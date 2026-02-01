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
use App\Services\PayMongoService;

class CustomerBookingController extends Controller
{
    protected $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

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
                $booking->formatted_created_at = $booking->created_at->format('Y-m-d h:i A');
                
                $eventStart = $booking->eventStartTime 
                    ? Carbon::parse($booking->eventStartTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                    
                $eventEnd = $booking->eventEndTime 
                    ? Carbon::parse($booking->eventEndTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');
                
                $booking->formatted_event_start = $eventStart;
                $booking->formatted_event_end = $eventEnd;
                
                $booking->total_paid = $booking->payments->where('paymentStatus', 'completed')->sum('amountPaid');
                
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

        $eventStart = $booking->eventStartTime 
            ? date('Y-m-d', strtotime($booking->eventStartTime))
            : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
            
        $eventEnd = $booking->eventEndTime 
            ? date('Y-m-d', strtotime($booking->eventEndTime))
            : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');

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
            
            $unavailableItems = [];
            $validationErrors = [];
            
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
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

            $unitTypes = $cart->items->pluck('unit.unitType')->unique()->toArray();
            if (count($unitTypes) > 1 && in_array('room', $unitTypes) && in_array('cottage', $unitTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot have both rooms and cottages in the same booking',
                    'has_mixed_items' => true,
                    'validation_errors' => ['You cannot have both rooms and cottages in the same booking.']
                ], 400);
            }

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
     * ✅ COMPLETELY REWRITTEN: Store booking - VALIDATION FIRST, NO DATABASE WRITES UNTIL PAYMENT CONFIRMED
     */
    public function store(Request $request)
    {
        Log::info('=== CUSTOMER BOOKING START ===');
        Log::info('Request Data:', $request->all());
        
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
            $userId = Auth::id();
            
            // ========== PHASE 1: CART RETRIEVAL & VALIDATION (NO DATABASE WRITES) ==========
            Log::info('PHASE 1: Starting cart retrieval and validation...');
            
            $cart = Cart::with(['items' => function($query) {
                $query->where('isBooked', false);
            }, 'items.unit'])
            ->where('user_id', $userId)
            ->where('is_active', true)
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
                Log::error('No items in cart');
                return response()->json([
                    'success' => false,
                    'message' => 'No items in cart. Please add items to cart first.'
                ], 400);
            }

            $checkIn = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();
            
            // ========== VALIDATION 1: Special Event Conflict ==========
            Log::info('VALIDATION 1: Checking special event conflicts...');
            $specialEventConflict = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            
            if ($specialEventConflict) {
                Log::error('Special event conflict detected');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period.',
                    'has_special_event_conflict' => true
                ], 400);
            }
            
            // ========== VALIDATION 2: Special Event Units ==========
            Log::info('VALIDATION 2: Checking for special event only units...');
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    Log::error('Special event unit conflict:', ['unit' => $unit->unitName]);
                    return response()->json([
                        'success' => false,
                        'message' => "{$unit->unitName} is permanently reserved for special events only.",
                        'has_special_event_unit_conflict' => true
                    ], 400);
                }
            }
            
            // ========== VALIDATION 3: Unit Availability ==========
            Log::info('VALIDATION 3: Checking individual unit availability...');
            $unavailableItems = [];
            $validationErrors = [];
            
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                    
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    Log::warning("Unit blocked: {$unit->unitName}");
                    continue;
                }
                
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    Log::warning("Unit already booked: {$unit->unitName}");
                    continue;
                }
                
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    Log::warning("Unit status not available: {$unit->unitName}");
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                Log::error('Unavailable items found:', $unavailableItems);
                return response()->json([
                    'success' => false,
                    'message' => 'Some items are no longer available',
                    'unavailable_items' => $unavailableItems,
                    'validation_errors' => $validationErrors
                ], 400);
            }

            // ========== VALIDATION 4: Mixed Unit Types ==========
            Log::info('VALIDATION 4: Checking for mixed unit types...');
            $unitTypes = $cart->items->pluck('unit.unitType')->unique()->toArray();
            if (count($unitTypes) > 1 && in_array('room', $unitTypes) && in_array('cottage', $unitTypes)) {
                Log::error('Mixed unit types in cart');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot have both rooms and cottages in the same booking.'
                ], 400);
            }

            // ========== VALIDATION 5: Entrance Fee (for Cottages) ==========
            Log::info('VALIDATION 5: Checking entrance fee requirement...');
            $hasCottages = $cart->items->contains(function($item) {
                return $item->unit->unitType === 'cottage';
            });
            
            $entranceFee = null;
            if ($hasCottages) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                
                if (!$entranceFee) {
                    Log::error('No active entrance fee for cottage booking');
                    return response()->json([
                        'success' => false,
                        'message' => 'Cottage booking requires active entrance fee.'
                    ], 400);
                }
            }
            
            // ========== VALIDATION 6: Final Special Event Check ==========
            Log::info('VALIDATION 6: Final special event check...');
            $finalSpecialEventCheck = $this->hasStrictSpecialEventConflict($checkIn, $checkOut);
            if ($finalSpecialEventCheck) {
                Log::error('Final special event conflict detected');
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot book during special event period.'
                ], 400);
            }
            
            Log::info('✅ ALL VALIDATIONS PASSED - Proceeding to payment phase...');
            
            // ========== PHASE 2: CALCULATE PRICING (NO DATABASE WRITES) ==========
            Log::info('PHASE 2: Calculating pricing...');
            
            $subtotal = 0;
            $totalGuests = $cart->numGuests;
            $daysCount = max(1, $cart->daysCount);

            foreach ($cart->items as $item) {
                $unit = $item->unit;
                $itemSubtotal = 0;

                if ($unit->unitType === 'room') {
                    if ($totalGuests == 1) {
                        $itemSubtotal = $unit->unitRatePrice * 2 * $daysCount;
                    } else {
                        $itemSubtotal = $unit->unitRatePrice * $totalGuests * $daysCount;
                    }
                } elseif ($unit->unitType === 'cottage') {
                    if ($entranceFee) {
                        $entranceTotal = $entranceFee->amount * $totalGuests;
                        $itemSubtotal = $entranceTotal + $unit->unitRatePrice;
                    } else {
                        $itemSubtotal = $unit->unitRatePrice;
                    }
                } else {
                    $itemSubtotal = $unit->unitRatePrice * $daysCount;
                }

                $subtotal += $itemSubtotal;
            }

            if ($subtotal <= 0) {
                Log::error('Invalid subtotal calculated: ' . $subtotal);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid booking calculation.'
                ], 400);
            }

            $tax = $subtotal * 0.12;
            $serviceFee = $subtotal * 0.05;
            $totalWithTax = $subtotal + $tax + $serviceFee;

            // ========== VALIDATION 7: Payment Amount ==========
            Log::info('VALIDATION 7: Validating payment amount...');
            $paymentAmount = floatval($request->payment_amount);
            $minPayment = $totalWithTax * 0.5;
            
            if ($paymentAmount < $minPayment) {
                Log::error('Payment below minimum: ' . $paymentAmount . ' < ' . $minPayment);
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum payment is ₱' . number_format($minPayment, 2) . ' (50% downpayment)'
                ], 400);
            }
            
            if ($paymentAmount > $totalWithTax) {
                Log::error('Payment exceeds total: ' . $paymentAmount . ' > ' . $totalWithTax);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot exceed total amount of ₱' . number_format($totalWithTax, 2)
                ], 400);
            }

            // ========== DETERMINE BOOKING & PAYMENT STATUS ==========
            $paymentType = 'downpayment';
            $remainingBalance = $totalWithTax - $paymentAmount;
            $bookingStatus = 'pending';
            $paymentStatus = 'pending';
            
            if ($paymentAmount >= $totalWithTax) {
                $paymentType = 'full';
                $remainingBalance = 0;
                $bookingStatus = 'confirmed';
                $paymentStatus = 'completed';
            }

            Log::info('Payment details calculated:', [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'service_fee' => $serviceFee,
                'total' => $totalWithTax,
                'payment_amount' => $paymentAmount,
                'payment_type' => $paymentType,
                'booking_status' => $bookingStatus
            ]);

            // ========== PHASE 3: PAYMENT METHOD ROUTING ==========
            Log::info('PHASE 3: Routing based on payment method: ' . $request->payment_method);
            
            if ($request->payment_method === 'gcash') {
                // ✅ FOR GCASH: Return payment info WITHOUT creating booking yet
                Log::info('✅ GCash payment selected - Returning payment parameters for frontend to initiate payment');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Ready for GCash payment',
                    'payment_method' => 'gcash',
                    'requires_payment_first' => true,
                    'booking_data' => [
                        'cart_id' => $cart->cartID,
                        'num_guests' => $totalGuests,
                        'total_price' => $totalWithTax,
                        'entrance_fee_id' => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                        'booking_type' => $request->booking_type,
                        'event_type' => $request->event_type,
                        'special_requirements' => $request->special_requirements,
                        'event_start' => $cart->checkInDate,
                        'event_end' => $cart->checkOutDate,
                    ],
                    'payment_data' => [
                        'payment_amount' => $paymentAmount,
                        'payment_type' => $paymentType,
                        'remaining_balance' => $remainingBalance,
                        'booking_status' => $bookingStatus,
                        'payment_status' => $paymentStatus
                    ]
                ]);
            }
            
            // ✅ FOR CASH: Proceed with database transaction
            Log::info('✅ Cash payment selected - Proceeding with database transaction');
            
            DB::beginTransaction();
            
            try {
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

                Log::info('Booking created:', ['booking_id' => $booking->bookingID]);

                // Create payment record
                $paymentReference = 'VLE' . time() . $booking->bookingID;
                
                $payment = Payment::create([
                    'bookingID' => $booking->bookingID,
                    'paymentReference' => $paymentReference,
                    'paymentMethod' => 'cash',
                    'paymentType' => $paymentType,
                    'amountPaid' => $paymentAmount,
                    'remainingBalance' => $remainingBalance,
                    'paymentDate' => now(),
                    'paymentStatus' => $paymentStatus,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Payment record created:', ['payment_reference' => $paymentReference]);
                
                // Mark cart items as booked
                $updatedItems = CartItem::where('cartID', $cart->cartID)
                    ->where('isBooked', false)
                    ->update(['isBooked' => true]);
                
                Log::info('Cart items marked as booked:', ['count' => $updatedItems]);
                
                // Update cart as inactive
                $cart->update([
                    'updated_at' => now(),
                    'is_active' => false
                ]);
                
                DB::commit();
                
                Log::info('=== CASH BOOKING COMPLETED SUCCESSFULLY ===');
                
                // Send email
                try {
                    Mail::to($request->email)->send(new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit')));
                    Log::info("Confirmation email sent to {$request->email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email: " . $e->getMessage());
                }
                
                return response()->json([
                    'success' => true,
                    'message' => ($bookingStatus === 'confirmed' 
                        ? 'Booking confirmed successfully!'
                        : 'Booking submitted successfully!'),
                    'booking_reference' => $paymentReference,
                    'booking_id' => $booking->bookingID,
                    'booking_status' => $bookingStatus,
                    'payment_amount' => $paymentAmount,
                    'is_confirmed' => $bookingStatus === 'confirmed'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ NEW: Process GCash payment AFTER validations passed
     */
    public function processGCashPayment(Request $request)
    {
        Log::info('=== GCASH PAYMENT PROCESSING START ===');
        Log::info('Request Data:', $request->all());
        
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to complete payment'
            ], 401);
        }

        try {
            // ✅ STEP 1: Validate that booking data was passed from store() method
            $bookingData = $request->booking_data;
            $paymentData = $request->payment_data;
            
            if (!$bookingData || !$paymentData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment request. Please restart booking process.'
                ], 400);
            }
            
            // ✅ STEP 2: Create PayMongo payment intent
            $amount = floatval($paymentData['payment_amount']);
            
            $paymentIntent = $this->payMongoService->createPaymentIntent(
                $amount,
                "Villa Elena Booking"
            );
            
            Log::info('Payment Intent Created', ['payment_intent_id' => $paymentIntent['data']['id']]);
            
            // ✅ STEP 3: Create payment method
            $paymentMethod = $this->payMongoService->createPaymentMethod();
            
            Log::info('Payment Method Created', ['payment_method_id' => $paymentMethod['data']['id']]);
            
            // ✅ STEP 4: Store booking data and payment intent in session for later use
            session([
                'gcash_booking_data' => $bookingData,
                'gcash_payment_data' => $paymentData,
                'gcash_payment_intent_id' => $paymentIntent['data']['id']
            ]);
            
            // ✅ STEP 5: Set up success/failed URLs with payment intent
            $successUrl = route('customer.payment.success', [
                'payment_intent_id' => $paymentIntent['data']['id']
            ]);
            
            $failedUrl = route('customer.payment.failed');
            
            // ✅ STEP 6: Attach payment method
            $attachedPayment = $this->payMongoService->attachPaymentMethod(
                $paymentIntent['data']['id'],
                $paymentMethod['data']['id'],
                $successUrl
            );
            
            Log::info('Payment Method Attached');
            
            // ✅ STEP 7: Get checkout URL
            $checkoutUrl = $attachedPayment['data']['attributes']['next_action']['redirect']['url'] ?? null;
            
            if (!$checkoutUrl) {
                throw new \Exception('Failed to get GCash checkout URL');
            }
            
            return response()->json([
                'success' => true,
                'checkout_url' => $checkoutUrl,
                'payment_intent_id' => $paymentIntent['data']['id']
            ]);
            
        } catch (\Exception $e) {
            Log::error('GCash Payment Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process GCash payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ COMPLETELY REWRITTEN: Verify and complete GCash payment
     */
    public function verifyGCashPayment(Request $request)
    {
        Log::info('=== GCASH PAYMENT VERIFICATION START ===');
        Log::info('Request params:', $request->all());
        
        try {
            $paymentIntentId = $request->query('payment_intent_id');
            
            if (!$paymentIntentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment verification request'
                ], 400);
            }
            
            // ✅ STEP 1: Retrieve payment intent from PayMongo
            $paymentIntent = $this->payMongoService->retrievePaymentIntent($paymentIntentId);
            
            $status = $paymentIntent['data']['attributes']['status'];
            
            Log::info('Payment Intent Status:', ['status' => $status]);
            
            // ✅ STEP 2: If payment failed, clean up and return error
            if ($status !== 'succeeded') {
                Log::warning('Payment failed or pending', ['status' => $status]);
                
                // Clear session data
                session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Payment was not successful.',
                    'redirect_url' => route('customer.payment.failed'),
                    'status' => $status
                ], 400);
            }
            
            // ✅ STEP 3: Payment succeeded - NOW create booking in database
            Log::info('✅ Payment succeeded - Creating booking in database...');
            
            DB::beginTransaction();
            
            try {
                // Retrieve booking data from session
                $bookingData = session('gcash_booking_data');
                $paymentData = session('gcash_payment_data');
                
                if (!$bookingData || !$paymentData) {
                    throw new \Exception('Session data expired. Please restart booking.');
                }
                
                // ✅ RE-VALIDATE before creating booking (safety check)
                $cart = Cart::with(['items.unit'])->findOrFail($bookingData['cart_id']);
                
                $checkIn = Carbon::parse($bookingData['event_start'])->startOfDay();
                $checkOut = Carbon::parse($bookingData['event_end'])->startOfDay();
                
                // Quick re-validation
                if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                    throw new \Exception('Date conflict detected. Please restart booking.');
                }
                
                // ✅ CREATE BOOKING
                $booking = Booking::create([
                    'cartID' => $bookingData['cart_id'],
                    'numGuests' => $bookingData['num_guests'],
                    'totalPrice' => $bookingData['total_price'],
                    'entranceFeeID' => $bookingData['entrance_fee_id'],
                    'bookingStatus' => $paymentData['booking_status'],
                    'bookingType' => $bookingData['booking_type'],
                    'eventType' => $bookingData['event_type'],
                    'specialRequirements' => $bookingData['special_requirements'],
                    'eventStartTime' => $bookingData['event_start'],
                    'eventEndTime' => $bookingData['event_end'],
                    'gcash_payment_intent_id' => $paymentIntentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('✅ Booking created:', ['booking_id' => $booking->bookingID]);

                // ✅ CREATE PAYMENT RECORD
                $amountPaid = $paymentIntent['data']['attributes']['amount'] / 100;
                $paymentReference = 'GCASH-' . $paymentIntentId;
                
                $payment = Payment::create([
                    'bookingID' => $booking->bookingID,
                    'paymentReference' => $paymentReference,
                    'paymentMethod' => 'gcash',
                    'paymentType' => $paymentData['payment_type'],
                    'amountPaid' => $amountPaid,
                    'remainingBalance' => $paymentData['remaining_balance'],
                    'paymentDate' => now(),
                    'paymentStatus' => 'completed'
                ]);
                
                Log::info('✅ Payment record created:', ['payment_reference' => $paymentReference]);
                
                // ✅ MARK CART ITEMS AS BOOKED
                $updatedItems = CartItem::where('cartID', $cart->cartID)
                    ->where('isBooked', false)
                    ->update(['isBooked' => true]);
                
                Log::info('✅ Cart items marked as booked:', ['count' => $updatedItems]);
                
                // ✅ UPDATE CART AS INACTIVE
                $cart->update([
                    'updated_at' => now(),
                    'is_active' => false
                ]);
                
                // ✅ COMMIT TRANSACTION
                DB::commit();
                
                // ✅ CLEAR SESSION
                session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);
                
                Log::info('=== GCASH BOOKING COMPLETED SUCCESSFULLY ===');
                
                // Send email
                try {
                    Mail::to($cart->user->email)->send(
                        new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit'))
                    );
                    Log::info("Confirmation email sent");
                } catch (\Exception $e) {
                    Log::error("Failed to send email: " . $e->getMessage());
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'booking' => [
                        'bookingID' => $booking->bookingID,
                        'bookingStatus' => $booking->bookingStatus,
                        'totalPrice' => $booking->totalPrice
                    ],
                    'payment' => [
                        'paymentReference' => $payment->paymentReference,
                        'amountPaid' => $payment->amountPaid,
                        'paymentStatus' => $payment->paymentStatus
                    ]
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating booking after payment: ' . $e->getMessage());
                throw $e;
            }
                
        } catch (\Exception $e) {
            Log::error('GCash Verification Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage(),
                'redirect_url' => route('customer.payment.failed')
            ], 500);
        }
    }

    /**
     * ✅ Handle successful GCash payment (blade view)
     */
    public function gcashPaymentSuccess(Request $request)
    {
        Log::info('=== GCASH PAYMENT SUCCESS PAGE ===');
        
        $paymentIntentId = $request->query('payment_intent_id');
        
        return view('customerFolder.payment.gcash-success', [
            'payment_intent_id' => $paymentIntentId
        ]);
    }

    /**
     * ✅ Show failed payment page
     */
    public function gcashPaymentFailed(Request $request)
    {
        Log::warning('=== GCASH PAYMENT FAILED PAGE ===');
        
        return view('customerFolder.payment.gcash-failed');
    }

    /**
     * Check for special event conflicts - STRICT VERSION
     */
    private function hasStrictSpecialEventConflict($checkIn, $checkOut)
    {
        $checkInDate = $checkIn->format('Y-m-d');
        $checkOutDate = $checkOut->format('Y-m-d');

        $hasConflict = DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->where('bookings.bookingType', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->where(function($query) use ($checkInDate, $checkOutDate) {
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
        if ($unit->blockStartDate && $unit->blockEndDate) {
            $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
            $blockEnd = Carbon::parse($unit->blockEndDate)->startOfDay();
            
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
            
            $cart = Cart::with(['items.unit', 'booking'])
                ->where('cartID', $cartId)
                ->where('user_id', $userId)
                ->firstOrFail();
            
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
                'message' => 'No booking found for this cart'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting booking summary: ' . $e->getMessage()
            ], 500);
        }
    }
}