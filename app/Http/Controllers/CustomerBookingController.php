<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\EntranceFee;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\BookingConfirmationEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class CustomerBookingController extends Controller
{
    /**
     * Show customer bookings page
     */
    public function bookingsPage()
    {
        return view('customerFolder.booking.my-bookings');
    }

    /**
     * Get customer's booking history
     */
    public function getCustomerBookings(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $userId = Auth::id();
        
        $status = $request->get('status', 'all');
        
        $bookings = Booking::with(['cart.items.unit', 'payments', 'entranceFee'])
            ->whereHas('cart', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($status !== 'all', function($query) use ($status) {
                $query->where('bookingStatus', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($booking) {
                // Format data for frontend
                $booking->formatted_created_at = $booking->created_at->format('Y-m-d h:i A');
                
                // ✅ FIX: Use cart dates if eventStartTime/eventEndTime are NULL
                $eventStart = $booking->eventStartTime 
                    ? Carbon::parse($booking->eventStartTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                    
                $eventEnd = $booking->eventEndTime 
                    ? Carbon::parse($booking->eventEndTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');
                
                $booking->formatted_event_start = $eventStart;
                $booking->formatted_event_end = $eventEnd;
                
                // Calculate total paid
                $booking->total_paid = $booking->payments->where('paymentStatus', 'completed')->sum('amountPaid');
                
                // Get accommodations list
                $booking->accommodations = $booking->cart->items->map(function($item) {
                    return [
                        'name' => $item->unit->unitName,
                        'type' => $item->unit->unitType,
                        'price' => $item->subtotalPrice
                    ];
                });
                
                return $booking;
            });

        return response()->json([
            'success' => true,
            'bookings' => $bookings,
            'total' => $bookings->count(),
            'pending' => $bookings->where('bookingStatus', 'pending')->count(),
            'confirmed' => $bookings->where('bookingStatus', 'confirmed')->count(),
            'completed' => $bookings->where('bookingStatus', 'completed')->count(),
            'cancelled' => $bookings->where('bookingStatus', 'cancelled')->count()
        ]);
    }

    /**
     * Get specific customer booking
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $userId = Auth::id();
        
        $booking = Booking::with(['cart.items.unit', 'payments', 'entranceFee'])
            ->whereHas('cart', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->findOrFail($id);

        // ✅ FIX: Use cart dates if eventStartTime/eventEndTime are NULL
        $eventStart = $booking->eventStartTime 
            ? date('Y-m-d', strtotime($booking->eventStartTime))
            : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
            
        $eventEnd = $booking->eventEndTime 
            ? date('Y-m-d', strtotime($booking->eventEndTime))
            : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');

        // Format booking details
        $booking->formatted_details = [
            'created_at' => $booking->created_at->format('Y-m-d h:i A'),
            'event_start' => $eventStart,
            'event_end' => $eventEnd,
            'total_price' => '₱' . number_format($booking->totalPrice, 2),
            'total_paid' => '₱' . number_format($booking->payments->where('paymentStatus', 'completed')->sum('amountPaid'), 2),
            'remaining_balance' => '₱' . number_format($booking->totalPrice - $booking->payments->where('paymentStatus', 'completed')->sum('amountPaid'), 2),
        ];

        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }

    /**
     * Cancel customer booking
     */
    public function cancelBooking($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            $userId = Auth::id();
            
            $booking = Booking::whereHas('cart', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->findOrFail($id);

            // Only allow cancellation for pending or confirmed bookings
            if (!in_array($booking->bookingStatus, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel booking with current status: ' . $booking->bookingStatus
                ], 400);
            }

            $booking->update([
                'bookingStatus' => 'cancelled',
                'cancelledAt' => now(),
                'cancelledBy' => $userId,
                'cancellationReason' => 'Cancelled by customer'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pre-validate cart before booking (can be called from frontend)
     */
    public function preValidateCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 401);
        }

        try {
            $userId = Auth::id();
            
            // Get active cart
            $cart = Cart::with(['items' => function($query) {
                    $query->where('isBooked', false);
                }, 'items.unit'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                    'cart_empty' => true
                ], 400);
            }

            $checkIn = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();
            
            // ✅ CRITICAL FIX: STRICT SPECIAL EVENT CHECK
            $specialEventConflict = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            
            if ($specialEventConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors' => [
                        'There is a special event scheduled during your selected dates.'
                    ]
                ], 400);
            }
            
            // ✅ STEP 2: Check if any unit in cart is marked for special events only
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected unit is reserved for special events only',
                        'has_special_event_unit_conflict' => true,
                        'special_event_details' => [
                            'unitName' => $unit->unitName
                        ],
                        'validation_errors' => [
                            "{$unit->unitName} is permanently reserved for special events only."
                        ]
                    ], 400);
                }
            }
            
            // ✅ STEP 3: Check individual units availability
            $unavailableItems = [];
            $validationErrors = [];
            
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                // 1. Check if unit is blocked for the selected dates
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                    
                    $unavailableItems[] = [
                        'cartItemID' => $item->cartItemID,
                        'unit' => $unit,
                        'reason' => 'Unit is blocked/unavailable from ' . $blockStart . ' to ' . $blockEnd,
                        'conflict_type' => 'unit_blocked'
                    ];
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    continue;
                }
                
                // 2. Check if unit is already booked for the selected dates
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = [
                        'cartItemID' => $item->cartItemID,
                        'unit' => $unit,
                        'reason' => 'Unit is already booked for the selected dates',
                        'conflict_type' => 'already_booked'
                    ];
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    continue;
                }
                
                // 3. Check unit status
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = [
                        'cartItemID' => $item->cartItemID,
                        'unit' => $unit,
                        'reason' => 'Unit is currently ' . $unit->unitStatus,
                        'conflict_type' => 'unit_status'
                    ];
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart are no longer available',
                    'unavailable_items' => $unavailableItems,
                    'validation_errors' => $validationErrors,
                    'has_availability_issues' => true
                ], 400);
            }

            // 4. Check if cart has mixed unit types
            $unitTypes = $cart->items->pluck('unit.unitType')->unique()->toArray();
            if (count($unitTypes) > 1 && in_array('room', $unitTypes) && in_array('cottage', $unitTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot have both rooms and cottages in the same booking',
                    'has_mixed_items' => true,
                    'validation_errors' => ['You cannot have both rooms and cottages in the same booking.']
                ], 400);
            }

            // 5. Check if cottage booking has active entrance fee
            $hasCottage = $cart->items->where('unit.unitType', 'cottage')->count() > 0;
            if ($hasCottage) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                if (!$entranceFee) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cottage booking requires active entrance fee',
                        'has_entrance_fee_issue' => true,
                        'validation_errors' => ['Cottage booking cannot proceed without an active entrance fee.']
                    ], 400);
                }
            }

            // ✅ FINAL CHECK: Double-check everything before approval
            $finalSpecialEventCheck = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            if ($finalSpecialEventCheck) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors' => [
                        'There is a special event scheduled during your selected dates.'
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'All items in cart are available for booking',
                'cart' => [
                    'cartID' => $cart->cartID,
                    'checkInDate' => $cart->checkInDate,
                    'checkOutDate' => $cart->checkOutDate,
                    'daysCount' => $cart->daysCount,
                    'numGuests' => $cart->numGuests
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
     * Store a new booking from customer - WITH COMPREHENSIVE VALIDATION
     */
    public function store(Request $request)
    {
        Log::info('=== CUSTOMER BOOKING START ===');
        Log::info('Request Data:', $request->all());
        Log::info('Authenticated User ID: ' . Auth::id());
        
        if (!Auth::check()) {
            Log::error('User not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Please login to complete booking'
            ], 401);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'booking_type' => 'required|in:day-use,overnight',
            'event_type' => 'required|string',
            'payment_method' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
            'special_requirements' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::id();
            
            // ✅ STEP 1: Find the ACTIVE cart with non-booked items
            $cart = Cart::with(['items' => function($query) {
                $query->where('isBooked', false);
            }, 'items.unit'])
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();

            // If no active cart, try to find any cart with non-booked items
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

            Log::info('Cart found:', ['cart' => $cart ? $cart->toArray() : null]);

            if (!$cart || $cart->items->isEmpty()) {
                Log::error('No items in cart');
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No items in cart or all items are already booked. Please add items to cart first.'
                ], 400);
            }

            // ========== ✅ STEP 2: RUN COMPREHENSIVE VALIDATION ==========
            Log::info('Starting comprehensive validation...');
            
            $checkIn = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();
            
            // ✅ CHECK 1: Special Event Conflict (STRICT)
            $specialEventConflict = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            
            if ($specialEventConflict) {
                DB::rollBack();
                Log::error('Special event conflict detected during booking');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period. There is a special event scheduled during your selected dates.',
                    'has_special_event_conflict' => true
                ], 400);
            }
            
            // ✅ CHECK 2: Check if any unit is marked for special events only
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    DB::rollBack();
                    Log::error('Special event unit conflict:', ['unit' => $unit->unitName]);
                    return response()->json([
                        'success' => false,
                        'message' => "{$unit->unitName} is permanently reserved for special events only.",
                        'has_special_event_unit_conflict' => true
                    ], 400);
                }
            }
            
            // ✅ CHECK 3: Individual unit availability
            $unavailableItems = [];
            $validationErrors = [];
            
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                // 3a. Check if unit is blocked for the selected dates
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                    
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    Log::warning("Unit blocked: {$unit->unitName} from {$blockStart} to {$blockEnd}");
                    continue;
                }
                
                // 3b. Check if unit is already booked for the selected dates
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    Log::warning("Unit already booked: {$unit->unitName}");
                    continue;
                }
                
                // 3c. Check unit status
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    Log::warning("Unit status not available: {$unit->unitName} - {$unit->unitStatus}");
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                DB::rollBack();
                Log::error('Unavailable items found:', $unavailableItems);
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart are no longer available',
                    'unavailable_items' => $unavailableItems,
                    'validation_errors' => $validationErrors,
                    'has_availability_issues' => true
                ], 400);
            }

            // ✅ CHECK 4: Mixed unit types validation
            $unitTypes = $cart->items->pluck('unit.unitType')->unique()->toArray();
            if (count($unitTypes) > 1 && in_array('room', $unitTypes) && in_array('cottage', $unitTypes)) {
                DB::rollBack();
                Log::error('Mixed unit types in cart');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot have both rooms and cottages in the same booking.',
                    'has_mixed_items' => true
                ], 400);
            }

            // ✅ CHECK 5: Cottage booking requires active entrance fee
            $hasCottages = $cart->items->contains(function($item) {
                return $item->unit->unitType === 'cottage';
            });
            
            Log::info('Has cottages in cart: ' . ($hasCottages ? 'YES' : 'NO'));
            
            if ($hasCottages) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                
                if (!$entranceFee) {
                    DB::rollBack();
                    Log::error('Cottages in cart but no active entrance fee');
                    return response()->json([
                        'success' => false,
                        'message' => 'Cottage booking cannot proceed without an active entrance fee.',
                        'has_entrance_fee_issue' => true
                    ], 400);
                }
            }
            
            // ✅ CHECK 6: FINAL SPECIAL EVENT CONFLICT CHECK (Double-check)
            $finalSpecialEventCheck = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            if ($finalSpecialEventCheck) {
                DB::rollBack();
                Log::error('Final special event conflict detected');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period.',
                    'has_special_event_conflict' => true
                ], 400);
            }
            
            Log::info('✅ All validation checks passed');
            // ========== END VALIDATION ==========

            // Get active entrance fee for cottage calculations
            $entranceFee = EntranceFee::where('isActive', true)->first();
            Log::info('Entrance fee:', ['entrance_fee' => $entranceFee ? $entranceFee->toArray() : null]);

            // Calculate total price based on unit type
            $subtotal = 0;
            $totalGuests = $cart->numGuests;
            
            // ✅ FIX: Ensure daysCount is at least 1
            $daysCount = max(1, $cart->daysCount);

            Log::info('Calculation parameters:', [
                'total_guests' => $totalGuests,
                'days_count_raw' => $cart->daysCount,
                'days_count_used' => $daysCount,
                'has_active_entrance_fee' => $entranceFee ? true : false,
                'entrance_fee_amount' => $entranceFee ? $entranceFee->amount : 'N/A'
            ]);

            foreach ($cart->items as $item) {
                $unit = $item->unit;
                $guests = $totalGuests;

                Log::info('Processing unit:', [
                    'unit_id' => $unit->unitID,
                    'unit_name' => $unit->unitName,
                    'unit_type' => $unit->unitType,
                    'unit_price' => $unit->unitRatePrice,
                    'guests' => $guests,
                    'days' => $daysCount
                ]);

                $itemSubtotal = 0;

                if ($unit->unitType === 'room') {
                    // ✅ ROOM CALCULATION
                    if ($guests == 1) {
                        $itemSubtotal = $unit->unitRatePrice * 2 * $daysCount;
                        Log::info('Room calculation (1 guest): ' . $unit->unitRatePrice . ' * 2 * ' . $daysCount . ' = ' . $itemSubtotal);
                    } else {
                        $itemSubtotal = $unit->unitRatePrice * $guests * $daysCount;
                        Log::info('Room calculation (' . $guests . ' guests): ' . $unit->unitRatePrice . ' * ' . $guests . ' * ' . $daysCount . ' = ' . $itemSubtotal);
                    }
                } elseif ($unit->unitType === 'cottage') {
                    // ✅ COTTAGE CALCULATION
                    if ($entranceFee) {
                        $entranceTotal = $entranceFee->amount * $guests;
                        $cottageTotal = $unit->unitRatePrice;
                        $itemSubtotal = $entranceTotal + $cottageTotal;
                        Log::info('Cottage calculation: (' . $entranceFee->amount . ' * ' . $guests . ') + ' . $unit->unitRatePrice . ' = ' . $itemSubtotal);
                    } else {
                        Log::warning('Cottage found but no entrance fee available for unit: ' . $unit->unitID);
                        $itemSubtotal = $unit->unitRatePrice;
                    }
                } else {
                    $itemSubtotal = $unit->unitRatePrice * $daysCount;
                    Log::info('Other unit calculation: ' . $unit->unitRatePrice . ' * ' . $daysCount . ' = ' . $itemSubtotal);
                }

                // Add to subtotal
                $subtotal += $itemSubtotal;
                Log::info('Item subtotal: ' . $itemSubtotal . ', Running subtotal: ' . $subtotal);

                // Update cart item subtotal if different
                if ($item->subtotalPrice != $itemSubtotal) {
                    $item->update(['subtotalPrice' => $itemSubtotal]);
                    Log::info('Updated cart item subtotalPrice to: ' . $itemSubtotal);
                }
            }

            // Ensure subtotal is not zero
            if ($subtotal <= 0) {
                DB::rollBack();
                Log::error('Calculated subtotal is zero or negative: ' . $subtotal);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid booking calculation. Please ensure all items have valid prices.'
                ], 400);
            }

            // Calculate total with tax and service fee
            $tax = $subtotal * 0.12;
            $serviceFee = $subtotal * 0.05;
            $totalWithTax = $subtotal + $tax + $serviceFee;

            Log::info('Total calculation:', [
                'subtotal' => $subtotal,
                'tax_12%' => $tax,
                'service_fee_5%' => $serviceFee,
                'total_with_tax' => $totalWithTax
            ]);

            // Validate payment amount
            $paymentAmount = floatval($request->payment_amount);
            $minPayment = $totalWithTax * 0.5;
            
            Log::info('Payment validation:', [
                'payment_amount' => $paymentAmount,
                'min_payment' => $minPayment,
                'total_with_tax' => $totalWithTax
            ]);
            
            if ($paymentAmount < $minPayment) {
                DB::rollBack();
                Log::error('Payment amount below minimum: ' . $paymentAmount . ' < ' . $minPayment);
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum payment is ₱' . number_format($minPayment, 2) . ' (50% downpayment)'
                ], 400);
            }
            
            if ($paymentAmount > $totalWithTax) {
                DB::rollBack();
                Log::error('Payment amount exceeds total: ' . $paymentAmount . ' > ' . $totalWithTax);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot exceed total amount of ₱' . number_format($totalWithTax, 2)
                ], 400);
            }

            // ========== ✅ STEP 3: DETERMINE BOOKING STATUS ==========
            $paymentType = 'downpayment';
            $remainingBalance = $totalWithTax - $paymentAmount;
            $bookingStatus = 'pending';
            $paymentStatus = 'pending';
            
            if ($paymentAmount >= $totalWithTax) {
                // Full payment = Auto-confirm booking
                $paymentType = 'full';
                $remainingBalance = 0;
                $bookingStatus = 'confirmed';
                $paymentStatus = 'completed';
                Log::info('✅ Full payment detected - Booking will be auto-confirmed');
            } else {
                // Downpayment = Pending booking
                $bookingStatus = 'pending';
                $paymentStatus = 'pending';
                Log::info('⏳ Downpayment detected - Booking will be pending approval');
            }

            Log::info('Payment details:', [
                'payment_type' => $paymentType,
                'remaining_balance' => $remainingBalance,
                'booking_status' => $bookingStatus,
                'payment_status' => $paymentStatus
            ]);
            // =======================================================

            // Create booking
            $booking = Booking::create([
                'cartID' => $cart->cartID,
                'numGuests' => $totalGuests,
                'totalPrice' => $totalWithTax,
                'entranceFeeID' => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                'bookingStatus' => $bookingStatus,
                'bookingType' => $request->booking_type,
                'eventType' => $request->event_type,
                'specialRequirements' => $request->special_requirements,
                'eventStartTime' => $cart->checkInDate,
                'eventEndTime' => $cart->checkOutDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Booking created:', [
                'booking_id' => $booking->bookingID,
                'status' => $bookingStatus
            ]);

            // Create payment record
            $paymentReference = 'VLE' . time() . $booking->bookingID;
            $payment = Payment::create([
                'bookingID' => $booking->bookingID,
                'paymentReference' => $paymentReference,
                'paymentMethod' => $request->payment_method,
                'paymentType' => $paymentType,
                'amountPaid' => $paymentAmount,
                'remainingBalance' => $remainingBalance,
                'paymentDate' => now(),
                'paymentStatus' => $paymentStatus,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Payment created:', [
                'payment_reference' => $paymentReference,
                'payment_status' => $paymentStatus
            ]);

            // ========== ✅ STEP 4: MARK CART ITEMS AS BOOKED ==========
            $updatedItems = CartItem::where('cartID', $cart->cartID)
                ->where('isBooked', false)
                ->update(['isBooked' => true]);
            
            Log::info('✅ Marked cart items as booked. Updated items count: ' . $updatedItems);
            
            // Update cart timestamp and mark as inactive
            $cart->update([
                'updated_at' => now(),
                'is_active' => false
            ]);
            
            Log::info('Cart marked as inactive for future bookings');
            // ===========================================================

            DB::commit();

            Log::info('=== CUSTOMER BOOKING SUCCESS ===');

            // Send booking confirmation email
            try {
                Mail::to($request->email)->send(new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit')));
                Log::info("Booking confirmation email sent to {$request->email} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send booking confirmation email: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => $bookingStatus === 'confirmed' 
                    ? 'Booking confirmed successfully! Your reservation is now active.'
                    : 'Booking submitted successfully! Your reservation is pending approval.',
                'booking_reference' => $paymentReference,
                'booking_id' => $booking->bookingID,
                'booking_status' => $bookingStatus,
                'payment_amount' => $paymentAmount,
                'is_confirmed' => $bookingStatus === 'confirmed',
                'items_marked_as_booked' => $updatedItems,
                'calculation_breakdown' => [
                    'subtotal' => $subtotal,
                    'tax_12%' => $tax,
                    'service_fee_5%' => $serviceFee,
                    'total_amount' => $totalWithTax,
                    'downpayment_paid' => $paymentAmount,
                    'remaining_balance' => $remainingBalance,
                    'total_guests' => $totalGuests,
                    'days_count' => $daysCount,
                    'booking_type' => $request->booking_type
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Booking Error: ' . $e->getMessage());
            Log::error('Booking Trace: ' . $e->getTraceAsString());
            Log::error('=== CUSTOMER BOOKING FAILED ===');
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for special event conflicts - STRICT VERSION
     */
    private function hasStrictSpecialEventConflict($checkIn, $checkOut)
    {
        // Convert to string dates for database comparison
        $checkInDate = $checkIn->format('Y-m-d');
        $checkOutDate = $checkOut->format('Y-m-d');

        // ✅ STRICT CHECK: ANY special event that overlaps with selected dates
        $hasConflict = DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->where('bookings.bookingType', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->where(function($query) use ($checkInDate, $checkOutDate) {
                // Check if special event date is within the selected date range
                $query->whereDate('carts.checkInDate', '>=', $checkInDate)
                      ->whereDate('carts.checkInDate', '<=', $checkOutDate);
            })
            ->exists();

        return $hasConflict;
    }

    /**
     * Check if unit is blocked for selected dates
     */
    private function isUnitBlocked($unit, $checkIn, $checkOut)
    {
        // Check if unit has block dates that overlap with selected dates
        if ($unit->blockStartDate && $unit->blockEndDate) {
            $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
            $blockEnd = Carbon::parse($unit->blockEndDate)->startOfDay();
            
            // Check for date overlap
            $hasOverlap = (
                ($checkIn->between($blockStart, $blockEnd, true)) ||
                ($checkOut->between($blockStart, $blockEnd, true)) ||
                ($blockStart->between($checkIn, $checkOut, true)) ||
                ($blockEnd->between($checkIn, $checkOut, true)) ||
                ($checkIn->lte($blockStart) && $checkOut->gte($blockEnd))
            );
            
            return $hasOverlap;
        }
        
        return false;
    }

    /**
     * Check if unit is already booked for selected dates
     */
    private function isUnitAlreadyBooked($unitId, $checkIn, $checkOut, $excludeCartId = null)
    {
        // Get all NORMAL bookings (not special events) that include this unit
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
            $existingCheckIn = Carbon::parse($booking->checkInDate)->startOfDay();
            $existingCheckOut = Carbon::parse($booking->checkOutDate)->startOfDay();
            
            // Check for date overlap
            $hasOverlap = (
                ($checkIn->between($existingCheckIn, $existingCheckOut, true)) ||
                ($checkOut->between($existingCheckIn, $existingCheckOut, true)) ||
                ($existingCheckIn->between($checkIn, $checkOut, true)) ||
                ($existingCheckOut->between($checkIn, $checkOut, true)) ||
                ($checkIn->lte($existingCheckIn) && $checkOut->gte($existingCheckOut))
            );
            
            if ($hasOverlap) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Clean up abandoned carts (carts with no bookings after 24 hours)
     * This can be run as a scheduled task (cron job)
     */
    public function cleanupAbandonedCarts()
    {
        try {
            $twentyFourHoursAgo = Carbon::now()->subHours(24);
            
            // Find carts that have no bookings and were created more than 24 hours ago
            $abandonedCarts = Cart::whereDoesntHave('booking')
                ->where('created_at', '<', $twentyFourHoursAgo)
                ->get();
            
            $deletedCount = 0;
            
            foreach ($abandonedCarts as $cart) {
                // Delete cart items first
                CartItem::where('cartID', $cart->cartID)->delete();
                // Then delete cart
                $cart->delete();
                $deletedCount++;
            }
            
            Log::info('Cleaned up ' . $deletedCount . ' abandoned carts');
            
            return response()->json([
                'success' => true,
                'message' => 'Cleaned up ' . $deletedCount . ' abandoned carts',
                'deleted_count' => $deletedCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error cleaning up abandoned carts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up abandoned carts: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get booking summary for a specific cart
     */
    public function getBookingSummary($cartId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        try {
            $userId = Auth::id();
            
            // Verify cart belongs to user
            $cart = Cart::with(['items.unit', 'booking'])
                ->where('cartID', $cartId)
                ->where('user_id', $userId)
                ->firstOrFail();
            
            // Check if cart has booking
            if ($cart->booking) {
                $booking = $cart->booking;
                
                return response()->json([
                    'success' => true,
                    'has_booking' => true,
                    'booking' => [
                        'id' => $booking->bookingID,
                        'status' => $booking->bookingStatus,
                        'total_price' => $booking->totalPrice,
                        'created_at' => $booking->created_at->format('Y-m-d H:i:s')
                    ],
                    'cart' => [
                        'id' => $cart->cartID,
                        'check_in' => $cart->checkInDate,
                        'check_out' => $cart->checkOutDate,
                        'guests' => $cart->numGuests,
                        'has_booked_items' => $cart->items->where('isBooked', true)->count() > 0
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'has_booking' => false,
                'message' => 'No booking found for this cart',
                'cart' => [
                    'id' => $cart->cartID,
                    'check_in' => $cart->checkInDate,
                    'check_out' => $cart->checkOutDate,
                    'guests' => $cart->numGuests,
                    'items' => $cart->items->map(function($item) {
                        return [
                            'id' => $item->cartItemID,
                            'unit_name' => $item->unit->unitName,
                            'unit_type' => $item->unit->unitType,
                            'is_booked' => $item->isBooked,
                            'subtotal' => $item->subtotalPrice
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking summary: ' . $e->getMessage()
            ], 500);
        }
    }
}