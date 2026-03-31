<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Display available rooms based on dates and guest count
     */
    public function getAvailableRooms(Request $request)
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

        // If no dates selected, return all available rooms
        if (!$checkIn || !$checkOut) {
            $rooms = Unit::where('unitType', 'room')
                ->where('unitStatus', 'available')
                ->get()
                ->map(function($room) {
                    return $this->formatRoomData($room);
                });

            return response()->json([
                'success' => true,
                'rooms' => $rooms,
                'search_params' => [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guestCount
                ],
                'message' => 'Showing all available rooms. Please select dates to check availability.'
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
                'rooms' => [],
                'search_params' => [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => $guestCount,
                    'days_count' => $daysCount
                ],
                'message' => 'No rooms available for the selected dates due to a special event booking.'
            ]);
        }

        // If dates are selected, check availability
        $availableRooms = Unit::where('unitType', 'room')
            ->where('capacity', '>=', $guestCount)
            ->get()
            ->filter(function($room) use ($checkIn, $checkOut) {
                return !$this->isUnitBlocked($room, $checkIn, $checkOut);
            })
            ->filter(function($room) use ($checkIn, $checkOut) {
                return !$this->checkBookingConflict($room->unitID, $checkIn, $checkOut);
            })
            ->map(function($room) {
                return $this->formatRoomData($room);
            });

        return response()->json([
            'success' => true,
            'rooms' => $availableRooms->values(),
            'search_params' => [
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests' => $guestCount,
                'days_count' => $daysCount
            ],
            'message' => $availableRooms->count() > 0 ? 'Available rooms found.' : 'No rooms available for the selected dates.'
        ]);
    }

    /**
     * Format room data with virtual tour URL
     */
    private function formatRoomData($room)
    {
        $roomData = $room->toArray();
        
        if ($room->virtualTourPanorama) {
            $roomData['virtual_tour_url'] = url('/virtual-tour?panorama=' . $room->virtualTourPanorama);
            $roomData['has_virtual_tour'] = true;
        } else {
            $roomData['virtual_tour_url'] = null;
            $roomData['has_virtual_tour'] = false;
        }
        
        return $roomData;
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
     * Add room to cart
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
                    'message' => 'Cannot book rooms for the selected dates due to a special event booking. Please choose different dates.'
                ], 400);
            }

            // Get unit details and check if blocked
            $unit = Unit::findOrFail($unitId);
            $isUnitBlocked = $this->isUnitBlocked($unit, $checkIn, $checkOut);
            
            if ($isUnitBlocked) {
                DB::rollBack();
                $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                
                return response()->json([
                    'success' => false,
                    'message' => "This room is not available from {$blockStart} to {$blockEnd} due to maintenance/blocking.",
                    'conflict_type' => 'unit_blocked'
                ], 400);
            }

            // Check for booking conflicts
            $hasBookingConflict = $this->checkBookingConflict($unitId, $checkIn, $checkOut);
            
            if ($hasBookingConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'This room is already booked for the selected dates. Please choose different dates or another room.',
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

            // ✅ ROOM CALCULATION: If single guest (1), multiply by 2
            if ($guests == 1) {
                $subtotal = $unit->unitRatePrice * 2 * $daysCount;
            } else {
                $subtotal = $unit->unitRatePrice * $guests * $daysCount;
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
                    'subtotalPrice' => $subtotal
                ]);
            }

            $cart->load('items.unit');
            $cartItemsCount = $cart->items->count();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Room added to cart successfully!',
                'cart_item' => $cartItem,
                'cart_count' => $cartItemsCount,
                'cart' => $cart,
                'items' => $cart->items,
                'calculation' => [
                    'unit_price' => $unit->unitRatePrice,
                    'days' => $daysCount,
                    'guests' => $guests,
                    'effective_guests' => ($guests == 1) ? 2 : $guests,
                    'formula' => $unit->unitRatePrice . ' × ' . (($guests == 1) ? 2 : $guests) . ' × ' . $daysCount,
                    'subtotal' => $subtotal,
                    'is_same_day' => ($checkIn === $checkOut)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add room to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all rooms (for initial load)
     */
    public function getAllRooms()
    {
        $rooms = Unit::where('unitType', 'room')
            ->where('unitStatus', 'available')
            ->get()
            ->map(function($room) {
                return $this->formatRoomData($room);
            });

        return response()->json([
            'success' => true,
            'rooms' => $rooms,
            'message' => 'All available rooms loaded'
        ]);
    }

    /**
     * Check date availability for special events
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
}