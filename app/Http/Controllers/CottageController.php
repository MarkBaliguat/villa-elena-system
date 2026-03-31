<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Booking;
use App\Models\EntranceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CottageController extends Controller
{
    /**
     * Format cottage data with virtual tour URL
     */
    private function formatCottageData($cottage)
    {
        $cottageData = $cottage->toArray();
        
        if ($cottage->virtualTourPanorama) {
            $cottageData['virtual_tour_url'] = url('/virtual-tour?panorama=' . $cottage->virtualTourPanorama);
            $cottageData['has_virtual_tour'] = true;
        } else {
            $cottageData['virtual_tour_url'] = null;
            $cottageData['has_virtual_tour'] = false;
        }
        
        return $cottageData;
    }

    /**
     * Display available cottages based on dates and guest count
     */
    public function getAvailableCottages(Request $request)
    {
        $request->validate([
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'guests' => 'nullable|integer|min:1'
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guestCount = $request->guests;

        // ✅ STANDARDIZE: Convert to date-only format
        if ($checkIn) {
            $checkIn = Carbon::parse($checkIn)->format('Y-m-d');
        }
        
        if ($checkOut) {
            $checkOut = Carbon::parse($checkOut)->format('Y-m-d');
        }

        // If no dates selected, return all available cottages
        if (!$checkIn || !$checkOut) {
            $cottages = Unit::where('unitType', 'cottage')
                ->where('unitStatus', 'available')
                ->get()
                ->map(function($cottage) {
                    return $this->formatCottageData($cottage);
                });

            return response()->json([
                'success' => true,
                'cottages' => $cottages,
                'search_params' => [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guestCount
                ],
                'message' => 'Showing all available cottages. Please select dates to check availability.'
            ]);
        }

        // Calculate days including same-day
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->startOfDay();
        
        $daysCount = $checkInDate->diffInDays($checkOutDate);
        if ($daysCount === 0) {
            $daysCount = 1;
        }

        // CHECK FOR SPECIAL EVENT BOOKINGS FIRST
        $specialEventBookings = Booking::where('bookingType', 'special-event')
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    $q->whereDate('eventStartTime', '<=', $checkOut)
                      ->whereDate('eventEndTime', '>=', $checkIn);
                });
            })
            ->whereIn('bookingStatus', ['pending', 'confirmed'])
            ->exists();

        if ($specialEventBookings) {
            return response()->json([
                'success' => true,
                'cottages' => [],
                'search_params' => [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guestCount,
                    'days_count' => $daysCount
                ],
                'message' => 'No cottages available for the selected dates due to a special event booking.'
            ]);
        }

        // If dates are selected, check availability
        $availableCottages = Unit::where('unitType', 'cottage')
            ->where('capacity', '>=', $guestCount)
            ->get()
            ->filter(function($cottage) use ($checkIn, $checkOut) {
                return !$this->isUnitBlocked($cottage, $checkIn, $checkOut);
            })
            ->filter(function($cottage) use ($checkIn, $checkOut) {
                return !$this->checkBookingConflict($cottage->unitID, $checkIn, $checkOut);
            })
            ->map(function($cottage) {
                return $this->formatCottageData($cottage);
            });

        return response()->json([
            'success' => true,
            'cottages' => $availableCottages->values(),
            'search_params' => [
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests' => $guestCount,
                'days_count' => $daysCount
            ],
            'message' => $availableCottages->count() > 0 ? 'Available cottages found.' : 'No cottages available for the selected dates.'
        ]);
    }

    /**
     * Check if unit is blocked based ONLY on blockStartDate and blockEndDate
     */
    private function isUnitBlocked($unit, $checkinDate, $checkoutDate)
    {
        if (!$unit->blockStartDate || !$unit->blockEndDate) {
            return false;
        }

        $newCheckin = Carbon::parse($checkinDate)->startOfDay();
        $newCheckout = Carbon::parse($checkoutDate ?? $checkinDate)->startOfDay();
        $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
        $blockEnd = Carbon::parse($unit->blockEndDate)->startOfDay();

        return (
            ($newCheckin->between($blockStart, $blockEnd)) ||
            ($newCheckout->between($blockStart, $blockEnd)) ||
            ($blockStart->between($newCheckin, $newCheckout)) ||
            ($blockEnd->between($newCheckin, $newCheckout)) ||
            ($newCheckin->lte($blockStart) && $newCheckout->gte($blockEnd))
        );
    }

    /**
     * Check for booking conflicts
     */
    private function checkBookingConflict($unitId, $checkinDate, $checkoutDate)
    {
        $existingBookings = Booking::whereHas('cart.cartItems', function($q) use ($unitId) {
                $q->where('unitID', $unitId);
            })
            ->whereIn('bookingStatus', ['confirmed', 'pending'])
            ->where('bookingType', '!=', 'special-event')
            ->with('cart')
            ->get();

        if ($existingBookings->isEmpty()) {
            return false;
        }

        $newCheckin = Carbon::parse($checkinDate)->startOfDay();
        $newCheckout = Carbon::parse($checkoutDate ?? $checkinDate)->startOfDay();

        foreach ($existingBookings as $booking) {
            if (!$booking->cart) continue;

            $existingCheckin = Carbon::parse($booking->cart->checkInDate)->startOfDay();
            $existingCheckout = Carbon::parse($booking->cart->checkOutDate)->startOfDay();

            $hasOverlap = (
                ($newCheckin->between($existingCheckin, $existingCheckout)) ||
                ($newCheckout->between($existingCheckin, $existingCheckout)) ||
                ($existingCheckin->between($newCheckin, $newCheckout)) ||
                ($existingCheckout->between($newCheckin, $newCheckout)) ||
                ($newCheckin->eq($existingCheckin) && $newCheckout->eq($existingCheckout))
            );

            if ($hasOverlap) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add cottage to cart - WITH ENTRANCE FEE VALIDATION
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first to add items to cart',
                'login_required' => true
            ], 401);
        }

        $request->validate([
            'unit_id' => 'required|exists:units,unitID',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $unitId = $request->unit_id;
            
            // ✅ STANDARDIZE: Convert to date-only format
            $checkIn = Carbon::parse($request->check_in)->format('Y-m-d');
            $checkOut = Carbon::parse($request->check_out)->format('Y-m-d');
            $guests = $request->guests;

            // Get unit details
            $unit = Unit::findOrFail($unitId);
            
            // ✅ CRITICAL: Check entrance fee BEFORE proceeding
            if ($unit->unitType === 'cottage') {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                
                if (!$entranceFee) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot book cottage. No active entrance fee found. Please contact villa management.',
                        'entrance_fee_required' => true,
                        'error_code' => 'NO_ACTIVE_ENTRANCE_FEE'
                    ], 400);
                }
            }

            // Check for special event bookings
            $specialEventBookings = Booking::where('bookingType', 'special-event')
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where(function($q) use ($checkIn, $checkOut) {
                        $q->whereDate('eventStartTime', '<=', $checkOut)
                          ->whereDate('eventEndTime', '>=', $checkIn);
                    });
                })
                ->whereIn('bookingStatus', ['pending', 'confirmed'])
                ->exists();

            if ($specialEventBookings) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book cottages for the selected dates due to a special event booking. Please choose different dates.'
                ], 400);
            }

            // Check if unit is blocked
            $isUnitBlocked = $this->isUnitBlocked($unit, $checkIn, $checkOut);
            
            if ($isUnitBlocked) {
                DB::rollBack();
                $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                
                return response()->json([
                    'success' => false,
                    'message' => "This cottage is not available from {$blockStart} to {$blockEnd} due to maintenance/blocking.",
                    'conflict_type' => 'unit_blocked'
                ], 400);
            }

            // Check for booking conflicts
            $hasBookingConflict = $this->checkBookingConflict($unitId, $checkIn, $checkOut);
            
            if ($hasBookingConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'This cottage is already booked for the selected dates. Please choose different dates or another cottage.',
                    'conflict_type' => 'normal_booking'
                ], 400);
            }

            // Calculate days count
            $checkInDate = Carbon::parse($checkIn)->startOfDay();
            $checkOutDate = Carbon::parse($checkOut)->startOfDay();
            
            $daysCount = $checkInDate->diffInDays($checkOutDate);
            if ($daysCount === 0) {
                $daysCount = 1;
            }

            // ✅ Check if user has an active cart
            $cart = Cart::where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart) {
                // Create new active cart — NO numGuests here
                $cart = Cart::create([
                    'user_id' => $userId,
                    'checkInDate' => $checkIn,
                    'checkOutDate' => $checkOut,
                    'daysCount' => $daysCount,
                    'is_active' => true
                ]);
            } else {
                $existingCheckIn = Carbon::parse($cart->checkInDate)->format('Y-m-d');
                $existingCheckOut = Carbon::parse($cart->checkOutDate)->format('Y-m-d');
                
                if ($existingCheckIn !== $checkIn || $existingCheckOut !== $checkOut) {
                    $existingCartItems = CartItem::where('cartID', $cart->cartID)
                        ->where('isBooked', false)
                        ->count();
                    
                    if ($existingCartItems > 0) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'You have items in your cart with different dates (' . $existingCheckIn . ' to ' . $existingCheckOut . '). Please complete or clear that booking first before selecting new dates.',
                            'has_existing_cart' => true,
                            'existing_dates' => [
                                'check_in' => $existingCheckIn,
                                'check_out' => $existingCheckOut
                            ]
                        ], 400);
                    } else {
                        // Update cart dates only — NO numGuests
                        $cart->update([
                            'checkInDate' => $checkIn,
                            'checkOutDate' => $checkOut,
                            'daysCount' => $daysCount,
                        ]);
                    }
                }
                // ✅ Removed: $cart->update(['numGuests' => $guests]) — numGuests now lives in CartItem
            }

            // Calculate subtotal based on unit type
            $subtotal = 0;
            $calculationBreakdown = [];
            
            if ($unit->unitType === 'room') {
                if ($guests == 1) {
                    $subtotal = $unit->unitRatePrice * 2 * $daysCount;
                } else {
                    $subtotal = $unit->unitRatePrice * $guests * $daysCount;
                }
                $calculationBreakdown = [
                    'formula' => $unit->unitRatePrice . ' × ' . ($guests == 1 ? 2 : $guests) . ' × ' . $daysCount . ' days',
                    'type' => 'room'
                ];
            } elseif ($unit->unitType === 'cottage') {
                // ✅ COTTAGE calculation with entrance fee (already validated above)
                $entranceFee = EntranceFee::where('isActive', true)->first();
                $entranceTotal = $entranceFee->amount * $guests;
                $subtotal = $entranceTotal + $unit->unitRatePrice;
                $calculationBreakdown = [
                    'formula' => '(' . $entranceFee->amount . ' × ' . $guests . ') + ' . $unit->unitRatePrice,
                    'type' => 'cottage',
                    'entrance_fee' => $entranceFee->amount,
                    'entrance_total' => $entranceTotal,
                    'cottage_price' => $unit->unitRatePrice,
                    'entrance_fee_id' => $entranceFee->entranceFeeID
                ];
            }

            // Check if item already exists in cart
            $existingCartItem = CartItem::where('cartID', $cart->cartID)
                ->where('unitID', $unitId)
                ->first();

            if ($existingCartItem) {
                // ✅ Update numGuests in CartItem
                $existingCartItem->update([
                    'numGuests' => $guests,
                    'subtotalPrice' => $subtotal
                ]);
                $cartItem = $existingCartItem;
            } else {
                // ✅ Create CartItem with numGuests
                $cartItem = CartItem::create([
                    'cartID' => $cart->cartID,
                    'unitID' => $unitId,
                    'numGuests' => $guests,
                    'subtotalPrice' => $subtotal,
                    'isBooked' => false
                ]);
            }

            $cart->load('items.unit');
            $cartItemsCount = $cart->items->count();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($unit->unitType) . ' added to cart successfully!',
                'cart_item' => $cartItem,
                'cart_count' => $cartItemsCount,
                'cart' => $cart,
                'items' => $cart->items,
                'calculation' => [
                    'unit_type' => $unit->unitType,
                    'unit_price' => $unit->unitRatePrice,
                    'days' => $daysCount,
                    'guests' => $guests,
                    'subtotal' => $subtotal,
                ],
                'calculation_breakdown' => $calculationBreakdown,
                'has_active_entrance_fee' => $unit->unitType === 'cottage' ? true : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all cottages (for initial load) - WITH ENTRANCE FEE CHECK
     */
    public function getAllCottages()
    {
        $entranceFee = EntranceFee::where('isActive', true)->first();
        
        $cottages = Unit::where('unitType', 'cottage')
            ->where('unitStatus', 'available')
            ->get()
            ->map(function($cottage) {
                return $this->formatCottageData($cottage);
            });

        return response()->json([
            'success' => true,
            'cottages' => $cottages,
            'has_active_entrance_fee' => !is_null($entranceFee),
            'entrance_fee_amount' => $entranceFee ? $entranceFee->amount : 0,
            'message' => $entranceFee ? 
                'All available cottages loaded. Entrance fee: ₱' . $entranceFee->amount : 
                '⚠️ Warning: No active entrance fee found. Cottage booking is disabled.'
        ]);
    }

    /**
     * Check date availability for cottages
     */
    public function checkDateAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in'
        ]);

        $checkIn = Carbon::parse($request->check_in)->format('Y-m-d');
        $checkOut = Carbon::parse($request->check_out)->format('Y-m-d');

        $specialEventBookings = Booking::where('bookingType', 'special-event')
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    $q->whereDate('eventStartTime', '<=', $checkOut)
                      ->whereDate('eventEndTime', '>=', $checkIn);
                });
            })
            ->whereIn('bookingStatus', ['pending', 'confirmed'])
            ->exists();

        return response()->json([
            'success' => true,
            'available' => !$specialEventBookings,
            'message' => $specialEventBookings ? 
                'Dates are not available due to special event booking.' : 
                'Dates are available for booking.'
        ]);
    }

    /**
     * Get current active entrance fee - WITH VALIDATION
     */
    public function getEntranceFee()
    {
        try {
            $entranceFee = EntranceFee::where('isActive', true)->first();
            
            if (!$entranceFee) {
                return response()->json([
                    'success' => false,
                    'entrance_fee' => null,
                    'has_active_entrance_fee' => false,
                    'message' => 'No active entrance fee found. Cottage booking is currently disabled.',
                    'booking_disabled' => true
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'entrance_fee' => [
                    'entranceFeeID' => $entranceFee->entranceFeeID,
                    'feeName' => $entranceFee->feeName,
                    'amount' => floatval($entranceFee->amount),
                    'isActive' => $entranceFee->isActive,
                    'created_at' => $entranceFee->created_at,
                    'updated_at' => $entranceFee->updated_at
                ],
                'has_active_entrance_fee' => true,
                'message' => 'Active entrance fee found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch entrance fee: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Validate if cottage booking is allowed (check entrance fee)
     */
    public function validateCottageBooking(Request $request)
    {
        try {
            $entranceFee = EntranceFee::where('isActive', true)->first();
            
            if (!$entranceFee) {
                return response()->json([
                    'success' => false,
                    'allowed' => false,
                    'message' => 'Cottage booking is not allowed because there is no active entrance fee.',
                    'action_required' => 'Please contact villa management to set up an entrance fee.',
                    'booking_disabled' => true
                ]);
            }
            
            return response()->json([
                'success' => true,
                'allowed' => true,
                'entrance_fee' => [
                    'amount' => floatval($entranceFee->amount),
                    'feeName' => $entranceFee->feeName
                ],
                'message' => 'Cottage booking is allowed. Entrance fee: ₱' . $entranceFee->amount . ' per guest'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating cottage booking: ' . $e->getMessage()
            ], 500);
        }
    }
}