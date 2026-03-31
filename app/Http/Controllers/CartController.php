<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Unit;
use App\Models\EntranceFee;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Get active cart items (non-booked items only)
     */
    public function getActiveCartItems()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first to view cart',
                'login_required' => true
            ], 401);
        }

        try {
            $userId = Auth::id();
            
            $cart = Cart::with(['items' => function($query) {
                    $query->where('isBooked', false);
                }, 'items.unit'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$cart) {
                $cart = Cart::with(['items' => function($query) {
                        $query->where('isBooked', false);
                    }, 'items.unit'])
                    ->where('user_id', $userId)
                    ->whereHas('items', function($query) {
                        $query->where('isBooked', false);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'cart' => null,
                    'items' => [],
                    'total_items' => 0,
                    'message' => 'Your cart is empty'
                ]);
            }

            $entranceFee = EntranceFee::where('isActive', true)->first();
            $entranceFeeAmount = $entranceFee ? $entranceFee->amount : 0;
            $hasActiveEntranceFee = !is_null($entranceFee);

            $unitTypes = $cart->items->pluck('unit.unitType')->unique()->toArray();
            $cartType = count($unitTypes) === 1 ? $unitTypes[0] : 'mixed';

            $daysCount = max(1, $cart->daysCount);

            // ✅ Compute summary guest info from cart_items
            $allGuestCounts = $cart->items->pluck('numGuests')->unique()->values();
            $guestSummary = $allGuestCounts->count() === 1
                ? $allGuestCounts->first()
                : $allGuestCounts->implode(', ');
            $guestVaries = $allGuestCounts->count() > 1;

            return response()->json([
                'success' => true,
                'cart' => [
                    'cartID'      => $cart->cartID,
                    'checkInDate' => Carbon::parse($cart->checkInDate)->format('Y-m-d'),
                    'checkOutDate'=> Carbon::parse($cart->checkOutDate)->format('Y-m-d'),
                    'daysCount'   => $daysCount,
                    // ✅ numGuests is now per cart_item — these are aggregate helpers for the UI
                    'guestSummary' => $guestSummary,
                    'guestVaries'  => $guestVaries,
                    'user_id'     => $cart->user_id,
                    'is_active'   => $cart->is_active
                ],
                'items' => $cart->items->map(function($item) use ($entranceFeeAmount, $hasActiveEntranceFee, $daysCount) {
                    $unit      = $item->unit;
                    // ✅ Read numGuests from cart_item, NOT from cart
                    $numGuests = $item->numGuests;
                    
                    $subtotal    = 0;
                    $calculation = '';
                    
                    if ($unit->unitType === 'room') {
                        $effectiveGuests = $numGuests == 1 ? 2 : $numGuests;
                        $subtotal        = $unit->unitRatePrice * $effectiveGuests * $daysCount;
                        $calculation     = "{$unit->unitRatePrice} × {$effectiveGuests} × {$daysCount} days";
                    } elseif ($unit->unitType === 'cottage') {
                        if ($hasActiveEntranceFee) {
                            $subtotal    = ($entranceFeeAmount * $numGuests) + $unit->unitRatePrice;
                            $calculation = "({$entranceFeeAmount} × {$numGuests}) + {$unit->unitRatePrice}";
                        } else {
                            $subtotal    = $unit->unitRatePrice;
                            $calculation = "{$unit->unitRatePrice} (No entrance fee)";
                        }
                    }
                    
                    return [
                        'cartItemID'          => $item->cartItemID,
                        'unitID'              => $item->unitID,
                        // ✅ numGuests is per item now
                        'numGuests'           => $numGuests,
                        'subtotalPrice'       => $item->subtotalPrice,
                        'isBooked'            => $item->isBooked,
                        'calculatedSubtotal'  => $subtotal,
                        'calculation'         => $calculation,
                        'unit'                => $unit
                    ];
                }),
                'entrance_fee'           => $entranceFeeAmount,
                'has_active_entrance_fee'=> $hasActiveEntranceFee,
                'cart_type'              => $cartType,
                'total_items'            => $cart->items->count(),
                'message'                => 'Cart items loaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart item count (for navbar badge)
     */
    public function getCartItemCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'count'   => 0,
                'message' => 'User not logged in'
            ]);
        }

        try {
            $userId = Auth::id();
            
            $cart = Cart::where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'count'   => 0,
                    'message' => 'No active cart found'
                ]);
            }

            $itemCount = CartItem::where('cartID', $cart->cartID)
                ->where('isBooked', false)
                ->count();

            return response()->json([
                'success' => true,
                'count'   => $itemCount,
                'cart_id' => $cart->cartID
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cart count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartItemId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            DB::beginTransaction();

            $cartItem = CartItem::findOrFail($cartItemId);
            
            $userId = Auth::id();
            $cart   = Cart::where('cartID', $cartItem->cartID)
                          ->where('user_id', $userId)
                          ->first();

            if (!$cart) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found or access denied'
                ], 404);
            }

            if ($cartItem->isBooked) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove booked item. Please contact admin.'
                ], 400);
            }

            $cartItem->delete();

            $remainingItems = CartItem::where('cartID', $cart->cartID)
                ->where('isBooked', false)
                ->count();

            if ($remainingItems === 0) {
                $cart->update(['is_active' => false]);
                DB::commit();
                return response()->json([
                    'success'         => true,
                    'message'         => 'Item removed from cart. Cart is now empty.',
                    'cart_empty'      => true,
                    'remaining_items' => 0
                ]);
            }

            DB::commit();
            return response()->json([
                'success'         => true,
                'message'         => 'Item removed from cart successfully',
                'cart_empty'      => false,
                'remaining_items' => $remainingItems
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear entire cart (only non-booked items)
     */
    public function clearCart()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $cart   = Cart::where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is already empty'
                ]);
            }

            CartItem::where('cartID', $cart->cartID)
                ->where('isBooked', false)
                ->delete();

            $cart->update(['is_active' => false]);
                
            DB::commit();
            return response()->json([
                'success'      => true,
                'message'      => 'Cart cleared successfully',
                'cart_deleted' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate cart items before checkout
     */
    public function validateCartBeforeCheckout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'       => false,
                'message'       => 'Please login first',
                'login_required'=> true
            ], 401);
        }

        try {
            $userId = Auth::id();
            
            $cart = Cart::with(['items' => function($query) {
                    $query->where('isBooked', false);
                }, 'items.unit'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Cart is empty',
                    'cart_empty' => true
                ], 400);
            }

            $checkIn  = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();
            
            // ✅ STEP 1: Special event conflict check
            $specialEventConflict = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            
            if ($specialEventConflict) {
                return response()->json([
                    'success'                    => false,
                    'message'                    => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors'          => [
                        'There is a special event scheduled during your selected dates.'
                    ]
                ], 400);
            }
            
            // ✅ STEP 2: Check if any unit is reserved for special events only
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    return response()->json([
                        'success'                         => false,
                        'message'                         => 'Selected unit is reserved for special events only',
                        'has_special_event_unit_conflict' => true,
                        'special_event_details'           => ['unitName' => $unit->unitName],
                        'validation_errors'               => [
                            "{$unit->unitName} is permanently reserved for special events only."
                        ]
                    ], 400);
                }
            }
            
            // ✅ STEP 3: Individual unit availability checks
            $unavailableItems = [];
            $validationErrors = [];
            
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate
                        ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd   = $unit->blockEndDate
                        ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                    
                    $unavailableItems[] = [
                        'cartItemID'    => $item->cartItemID,
                        'unit'          => $unit,
                        'reason'        => "Unit is blocked/unavailable from {$blockStart} to {$blockEnd}",
                        'conflict_type' => 'unit_blocked'
                    ];
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    continue;
                }
                
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = [
                        'cartItemID'    => $item->cartItemID,
                        'unit'          => $unit,
                        'reason'        => 'Unit is already booked for the selected dates',
                        'conflict_type' => 'already_booked'
                    ];
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    continue;
                }
                
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = [
                        'cartItemID'    => $item->cartItemID,
                        'unit'          => $unit,
                        'reason'        => 'Unit is currently ' . $unit->unitStatus,
                        'conflict_type' => 'unit_status'
                    ];
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json([
                    'success'               => false,
                    'message'               => 'Some items in your cart are no longer available',
                    'unavailable_items'     => $unavailableItems,
                    'validation_errors'     => $validationErrors,
                    'has_availability_issues'=> true
                ], 400);
            }

            // ✅ STEP 4: Cottage entrance fee check
            $hasCottage = $cart->items->where('unit.unitType', 'cottage')->count() > 0;
            if ($hasCottage) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                if (!$entranceFee) {
                    return response()->json([
                        'success'                 => false,
                        'message'                 => 'Cottage booking requires active entrance fee',
                        'has_entrance_fee_issue'  => true,
                        'validation_errors'       => [
                            'Cottage booking cannot proceed without an active entrance fee.'
                        ]
                    ], 400);
                }
            }

            // ✅ FINAL: Double-check special event
            if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                return response()->json([
                    'success'                    => false,
                    'message'                    => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors'          => [
                        'There is a special event scheduled during your selected dates.'
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'All items in cart are available for booking',
                'cart'    => [
                    'cartID'      => $cart->cartID,
                    'checkInDate' => $cart->checkInDate,
                    'checkOutDate'=> $cart->checkOutDate,
                    'daysCount'   => $cart->daysCount,
                    // ✅ numGuests now reported per-item, not from cart
                ],
                'total_items' => $cart->items->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for special event conflicts - STRICT VERSION
     */
    private function hasStrictSpecialEventConflict($checkIn, $checkOut)
    {
        $checkInDate  = $checkIn->format('Y-m-d');
        $checkOutDate = $checkOut->format('Y-m-d');

        return DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->where('bookings.bookingType', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->where(function($query) use ($checkInDate, $checkOutDate) {
                $query->whereDate('carts.checkInDate', '>=', $checkInDate)
                      ->whereDate('carts.checkInDate', '<=', $checkOutDate);
            })
            ->exists();
    }

    /**
     * Check if unit is blocked for selected dates
     */
    private function isUnitBlocked($unit, $checkIn, $checkOut)
    {
        if ($unit->blockStartDate && $unit->blockEndDate) {
            $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
            $blockEnd   = Carbon::parse($unit->blockEndDate)->startOfDay();
            
            return (
                $checkIn->between($blockStart, $blockEnd, true) ||
                $checkOut->between($blockStart, $blockEnd, true) ||
                $blockStart->between($checkIn, $checkOut, true) ||
                $blockEnd->between($checkIn, $checkOut, true) ||
                ($checkIn->lte($blockStart) && $checkOut->gte($blockEnd))
            );
        }
        
        return false;
    }

    /**
     * Check if unit is already booked for selected dates
     */
    private function isUnitAlreadyBooked($unitId, $checkIn, $checkOut, $excludeCartId = null)
    {
        $existingBookings = DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->join('cart_items', 'carts.cartID', '=', 'cart_items.cartID')
            ->where('cart_items.unitID', $unitId)
            ->where('cart_items.isBooked', true)
            ->where('bookings.bookingType', '!=', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->when($excludeCartId, function($query) use ($excludeCartId) {
                return $query->where('carts.cartID', '!=', $excludeCartId);
            })
            ->select('carts.checkInDate', 'carts.checkOutDate')
            ->get();
        
        foreach ($existingBookings as $booking) {
            $existingCheckIn  = Carbon::parse($booking->checkInDate)->startOfDay();
            $existingCheckOut = Carbon::parse($booking->checkOutDate)->startOfDay();
            
            $hasOverlap = (
                $checkIn->between($existingCheckIn, $existingCheckOut, true) ||
                $checkOut->between($existingCheckIn, $existingCheckOut, true) ||
                $existingCheckIn->between($checkIn, $checkOut, true) ||
                $existingCheckOut->between($checkIn, $checkOut, true) ||
                ($checkIn->lte($existingCheckIn) && $checkOut->gte($existingCheckOut))
            );
            
            if ($hasOverlap) return true;
        }
        
        return false;
    }
}