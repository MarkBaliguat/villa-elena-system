<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\EntranceFee;
use App\Models\Unit;
use App\Models\User;
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

    public function bookingsPage()
    {
        return view('customerFolder.booking.my-bookings');
    }

    public function getCustomerBookings(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $userId = Auth::id();
        $status = $request->get('status', 'all');

        $bookings = Booking::with(['cart.items.unit', 'payments', 'entranceFee'])
            ->whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->when($status !== 'all', fn($q) => $q->where('bookingStatus', $status))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $booking->formatted_created_at = $booking->created_at->format('Y-m-d h:i A');
                $booking->formatted_event_start = $booking->eventStartTime
                    ? Carbon::parse($booking->eventStartTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                $booking->formatted_event_end = $booking->eventEndTime
                    ? Carbon::parse($booking->eventEndTime)->format('Y-m-d')
                    : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');
                $booking->total_paid = $booking->payments->where('paymentStatus', 'completed')->sum('amountPaid');
                $booking->accommodations = $booking->cart->items->map(fn($item) => [
                    'name'  => $item->unit->unitName,
                    'type'  => $item->unit->unitType,
                    'price' => $item->subtotalPrice
                ]);
                return $booking;
            });

        return response()->json([
            'success'   => true,
            'bookings'  => $bookings,
            'total'     => $bookings->count(),
            'pending'   => $bookings->where('bookingStatus', 'pending')->count(),
            'confirmed' => $bookings->where('bookingStatus', 'confirmed')->count(),
            'completed' => $bookings->where('bookingStatus', 'completed')->count(),
            'cancelled' => $bookings->where('bookingStatus', 'cancelled')->count()
        ]);
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $userId  = Auth::id();
        $booking = Booking::with(['cart.items.unit', 'payments', 'entranceFee'])
            ->whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->findOrFail($id);

        $eventStart = $booking->eventStartTime
            ? date('Y-m-d', strtotime($booking->eventStartTime))
            : Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
        $eventEnd   = $booking->eventEndTime
            ? date('Y-m-d', strtotime($booking->eventEndTime))
            : Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');

        $totalPaid = $booking->payments->where('paymentStatus', 'completed')->sum('amountPaid');
        $booking->formatted_details = [
            'created_at'        => $booking->created_at->format('Y-m-d h:i A'),
            'event_start'       => $eventStart,
            'event_end'         => $eventEnd,
            'total_price'       => '₱' . number_format($booking->totalPrice, 2),
            'total_paid'        => '₱' . number_format($totalPaid, 2),
            'remaining_balance' => '₱' . number_format($booking->totalPrice - $totalPaid, 2),
        ];

        return response()->json(['success' => true, 'booking' => $booking]);
    }

    public function cancelBooking($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        try {
            $userId  = Auth::id();
            $booking = Booking::whereHas('cart', fn($q) => $q->where('user_id', $userId))->findOrFail($id);

            if (!in_array($booking->bookingStatus, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel booking with current status: ' . $booking->bookingStatus
                ], 400);
            }

            $booking->update([
                'bookingStatus'      => 'cancelled',
                'cancelledAt'        => now(),
                'cancelledBy'        => $userId,
                'cancellationReason' => 'Cancelled by customer'
            ]);

            return response()->json(['success' => true, 'message' => 'Booking cancelled successfully', 'booking' => $booking]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to cancel booking: ' . $e->getMessage()], 500);
        }
    }

    public function preValidateCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        try {
            $userId = Auth::id();
            $cart   = Cart::with(['items' => fn($q) => $q->where('isBooked', false), 'items.unit'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty', 'cart_empty' => true], 400);
            }

            $checkIn  = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();

            if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                return response()->json([
                    'success'                   => false,
                    'message'                   => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors'          => ['There is a special event scheduled during your selected dates.']
                ], 400);
            }

            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    return response()->json([
                        'success'                        => false,
                        'message'                        => 'Selected unit is reserved for special events only',
                        'has_special_event_unit_conflict' => true,
                        'validation_errors'              => ["{$unit->unitName} is permanently reserved for special events only."]
                    ], 400);
                }
            }

            $unavailableItems = [];
            $validationErrors = [];
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd   = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : null;
                    $unavailableItems[] = ['cartItemID' => $item->cartItemID, 'unit' => $unit, 'reason' => 'Unit is blocked', 'conflict_type' => 'unit_blocked'];
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    continue;
                }
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = ['cartItemID' => $item->cartItemID, 'unit' => $unit, 'reason' => 'Unit is already booked', 'conflict_type' => 'already_booked'];
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    continue;
                }
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = ['cartItemID' => $item->cartItemID, 'unit' => $unit, 'reason' => 'Unit is ' . $unit->unitStatus, 'conflict_type' => 'unit_status'];
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json([
                    'success'           => false,
                    'message'           => 'Some items in your cart are no longer available',
                    'unavailable_items' => $unavailableItems,
                    'validation_errors' => $validationErrors,
                    'has_availability_issues' => true
                ], 400);
            }

            $hasCottage = $cart->items->where('unit.unitType', 'cottage')->count() > 0;
            if ($hasCottage) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                if (!$entranceFee) {
                    return response()->json([
                        'success'                => false,
                        'message'                => 'Cottage booking requires active entrance fee',
                        'has_entrance_fee_issue' => true,
                        'validation_errors'      => ['Cottage booking cannot proceed without an active entrance fee.']
                    ], 400);
                }
            }

            if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                return response()->json([
                    'success'                   => false,
                    'message'                   => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors'          => ['There is a special event scheduled during your selected dates.']
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
                    'numGuests'   => $cart->numGuests
                ],
                'total_items' => $cart->items->count()
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error validating cart: ' . $e->getMessage()], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  STORE — main booking entry point (Cash / GCash / Card)
    // ═══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        Log::info('=== CUSTOMER BOOKING START ===');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to complete booking'], 401);
        }

        $validator = Validator::make($request->all(), [
            'full_name'           => 'required|string|max:255',
            'email'               => 'required|email',
            'phone'               => ['required', 'string', 'regex:/^09\d{9}$/'],
            'booking_type'        => 'required|in:day-use,overnight',
            'event_type'          => 'required|string',
            'payment_method'      => 'required|string',
            'payment_amount'      => 'required|numeric|min:0',
            'special_requirements'=> 'nullable|string'
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex'    => 'Phone number must start with 09 and be exactly 11 digits.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            $userId = Auth::id();

            // Update phone
            $user = User::find($userId);
            if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $request->phone)) {
                $user->phoneNumber = $request->phone;
                $user->save();
            }

            // ── Cart retrieval ──
            $cart = Cart::with(['items' => fn($q) => $q->where('isBooked', false), 'items.unit'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->first();

            if (!$cart) {
                $cart = Cart::with(['items' => fn($q) => $q->where('isBooked', false), 'items.unit'])
                    ->where('user_id', $userId)
                    ->whereHas('items', fn($q) => $q->where('isBooked', false))
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No items in cart. Please add items to cart first.'], 400);
            }

            $checkIn  = Carbon::parse($cart->checkInDate)->startOfDay();
            $checkOut = Carbon::parse($cart->checkOutDate)->startOfDay();

            // Validations 1–6 (same as before)
            if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                return response()->json(['success' => false, 'message' => 'Cannot book during special event period.', 'has_special_event_conflict' => true], 400);
            }

            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    return response()->json(['success' => false, 'message' => "{$unit->unitName} is permanently reserved for special events only.", 'has_special_event_unit_conflict' => true], 400);
                }
            }

            $unavailableItems = [];
            $validationErrors = [];
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd   = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : null;
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is blocked from {$blockStart} to {$blockEnd}.";
                    continue;
                }
                if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is already booked for the selected dates.";
                    continue;
                }
                if ($unit->unitStatus !== 'available') {
                    $unavailableItems[] = $unit->unitName;
                    $validationErrors[] = "{$unit->unitName} is currently {$unit->unitStatus}.";
                    continue;
                }
            }

            if (!empty($unavailableItems)) {
                return response()->json(['success' => false, 'message' => 'Some items are no longer available', 'unavailable_items' => $unavailableItems, 'validation_errors' => $validationErrors], 400);
            }

            $hasCottages = $cart->items->contains(fn($item) => $item->unit->unitType === 'cottage');
            $entranceFee = null;
            if ($hasCottages) {
                $entranceFee = EntranceFee::where('isActive', true)->first();
                if (!$entranceFee) {
                    return response()->json(['success' => false, 'message' => 'Cottage booking requires active entrance fee.'], 400);
                }
            }

            if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                return response()->json(['success' => false, 'message' => 'Cannot book during special event period.'], 400);
            }

            // ── Pricing ──
            $subtotal    = 0;
            $totalGuests = $cart->numGuests;
            $daysCount   = max(1, $cart->daysCount);

            foreach ($cart->items as $item) {
                $unit         = $item->unit;
                $itemSubtotal = 0;
                if ($unit->unitType === 'room') {
                    $mult         = $totalGuests == 1 ? 2 : $totalGuests;
                    $itemSubtotal = $unit->unitRatePrice * $mult * $daysCount;
                } elseif ($unit->unitType === 'cottage') {
                    $itemSubtotal = $unit->unitRatePrice;
                    if ($entranceFee) {
                        $itemSubtotal += $entranceFee->amount * $totalGuests;
                    }
                } else {
                    $itemSubtotal = $unit->unitRatePrice * $daysCount;
                }
                $subtotal += $itemSubtotal;
            }

            if ($subtotal <= 0) {
                return response()->json(['success' => false, 'message' => 'Invalid booking calculation.'], 400);
            }

            $totalPrice    = $subtotal;
            $paymentAmount = floatval($request->payment_amount);
            $minPayment    = $totalPrice * 0.5;

            if ($paymentAmount < $minPayment) {
                return response()->json(['success' => false, 'message' => 'Minimum payment is ₱' . number_format($minPayment, 2) . ' (50% downpayment)'], 400);
            }
            if ($paymentAmount > $totalPrice) {
                return response()->json(['success' => false, 'message' => 'Payment cannot exceed total amount of ₱' . number_format($totalPrice, 2)], 400);
            }

            $paymentType      = $paymentAmount >= $totalPrice ? 'full' : 'downpayment';
            $remainingBalance = $paymentAmount >= $totalPrice ? 0 : $totalPrice - $paymentAmount;
            $bookingStatus    = $paymentAmount >= $totalPrice ? 'confirmed' : 'pending';
            $paymentStatus    = 'completed';

            // ── Routing ──
            $method = $request->payment_method;

            // ── GCASH ──
            if ($method === 'gcash') {
                return response()->json([
                    'success'               => true,
                    'message'               => 'Ready for GCash payment',
                    'payment_method'        => 'gcash',
                    'requires_payment_first'=> true,
                    'booking_data' => [
                        'cart_id'           => $cart->cartID,
                        'num_guests'        => $totalGuests,
                        'total_price'       => $totalPrice,
                        'entrance_fee_id'   => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                        'booking_type'      => $request->booking_type,
                        'event_type'        => $request->event_type,
                        'special_requirements' => $request->special_requirements,
                        'event_start'       => $cart->checkInDate,
                        'event_end'         => $cart->checkOutDate,
                        'phone'             => $request->phone,
                    ],
                    'payment_data' => [
                        'payment_amount'    => $paymentAmount,
                        'payment_type'      => $paymentType,
                        'remaining_balance' => $remainingBalance,
                        'booking_status'    => $bookingStatus,
                        'payment_status'    => $paymentStatus
                    ]
                ]);
            }

            // ── CARD ──
            if ($method === 'card') {
                return response()->json([
                    'success'               => true,
                    'message'               => 'Ready for Card payment',
                    'payment_method'        => 'card',
                    'requires_payment_first'=> true,
                    'booking_data' => [
                        'cart_id'           => $cart->cartID,
                        'num_guests'        => $totalGuests,
                        'total_price'       => $totalPrice,
                        'entrance_fee_id'   => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                        'booking_type'      => $request->booking_type,
                        'event_type'        => $request->event_type,
                        'special_requirements' => $request->special_requirements,
                        'event_start'       => $cart->checkInDate,
                        'event_end'         => $cart->checkOutDate,
                        'phone'             => $request->phone,
                        'email'             => $request->email,
                        'full_name'         => $request->full_name,
                    ],
                    'payment_data' => [
                        'payment_amount'    => $paymentAmount,
                        'payment_type'      => $paymentType,
                        'remaining_balance' => $remainingBalance,
                        'booking_status'    => $bookingStatus,
                        'payment_status'    => $paymentStatus
                    ]
                ]);
            }

            // ── CASH ──
            DB::beginTransaction();
            try {
                $booking = Booking::create([
                    'cartID'              => $cart->cartID,
                    'numGuests'           => $totalGuests,
                    'totalPrice'          => $totalPrice,
                    'entranceFeeID'       => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                    'bookingStatus'       => $bookingStatus,
                    'bookingType'         => $request->booking_type,
                    'eventType'           => $request->event_type,
                    'specialRequirements' => $request->special_requirements,
                    'eventStartTime'      => $cart->checkInDate,
                    'eventEndTime'        => $cart->checkOutDate,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                $paymentReference = 'VLE' . time() . $booking->bookingID;
                Payment::create([
                    'bookingID'        => $booking->bookingID,
                    'paymentReference' => $paymentReference,
                    'paymentMethod'    => 'cash',
                    'paymentType'      => $paymentType,
                    'amountPaid'       => $paymentAmount,
                    'remainingBalance' => $remainingBalance,
                    'paymentDate'      => now(),
                    'paymentStatus'    => $paymentStatus,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                CartItem::where('cartID', $cart->cartID)->where('isBooked', false)->update(['isBooked' => true]);
                $cart->update(['is_active' => false, 'updated_at' => now()]);
                DB::commit();

                try {
                    Mail::to($request->email)->send(new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit')));
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }

                return response()->json([
                    'success'           => true,
                    'message'           => $bookingStatus === 'confirmed' ? 'Booking confirmed successfully!' : 'Booking submitted successfully!',
                    'booking_reference' => $paymentReference,
                    'booking_id'        => $booking->bookingID,
                    'booking_status'    => $bookingStatus,
                    'payment_amount'    => $paymentAmount,
                    'is_confirmed'      => $bookingStatus === 'confirmed'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to complete booking: ' . $e->getMessage()], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  PROCESS CARD PAYMENT — create intent + attach method
    // ═══════════════════════════════════════════════════════
    public function processCardPayment(Request $request)
    {
        Log::info('=== CARD PAYMENT PROCESSING START ===');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to complete payment'], 401);
        }

        $validator = Validator::make($request->all(), [
            'booking_data'           => 'required|array',
            'payment_data'           => 'required|array',
            'card_number'            => 'required|string|min:13|max:19',
            'card_exp_month'         => 'required|string|size:2',
            'card_exp_year'          => 'required|string|size:4',
            'card_cvc'               => 'required|string|min:3|max:4',
            'card_name'              => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid card details.', 'errors' => $validator->errors()], 422);
        }

        try {
            $bookingData = $request->booking_data;
            $paymentData = $request->payment_data;

            // Update phone if provided
            if (isset($bookingData['phone'])) {
                $user = User::find(Auth::id());
                if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                    $user->phoneNumber = $bookingData['phone'];
                    $user->save();
                }
            }

            // STEP 1: Create card payment method via PayMongo (server-side)
            $cardDetails = [
                'card_number' => preg_replace('/\s+/', '', $request->card_number),
                'exp_month'   => $request->card_exp_month,
                'exp_year'    => $request->card_exp_year,
                'cvc'         => $request->card_cvc,
            ];

            $billingDetails = [
                'name'  => $request->card_name,
                'email' => $bookingData['email'] ?? Auth::user()->email,
                'phone' => $bookingData['phone'] ?? null,
            ];

            $paymentMethod = $this->payMongoService->createCardPaymentMethod($cardDetails, $billingDetails);
            Log::info('Card Payment Method Created', ['method_id' => $paymentMethod['data']['id']]);

            // STEP 2: Create payment intent for card
            $amount        = floatval($paymentData['payment_amount']);
            $paymentIntent = $this->payMongoService->createCardPaymentIntent($amount, 'Villa Elena Booking');
            Log::info('Card Payment Intent Created', ['intent_id' => $paymentIntent['data']['id']]);

            // STEP 3: Store in session
            session([
                'card_booking_data'      => $bookingData,
                'card_payment_data'      => $paymentData,
                'card_payment_intent_id' => $paymentIntent['data']['id']
            ]);

            // STEP 4: Build return URL
            $returnUrl = route('customer.card.payment.success', [
                'payment_intent_id' => $paymentIntent['data']['id']
            ]);

            // STEP 5: Attach payment method — this triggers 3DS if needed
            $attachedPayment = $this->payMongoService->attachPaymentMethod(
                $paymentIntent['data']['id'],
                $paymentMethod['data']['id'],
                $returnUrl
            );

            $intentStatus = $attachedPayment['data']['attributes']['status'] ?? 'unknown';
            Log::info('Card Payment Attach Status', ['status' => $intentStatus]);

            // STEP 6: Check if 3DS redirect is needed
            $nextAction  = $attachedPayment['data']['attributes']['next_action'] ?? null;
            $redirectUrl = $nextAction['redirect']['url'] ?? null;

            if ($redirectUrl) {
                // 3DS required — redirect customer to bank
                Log::info('3DS required, redirecting to bank', ['url' => $redirectUrl]);
                return response()->json([
                    'success'            => true,
                    'requires_3ds'       => true,
                    'redirect_url'       => $redirectUrl,
                    'payment_intent_id'  => $paymentIntent['data']['id']
                ]);
            }

            // STEP 7: No 3DS needed — payment succeeded immediately
            if ($intentStatus === 'succeeded') {
                $result = $this->createBookingAfterCardPayment(
                    $paymentIntent['data']['id'],
                    $attachedPayment['data']['attributes']['amount'] / 100
                );
                return response()->json($result);
            }

            throw new \Exception('Card payment did not complete. Status: ' . $intentStatus);

        } catch (\Exception $e) {
            Log::error('Card Payment Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  VERIFY CARD PAYMENT — called after 3DS redirect
    // ═══════════════════════════════════════════════════════
    public function verifyCardPayment(Request $request)
    {
        Log::info('=== CARD PAYMENT VERIFICATION START ===');

        try {
            $paymentIntentId = $request->query('payment_intent_id');

            if (!$paymentIntentId) {
                return response()->json(['success' => false, 'message' => 'Invalid payment verification request'], 400);
            }

            $paymentIntent = $this->payMongoService->retrievePaymentIntent($paymentIntentId);
            $status        = $paymentIntent['data']['attributes']['status'];
            $amountPaid    = $paymentIntent['data']['attributes']['amount'] / 100;

            Log::info('Card Payment Intent Status', ['status' => $status]);

            if ($status !== 'succeeded') {
                session()->forget(['card_booking_data', 'card_payment_data', 'card_payment_intent_id']);
                return response()->json([
                    'success'      => false,
                    'message'      => 'Card payment was not successful.',
                    'redirect_url' => route('customer.payment.failed'),
                    'status'       => $status
                ], 400);
            }

            $result = $this->createBookingAfterCardPayment($paymentIntentId, $amountPaid);
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Card Verification Error: ' . $e->getMessage());
            return response()->json([
                'success'      => false,
                'message'      => 'Error verifying payment: ' . $e->getMessage(),
                'redirect_url' => route('customer.payment.failed')
            ], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  HELPER — Create booking after card payment succeeded
    // ═══════════════════════════════════════════════════════
    private function createBookingAfterCardPayment(string $paymentIntentId, float $amountPaid): array
    {
        $bookingData = session('card_booking_data');
        $paymentData = session('card_payment_data');

        if (!$bookingData || !$paymentData) {
            throw new \Exception('Session data expired. Please restart booking.');
        }

        $cart     = Cart::with(['items.unit', 'user'])->findOrFail($bookingData['cart_id']);
        $checkIn  = Carbon::parse($bookingData['event_start'])->startOfDay();
        $checkOut = Carbon::parse($bookingData['event_end'])->startOfDay();

        if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
            throw new \Exception('Date conflict detected. Please restart booking.');
        }

        // Update phone
        if (isset($bookingData['phone'])) {
            $user = User::find($cart->user_id);
            if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                $user->phoneNumber = $bookingData['phone'];
                $user->save();
            }
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'cartID'              => $bookingData['cart_id'],
                'numGuests'           => $bookingData['num_guests'],
                'totalPrice'          => $bookingData['total_price'],
                'entranceFeeID'       => $bookingData['entrance_fee_id'],
                'bookingStatus'       => $paymentData['booking_status'],
                'bookingType'         => $bookingData['booking_type'],
                'eventType'           => $bookingData['event_type'],
                'specialRequirements' => $bookingData['special_requirements'],
                'gcash_payment_intent_id' => $paymentIntentId, // reusing column for card too
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $paymentReference = 'CARD-' . $paymentIntentId;
            $payment = Payment::create([
                'bookingID'        => $booking->bookingID,
                'paymentReference' => $paymentReference,
                'paymentMethod'    => 'card',
                'paymentType'      => $paymentData['payment_type'],
                'amountPaid'       => $amountPaid,
                'remainingBalance' => $paymentData['remaining_balance'],
                'paymentDate'      => now(),
                'paymentStatus'    => 'completed'
            ]);

            CartItem::where('cartID', $cart->cartID)->where('isBooked', false)->update(['isBooked' => true]);
            $cart->update(['is_active' => false, 'updated_at' => now()]);

            DB::commit();

            session()->forget(['card_booking_data', 'card_payment_data', 'card_payment_intent_id']);

            Log::info('=== CARD BOOKING COMPLETED SUCCESSFULLY ===');

            try {
                Mail::to($cart->user->email)->send(
                    new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit'))
                );
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Card payment verified and booking confirmed!',
                'booking' => [
                    'bookingID'     => $booking->bookingID,
                    'bookingStatus' => $booking->bookingStatus,
                    'totalPrice'    => $booking->totalPrice
                ],
                'payment' => [
                    'paymentReference' => $payment->paymentReference,
                    'amountPaid'       => $payment->amountPaid,
                    'paymentStatus'    => $payment->paymentStatus
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ═══════════════════════════════════════════════════════
    //  CARD SUCCESS PAGE — after 3DS redirect
    // ═══════════════════════════════════════════════════════
    public function cardPaymentSuccess(Request $request)
    {
        Log::info('=== CARD PAYMENT SUCCESS PAGE ===');
        $paymentIntentId = $request->query('payment_intent_id');
        return view('customerFolder.payment.card-success', [
            'payment_intent_id' => $paymentIntentId
        ]);
    }

    // ═══════════════════════════════════════════════════════
    //  GCASH methods (unchanged)
    // ═══════════════════════════════════════════════════════
    public function processGCashPayment(Request $request)
    {
        Log::info('=== GCASH PAYMENT PROCESSING START ===');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to complete payment'], 401);
        }

        try {
            $bookingData = $request->booking_data;
            $paymentData = $request->payment_data;

            if (!$bookingData || !$paymentData) {
                return response()->json(['success' => false, 'message' => 'Invalid payment request. Please restart booking process.'], 400);
            }

            if (isset($bookingData['phone'])) {
                $user = User::find(Auth::id());
                if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                    $user->phoneNumber = $bookingData['phone'];
                    $user->save();
                }
            }

            $amount        = floatval($paymentData['payment_amount']);
            $paymentIntent = $this->payMongoService->createPaymentIntent($amount, 'Villa Elena Booking');
            $paymentMethod = $this->payMongoService->createPaymentMethod();

            session([
                'gcash_booking_data'      => $bookingData,
                'gcash_payment_data'      => $paymentData,
                'gcash_payment_intent_id' => $paymentIntent['data']['id']
            ]);

            $successUrl = route('customer.payment.success', ['payment_intent_id' => $paymentIntent['data']['id']]);

            $attachedPayment = $this->payMongoService->attachPaymentMethod(
                $paymentIntent['data']['id'],
                $paymentMethod['data']['id'],
                $successUrl
            );

            $checkoutUrl = $attachedPayment['data']['attributes']['next_action']['redirect']['url'] ?? null;

            if (!$checkoutUrl) {
                throw new \Exception('Failed to get GCash checkout URL');
            }

            return response()->json([
                'success'           => true,
                'checkout_url'      => $checkoutUrl,
                'payment_intent_id' => $paymentIntent['data']['id']
            ]);

        } catch (\Exception $e) {
            Log::error('GCash Payment Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to process GCash payment: ' . $e->getMessage()], 500);
        }
    }

    public function verifyGCashPayment(Request $request)
    {
        Log::info('=== GCASH PAYMENT VERIFICATION START ===');

        try {
            $paymentIntentId = $request->query('payment_intent_id');

            if (!$paymentIntentId) {
                return response()->json(['success' => false, 'message' => 'Invalid payment verification request'], 400);
            }

            $paymentIntent = $this->payMongoService->retrievePaymentIntent($paymentIntentId);
            $status        = $paymentIntent['data']['attributes']['status'];

            if ($status !== 'succeeded') {
                session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);
                return response()->json([
                    'success'      => false,
                    'message'      => 'Payment was not successful.',
                    'redirect_url' => route('customer.payment.failed'),
                    'status'       => $status
                ], 400);
            }

            DB::beginTransaction();
            try {
                $bookingData = session('gcash_booking_data');
                $paymentData = session('gcash_payment_data');

                if (!$bookingData || !$paymentData) {
                    throw new \Exception('Session data expired. Please restart booking.');
                }

                $cart     = Cart::with(['items.unit', 'user'])->findOrFail($bookingData['cart_id']);
                $checkIn  = Carbon::parse($bookingData['event_start'])->startOfDay();
                $checkOut = Carbon::parse($bookingData['event_end'])->startOfDay();

                if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                    throw new \Exception('Date conflict detected. Please restart booking.');
                }

                if (isset($bookingData['phone'])) {
                    $user = User::find($cart->user_id);
                    if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                        $user->phoneNumber = $bookingData['phone'];
                        $user->save();
                    }
                }

                $booking = Booking::create([
                    'cartID'                  => $bookingData['cart_id'],
                    'numGuests'               => $bookingData['num_guests'],
                    'totalPrice'              => $bookingData['total_price'],
                    'entranceFeeID'           => $bookingData['entrance_fee_id'],
                    'bookingStatus'           => $paymentData['booking_status'],
                    'bookingType'             => $bookingData['booking_type'],
                    'eventType'               => $bookingData['event_type'],
                    'specialRequirements'     => $bookingData['special_requirements'],
                    'gcash_payment_intent_id' => $paymentIntentId,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);

                $amountPaid       = $paymentIntent['data']['attributes']['amount'] / 100;
                $paymentReference = 'GCASH-' . $paymentIntentId;

                $payment = Payment::create([
                    'bookingID'        => $booking->bookingID,
                    'paymentReference' => $paymentReference,
                    'paymentMethod'    => 'gcash',
                    'paymentType'      => $paymentData['payment_type'],
                    'amountPaid'       => $amountPaid,
                    'remainingBalance' => $paymentData['remaining_balance'],
                    'paymentDate'      => now(),
                    'paymentStatus'    => 'completed'
                ]);

                CartItem::where('cartID', $cart->cartID)->where('isBooked', false)->update(['isBooked' => true]);
                $cart->update(['is_active' => false, 'updated_at' => now()]);

                DB::commit();
                session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);

                try {
                    Mail::to($cart->user->email)->send(
                        new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit'))
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'booking' => ['bookingID' => $booking->bookingID, 'bookingStatus' => $booking->bookingStatus, 'totalPrice' => $booking->totalPrice],
                    'payment' => ['paymentReference' => $payment->paymentReference, 'amountPaid' => $payment->amountPaid, 'paymentStatus' => $payment->paymentStatus]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('GCash Verification Error: ' . $e->getMessage());
            return response()->json([
                'success'      => false,
                'message'      => 'Error verifying payment: ' . $e->getMessage(),
                'redirect_url' => route('customer.payment.failed')
            ], 500);
        }
    }

    public function gcashPaymentSuccess(Request $request)
    {
        return view('customerFolder.payment.gcash-success', ['payment_intent_id' => $request->query('payment_intent_id')]);
    }

    public function gcashPaymentFailed(Request $request)
    {
        return view('customerFolder.payment.gcash-failed');
    }

    // ═══════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════
    private function hasStrictSpecialEventConflict($checkIn, $checkOut)
    {
        return DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->where('bookings.bookingType', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereDate('carts.checkInDate', '>=', $checkIn->format('Y-m-d'))
                      ->whereDate('carts.checkInDate', '<=', $checkOut->format('Y-m-d'));
            })
            ->exists();
    }

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

    private function isUnitAlreadyBooked($unitId, $checkIn, $checkOut, $excludeCartId = null)
    {
        $existingBookings = DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->join('cart_items', 'carts.cartID', '=', 'cart_items.cartID')
            ->where('cart_items.unitID', $unitId)
            ->where('cart_items.isBooked', true)
            ->where('bookings.bookingType', '!=', 'special-event')
            ->whereIn('bookings.bookingStatus', ['pending', 'confirmed', 'approved'])
            ->when($excludeCartId, fn($q) => $q->where('carts.cartID', '!=', $excludeCartId))
            ->select('carts.checkInDate', 'carts.checkOutDate')
            ->get();

        foreach ($existingBookings as $booking) {
            $existIn  = Carbon::parse($booking->checkInDate)->startOfDay();
            $existOut = Carbon::parse($booking->checkOutDate)->startOfDay();
            if (
                $checkIn->between($existIn, $existOut, true) ||
                $checkOut->between($existIn, $existOut, true) ||
                $existIn->between($checkIn, $checkOut, true) ||
                $existOut->between($checkIn, $checkOut, true) ||
                ($checkIn->lte($existIn) && $checkOut->gte($existOut))
            ) {
                return true;
            }
        }
        return false;
    }

    public function getBookingSummary($cartId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        try {
            $userId = Auth::id();
            $cart   = Cart::with(['items.unit', 'booking'])
                ->where('cartID', $cartId)
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($cart->booking) {
                $booking = $cart->booking;
                return response()->json([
                    'success'     => true,
                    'has_booking' => true,
                    'booking'     => ['id' => $booking->bookingID, 'status' => $booking->bookingStatus, 'total_price' => $booking->totalPrice, 'created_at' => $booking->created_at->format('Y-m-d H:i:s')],
                    'cart'        => ['id' => $cart->cartID, 'check_in' => $cart->checkInDate, 'check_out' => $cart->checkOutDate, 'guests' => $cart->numGuests, 'has_booked_items' => $cart->items->where('isBooked', true)->count() > 0]
                ]);
            }

            return response()->json(['success' => true, 'has_booking' => false, 'message' => 'No booking found for this cart']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error getting booking summary: ' . $e->getMessage()], 500);
        }
    }
}