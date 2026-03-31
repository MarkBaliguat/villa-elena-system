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
                    'name'      => $item->unit->unitName,
                    'type'      => $item->unit->unitType,
                    'price'     => $item->subtotalPrice,
                    'numGuests' => $item->numGuests,
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
                    'success'                    => false,
                    'message'                    => 'Cannot book during special event period',
                    'has_special_event_conflict' => true,
                    'validation_errors'          => ['There is a special event scheduled during your selected dates.']
                ], 400);
            }

            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                    return response()->json([
                        'success'                         => false,
                        'message'                         => 'Selected unit is reserved for special events only',
                        'has_special_event_unit_conflict' => true,
                        'validation_errors'               => ["{$unit->unitName} is permanently reserved for special events only."]
                    ], 400);
                }
            }

            $unavailableItems = [];
            $validationErrors = [];
            foreach ($cart->items as $item) {
                $unit = $item->unit;
                if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                    $blockStart         = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd           = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : null;
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
                    'success'                 => false,
                    'message'                 => 'Some items in your cart are no longer available',
                    'unavailable_items'       => $unavailableItems,
                    'validation_errors'       => $validationErrors,
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

            return response()->json([
                'success' => true,
                'message' => 'All items in cart are available for booking',
                'cart'    => [
                    'cartID'       => $cart->cartID,
                    'checkInDate'  => $cart->checkInDate,
                    'checkOutDate' => $cart->checkOutDate,
                    'daysCount'    => $cart->daysCount,
                ],
                'total_items' => $cart->items->count()
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error validating cart: ' . $e->getMessage()], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  STORE — main booking entry point (GCash / Card)
    // ═══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        Log::info('=== CUSTOMER BOOKING START ===');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to complete booking'], 401);
        }

        $validator = Validator::make($request->all(), [
            'full_name'            => 'required|string|max:255',
            'email'                => 'required|email',
            'phone'                => ['required', 'string', 'regex:/^09\d{9}$/'],
            'booking_type'         => 'required|in:day-use,overnight',
            'event_type'           => 'required|string',
            'payment_method'       => 'required|string',
            'payment_amount'       => 'required|numeric|min:0',
            'special_requirements' => 'nullable|string'
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex'    => 'Phone number must start with 09 and be exactly 11 digits.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            $userId = Auth::id();

            $user = User::find($userId);
            if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $request->phone)) {
                $user->phoneNumber = $request->phone;
                $user->save();
            }

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
                    $blockStart         = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd           = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : null;
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

            // ── Pricing — numGuests is now per cart item ──
            $subtotal  = 0;
            $daysCount = max(1, $cart->daysCount);

            foreach ($cart->items as $item) {
                $unit        = $item->unit;
                $itemGuests  = $item->numGuests; // ← per-item guest count
                $itemSubtotal = 0;

                if ($unit->unitType === 'room') {
                    $mult         = $itemGuests == 1 ? 2 : $itemGuests;
                    $itemSubtotal = $unit->unitRatePrice * $mult * $daysCount;
                } elseif ($unit->unitType === 'cottage') {
                    $itemSubtotal = $unit->unitRatePrice;
                    if ($entranceFee) {
                        $itemSubtotal += $entranceFee->amount * $itemGuests;
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

            $method = $request->payment_method;

            // ── GCASH ──
            if ($method === 'gcash') {
                return response()->json([
                    'success'                => true,
                    'message'                => 'Ready for GCash payment',
                    'payment_method'         => 'gcash',
                    'requires_payment_first' => true,
                    'booking_data'           => [
                        'cart_id'              => $cart->cartID,
                        'total_price'          => $totalPrice,
                        'entrance_fee_id'      => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                        'booking_type'         => $request->booking_type,
                        'event_type'           => $request->event_type,
                        'special_requirements' => $request->special_requirements,
                        'event_start'          => $cart->checkInDate,
                        'event_end'            => $cart->checkOutDate,
                        'phone'                => $request->phone,
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
                    'success'                => true,
                    'message'                => 'Ready for Card payment',
                    'payment_method'         => 'card',
                    'requires_payment_first' => true,
                    'booking_data'           => [
                        'cart_id'              => $cart->cartID,
                        'total_price'          => $totalPrice,
                        'entrance_fee_id'      => $hasCottages && $entranceFee ? $entranceFee->entranceFeeID : null,
                        'booking_type'         => $request->booking_type,
                        'event_type'           => $request->event_type,
                        'special_requirements' => $request->special_requirements,
                        'event_start'          => $cart->checkInDate,
                        'event_end'            => $cart->checkOutDate,
                        'phone'                => $request->phone,
                        'email'                => $request->email,
                        'full_name'            => $request->full_name,
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

            return response()->json(['success' => false, 'message' => 'Invalid payment method.'], 400);

        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to complete booking: ' . $e->getMessage()], 500);
        }
    }

    // ═══════════════════════════════════════════════════════
    //  PROCESS CARD PAYMENT
    // ═══════════════════════════════════════════════════════
    public function processCardPayment(Request $request)
    {
        Log::info('=== CARD PAYMENT PROCESSING START ===');

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to complete payment'], 401);
        }

        $validator = Validator::make($request->all(), [
            'booking_data'      => 'required|array',
            'payment_data'      => 'required|array',
            'payment_method_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid payment request.', 'errors' => $validator->errors()], 422);
        }

        try {
            $bookingData     = $request->booking_data;
            $paymentData     = $request->payment_data;
            $paymentMethodId = $request->payment_method_id;

            if (isset($bookingData['phone'])) {
                $user = User::find(Auth::id());
                if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                    $user->phoneNumber = $bookingData['phone'];
                    $user->save();
                }
            }

            $amount = floatval($paymentData['payment_amount']);

            $paymentIntent = $this->payMongoService->createCardPaymentIntent($amount, 'Villa Elena Booking');
            Log::info('Card Payment Intent Created', ['intent_id' => $paymentIntent['data']['id']]);

            session([
                'card_booking_data'      => $bookingData,
                'card_payment_data'      => $paymentData,
                'card_payment_intent_id' => $paymentIntent['data']['id']
            ]);

            $returnUrl = route('customer.card.payment.success', [
                'payment_intent_id' => $paymentIntent['data']['id']
            ]);

            $attachedPayment = $this->payMongoService->attachPaymentMethod(
                $paymentIntent['data']['id'],
                $paymentMethodId,
                $returnUrl
            );

            $intentStatus = $attachedPayment['data']['attributes']['status'] ?? 'unknown';
            Log::info('Card Payment Attach Status', ['status' => $intentStatus]);

            $nextAction  = $attachedPayment['data']['attributes']['next_action'] ?? null;
            $redirectUrl = $nextAction['redirect']['url'] ?? null;

            if ($redirectUrl) {
                Log::info('3DS required, redirecting to bank', ['url' => $redirectUrl]);
                return response()->json([
                    'success'           => true,
                    'requires_3ds'      => true,
                    'redirect_url'      => $redirectUrl,
                    'payment_intent_id' => $paymentIntent['data']['id']
                ]);
            }

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
    //  ✅ Re-checks unit availability AFTER payment to guard
    //  against race conditions during 3DS redirect gap
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

        // ── Re-check 1: Special event conflict ──
        if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
            Log::error('=== CARD: SPECIAL EVENT CONFLICT AFTER CHARGE ===', [
                'payment_intent_id' => $paymentIntentId,
                'amount_paid'       => $amountPaid,
                'cart_id'           => $cart->cartID,
            ]);
            session()->forget(['card_booking_data', 'card_payment_data', 'card_payment_intent_id']);
            throw new \Exception(
                'Your payment was received but the dates are no longer available due to a special event. ' .
                'Please contact us at 0917-301-0790 for an immediate refund.'
            );
        }

        // ── Re-check 2: Unit availability (race condition guard) ──
        $unavailableUnits = [];
        foreach ($cart->items->where('isBooked', false) as $item) {
            $unit = $item->unit;

            if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                $unavailableUnits[] = "{$unit->unitName} is reserved for special events only.";
                continue;
            }

            if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                $blockStart         = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : 'N/A';
                $blockEnd           = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : 'N/A';
                $unavailableUnits[] = "{$unit->unitName} was blocked ({$blockStart} – {$blockEnd}) while your payment was processing.";
                continue;
            }

            if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                $unavailableUnits[] = "{$unit->unitName} was just booked by another guest while your payment was processing.";
                continue;
            }

            if ($unit->unitStatus !== 'available') {
                $unavailableUnits[] = "{$unit->unitName} is no longer available (status: {$unit->unitStatus}).";
                continue;
            }
        }

        if (!empty($unavailableUnits)) {
            Log::error('=== CARD: UNIT CONFLICT AFTER CHARGE — MANUAL REFUND NEEDED ===', [
                'payment_intent_id' => $paymentIntentId,
                'amount_paid'       => $amountPaid,
                'conflicts'         => $unavailableUnits,
                'cart_id'           => $cart->cartID,
                'user_id'           => $cart->user_id,
                'user_email'        => $cart->user->email ?? 'unknown',
            ]);
            session()->forget(['card_booking_data', 'card_payment_data', 'card_payment_intent_id']);
            throw new \Exception(
                'Your payment was received but the following units are no longer available: ' .
                implode(' | ', $unavailableUnits) .
                ' Please contact us at 0917-301-0790 for an immediate refund. Reference: ' . $paymentIntentId
            );
        }

        if (isset($bookingData['phone'])) {
            $user = User::find($cart->user_id);
            if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                $user->phoneNumber = $bookingData['phone'];
                $user->save();
            }
        }

        // ── Recompute total from cart items (numGuests per item) ──
        $entranceFee = $bookingData['entrance_fee_id']
            ? EntranceFee::find($bookingData['entrance_fee_id'])
            : null;

        $daysCount = max(1, $cart->daysCount);
        $subtotal  = 0;

        foreach ($cart->items->where('isBooked', false) as $item) {
            $unit       = $item->unit;
            $itemGuests = $item->numGuests;

            if ($unit->unitType === 'room') {
                $mult      = $itemGuests == 1 ? 2 : $itemGuests;
                $subtotal += $unit->unitRatePrice * $mult * $daysCount;
            } elseif ($unit->unitType === 'cottage') {
                $subtotal += $unit->unitRatePrice;
                if ($entranceFee) {
                    $subtotal += $entranceFee->amount * $itemGuests;
                }
            } else {
                $subtotal += $unit->unitRatePrice * $daysCount;
            }
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'cartID'                  => $bookingData['cart_id'],
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

            Log::info('=== CARD BOOKING COMPLETED SUCCESSFULLY ===', [
                'booking_id'        => $booking->bookingID,
                'payment_reference' => $paymentReference,
            ]);

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
    //  GCASH — Process Payment
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

    // ═══════════════════════════════════════════════════════
    //  GCASH — Verify Payment
    //  ✅ Re-checks unit availability AFTER payment to guard
    //  against race conditions during GCash redirect gap
    // ═══════════════════════════════════════════════════════
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

                // ── Re-check 1: Special event conflict ──
                if ($this->hasStrictSpecialEventConflict($checkIn, $checkOut)) {
                    Log::error('=== GCASH: SPECIAL EVENT CONFLICT AFTER CHARGE ===', [
                        'payment_intent_id' => $paymentIntentId,
                        'amount_paid'       => $paymentIntent['data']['attributes']['amount'] / 100,
                        'cart_id'           => $cart->cartID,
                    ]);
                    DB::rollBack();
                    session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);
                    throw new \Exception(
                        'Your payment was received but the dates are no longer available due to a special event. ' .
                        'Please contact us at 0917-301-0790 for an immediate refund.'
                    );
                }

                // ── Re-check 2: Unit availability (race condition guard) ──
                $unavailableUnits = [];
                foreach ($cart->items->where('isBooked', false) as $item) {
                    $unit = $item->unit;

                    if ($unit->for_special_events && $unit->unitStatus === 'blocked') {
                        $unavailableUnits[] = "{$unit->unitName} is reserved for special events only.";
                        continue;
                    }

                    if ($this->isUnitBlocked($unit, $checkIn, $checkOut)) {
                        $blockStart         = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : 'N/A';
                        $blockEnd           = $unit->blockEndDate   ? Carbon::parse($unit->blockEndDate)->format('M d, Y')   : 'N/A';
                        $unavailableUnits[] = "{$unit->unitName} was blocked ({$blockStart} – {$blockEnd}) while your payment was processing.";
                        continue;
                    }

                    if ($this->isUnitAlreadyBooked($unit->unitID, $checkIn, $checkOut, $cart->cartID)) {
                        $unavailableUnits[] = "{$unit->unitName} was just booked by another guest while your payment was processing.";
                        continue;
                    }

                    if ($unit->unitStatus !== 'available') {
                        $unavailableUnits[] = "{$unit->unitName} is no longer available (status: {$unit->unitStatus}).";
                        continue;
                    }
                }

                if (!empty($unavailableUnits)) {
                    Log::error('=== GCASH: UNIT CONFLICT AFTER CHARGE — MANUAL REFUND NEEDED ===', [
                        'payment_intent_id' => $paymentIntentId,
                        'amount_paid'       => $paymentIntent['data']['attributes']['amount'] / 100,
                        'conflicts'         => $unavailableUnits,
                        'cart_id'           => $cart->cartID,
                        'user_id'           => $cart->user_id,
                        'user_email'        => $cart->user->email ?? 'unknown',
                    ]);
                    DB::rollBack();
                    session()->forget(['gcash_booking_data', 'gcash_payment_data', 'gcash_payment_intent_id']);
                    throw new \Exception(
                        'Your payment was received but the following units are no longer available: ' .
                        implode(' | ', $unavailableUnits) .
                        ' Please contact us at 0917-301-0790 for an immediate refund. Reference: ' . $paymentIntentId
                    );
                }

                if (isset($bookingData['phone'])) {
                    $user = User::find($cart->user_id);
                    if ($user && (empty($user->phoneNumber) || $user->phoneNumber !== $bookingData['phone'])) {
                        $user->phoneNumber = $bookingData['phone'];
                        $user->save();
                    }
                }

                $amountPaid       = $paymentIntent['data']['attributes']['amount'] / 100;
                $paymentReference = 'GCASH-' . $paymentIntentId;

                $booking = Booking::create([
                    'cartID'                  => $bookingData['cart_id'],
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

                Log::info('=== GCASH BOOKING COMPLETED SUCCESSFULLY ===', [
                    'booking_id'        => $booking->bookingID,
                    'payment_reference' => $paymentReference,
                ]);

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
                    'booking'     => [
                        'id'          => $booking->bookingID,
                        'status'      => $booking->bookingStatus,
                        'total_price' => $booking->totalPrice,
                        'created_at'  => $booking->created_at->format('Y-m-d H:i:s')
                    ],
                    'cart' => [
                        'id'               => $cart->cartID,
                        'check_in'         => $cart->checkInDate,
                        'check_out'        => $cart->checkOutDate,
                        'has_booked_items' => $cart->items->where('isBooked', true)->count() > 0
                    ]
                ]);
            }

            return response()->json(['success' => true, 'has_booking' => false, 'message' => 'No booking found for this cart']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error getting booking summary: ' . $e->getMessage()], 500);
        }
    }
}