<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Unit;
use App\Models\User;
use App\Models\Payment;
use App\Models\EntranceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\BookingConfirmationEmail;
use App\Mail\BookingCompletedEmail;
use App\Mail\BookingCancelledEmail; 
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Booking::with(['cart.user', 'cart.cartItems.unit', 'payments'])
                ->whereIn('bookingStatus', ['pending', 'confirmed'])
                ->where('eventType', 'normal-booking')
                ->where('bookingType', '!=', 'special-event')
                ->orderBy('created_at', 'desc');

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('bookingStatus', $request->status);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->whereHas('cart.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phoneNumber', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 10);
            $bookings = $query->paginate($perPage);

            $formattedBookings = $bookings->map(function($booking) {
                $user = $booking->cart->user;
                $units = $booking->cart->cartItems->map(function($item) {
                    return $item->unit->unitName;
                })->join(', ');

                $paymentSummary = $this->calculatePaymentSummary($booking);
                $latestPayment = $booking->payments->where('paymentStatus', 'completed')->sortByDesc('paymentDate')->first();

                // ✅ STANDARDIZED DATE FORMAT
                $checkInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                $checkOutDate = Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');
                $paymentDate = $latestPayment ? Carbon::parse($latestPayment->paymentDate)->format('Y-m-d') : null;

                return [
                    'bookingID' => $booking->bookingID,
                    'guest_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phoneNumber,
                    'checkin_date' => $checkInDate,
                    'checkout_date' => $checkOutDate,
                    'booking_type' => $booking->bookingType,
                    'num_guests' => $booking->cart->numGuests,
                    'total_price' => $booking->totalPrice,
                    'total_paid' => $paymentSummary['total_paid'],
                    'total_refunded' => $paymentSummary['total_refunded'],
                    'net_paid' => $paymentSummary['net_paid'],
                    'remaining_balance' => $paymentSummary['remaining_balance'],
                    'payment_status' => $latestPayment ? $latestPayment->paymentStatus : null,
                    'payment_date' => $paymentDate,
                    'booking_status' => $booking->bookingStatus,
                    'special_requirements' => $booking->specialRequirements,
                    'units' => $units,
                    'days_count' => $booking->cart->daysCount,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedBookings,
                'total' => $bookings->total(),
                'current_page' => $bookings->currentPage(),
                'per_page' => $bookings->perPage(),
                'last_page' => $bookings->lastPage()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculatePaymentSummary($booking)
    {
        $payments = $booking->payments->where('paymentStatus', 'completed');
        
        $totalPaid = 0;
        $totalRefunded = 0;

        foreach ($payments as $payment) {
            if ($payment->paymentType === 'refund') {
                $totalRefunded += $payment->amountPaid;
            } else {
                $totalPaid += $payment->amountPaid;
            }
        }

        $netPaid = $totalPaid - $totalRefunded;
        $remainingBalance = max(0, $booking->totalPrice - $netPaid);

        return [
            'total_paid' => $totalPaid,
            'total_refunded' => $totalRefunded,
            'net_paid' => $netPaid,
            'remaining_balance' => $remainingBalance
        ];
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'guest_name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $value = strtolower(trim($value));
                        
                        if (!str_ends_with($value, '@gmail.com')) {
                            $fail('Only @gmail.com email addresses are accepted.');
                            return;
                        }
                        
                        $originalValue = request()->input($attribute);
                        if (preg_match('/[A-Z]/', $originalValue)) {
                            $fail('Email must be in lowercase letters only.');
                            return;
                        }
                        
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Please enter a valid email address.');
                            return;
                        }
                    },
                ],
                'phone' => 'required|string|regex:/^09\d{9}$/',
                'booking_type' => 'required|in:day-use,overnight',
                'checkin_date' => 'required|date|after_or_equal:today',
                'checkout_date' => 'nullable|date|after_or_equal:checkin_date',
                'num_guests' => 'required|integer|min:1',
                'unit_id' => 'required|exists:units,unitID',
                'special_requirements' => 'nullable|string'
            ]);

            // ✅ Convert email to lowercase
            $validated['email'] = strtolower(trim($validated['email']));

            // ✅ STANDARDIZED DATE FORMAT
            $checkInDate = Carbon::parse($validated['checkin_date'])->format('Y-m-d');
            $checkOutDate = $validated['checkout_date'] ? 
                Carbon::parse($validated['checkout_date'])->format('Y-m-d') : null;

            if ($validated['booking_type'] === 'overnight' && empty($validated['checkout_date'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Check-out date is required for overnight bookings'
                ], 422);
            }

            $unit = Unit::find($validated['unit_id']);
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected unit not found'
                ], 422);
            }

            if ($unit->unitType === 'cottage' && $validated['booking_type'] === 'overnight') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cottage units are only available for day-use bookings'
                ], 422);
            }

            if ($validated['booking_type'] === 'day-use') {
                $checkOutDate = $checkInDate;
                
                if ($validated['checkout_date'] && $validated['checkout_date'] !== $validated['checkin_date']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'For day-use bookings, check-out date must be the same as check-in date'
                    ], 422);
                }
            }

            if ($validated['booking_type'] === 'overnight' && $checkOutDate === $checkInDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'For overnight bookings, check-out date must be after check-in date'
                ], 422);
            }

            $entranceFee = EntranceFee::active()->first();
            $entranceFeeAmount = $entranceFee ? $entranceFee->amount : 0;
            $entranceFeeID = null;

            // Calculate days count
            if ($validated['booking_type'] === 'day-use') {
                $daysCount = 1;
            } else {
                $daysCount = max(1, Carbon::parse($checkOutDate)->diffInDays(Carbon::parse($checkInDate)));
            }

            $totalPrice = 0;

            if ($unit->unitType === 'cottage') {
                if (!$entranceFee) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active entrance fee found. Please set up an entrance fee first for cottage bookings.'
                    ], 422);
                }
                $entranceFeeID = $entranceFee->entranceFeeID;
                $totalPrice = ($entranceFeeAmount * $validated['num_guests']) + $unit->unitRatePrice;
            } else {
                if ($validated['booking_type'] === 'day-use') {
                    $totalPrice = $unit->unitRatePrice * $validated['num_guests'];
                } else {
                    $totalPrice = $unit->unitRatePrice * $validated['num_guests'] * $daysCount;
                }
            }

            // Check if unit is blocked - IGNORE unitStatus, check block dates only
            $unitBlocked = $this->isUnitBlocked($unit, $checkInDate, $checkOutDate ?? $checkInDate);
            if ($unitBlocked) {
                $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                
                $message = "This unit ({$unit->unitName}) is not available from {$blockStart} to {$blockEnd} due to maintenance/blocking.";
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'conflict_type' => 'unit_blocked'
                ], 422);
            }

            // FIXED: Check for special event conflicts FIRST
            $specialEventConflict = $this->checkSpecialEventConflict(
                $checkInDate,
                $checkOutDate
            );

            if ($specialEventConflict['has_conflict']) {
                return response()->json([
                    'success' => false,
                    'message' => $specialEventConflict['message'],
                    'conflict_type' => 'special_event_blocking'
                ], 422);
            }

            // FIXED: Enhanced conflict checking for normal bookings
            $normalConflict = $this->checkNormalBookingConflict(
                $validated['unit_id'],
                $checkInDate,
                $checkOutDate,
                $validated['booking_type'],
                null,
                false // Don't check block dates again (already checked above)
            );

            if ($normalConflict) {
                $unitName = $unit->unitName;
                $checkinFormatted = Carbon::parse($checkInDate)->format('M d, Y');
                $checkoutFormatted = $checkOutDate ? Carbon::parse($checkOutDate)->format('M d, Y') : $checkinFormatted;
                
                $message = $validated['booking_type'] === 'day-use'
                    ? "This unit ({$unitName}) is already booked for {$checkinFormatted}. Please choose a different date or unit."
                    : "This unit ({$unitName}) is already booked from {$checkinFormatted} to {$checkoutFormatted}. Please choose different dates or another unit.";
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'conflict_type' => 'normal_booking'
                ], 422);
            }

            DB::beginTransaction();

            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $validated['guest_name'],
                    'username' => strtolower(str_replace(' ', '', $validated['guest_name'])) . rand(1000, 9999),
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phone'],
                    'password' => bcrypt('temporary123'),
                    'email_verified_at' => now(),
                    'role' => 'guest'
                ]);
            }

            // ✅ STANDARDIZED DATE FORMAT FOR CART
            $cart = Cart::create([
                'user_id' => $user->userID,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
                'daysCount' => $daysCount,
                'numGuests' => $validated['num_guests']
            ]);

            CartItem::create([
                'cartID' => $cart->cartID,
                'unitID' => $validated['unit_id'],
                'subtotalPrice' => $totalPrice,
                'isBooked' => true
            ]);

            $bookingData = [
                'cartID' => $cart->cartID,
                'totalPrice' => $totalPrice,
                'bookingStatus' => 'confirmed',
                'bookingType' => $validated['booking_type'],
                'eventType' => 'normal-booking',
                'specialRequirements' => $validated['special_requirements']
            ];

            if ($entranceFeeID) {
                $bookingData['entranceFeeID'] = $entranceFeeID;
            }

            $booking = Booking::create($bookingData);

            DB::commit();

            // Send booking confirmation email
            try {
                Mail::to($validated['email'])->send(new BookingConfirmationEmail($booking->load('cart.user', 'cart.cartItems.unit')));
                Log::info("Booking confirmation email sent to {$validated['email']} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send booking confirmation email: " . $e->getMessage());
                // Continue even if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking->load('cart.user', 'cart.cartItems.unit', 'entranceFee')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIXED SPECIAL EVENT CONFLICT CHECKING
     * Special events block ALL normal bookings for ANY date overlap
     */
    private function checkSpecialEventConflict($checkinDate, $checkoutDate)
    {
        Log::info('=== SPECIAL EVENT CONFLICT CHECK ===', [
            'checkin' => $checkinDate,
            'checkout' => $checkoutDate
        ]);

        $newCheckin = Carbon::parse($checkinDate)->startOfDay();
        $newCheckout = $checkoutDate ? 
            Carbon::parse($checkoutDate)->startOfDay() : 
            Carbon::parse($checkinDate)->endOfDay();

        // Get ALL special events (regardless of unit) with active status
        $specialEvents = Booking::whereIn('bookingStatus', ['confirmed', 'pending'])
            ->where('bookingType', 'special-event')
            ->with('cart')
            ->get();

        if ($specialEvents->isEmpty()) {
            Log::info('No special events found - NO SPECIAL EVENT CONFLICT');
            return [
                'has_conflict' => false,
                'message' => 'No special events conflict'
            ];
        }

        foreach ($specialEvents as $event) {
            if (!$event->cart) continue;

            // ✅ STANDARDIZED DATE FORMAT
            $eventCheckin = Carbon::parse($event->cart->checkInDate)->startOfDay();
            $eventCheckout = Carbon::parse($event->cart->checkOutDate)->startOfDay();

            Log::info('Checking against special event', [
                'event_id' => $event->bookingID,
                'event_checkin' => $eventCheckin->toDateString(),
                'event_checkout' => $eventCheckout->toDateString(),
                'new_checkin' => $newCheckin->toDateString(),
                'new_checkout' => $newCheckout->toDateString()
            ]);

            // Check for any overlap between new booking and special event
            $hasOverlap = (
                ($newCheckin->between($eventCheckin, $eventCheckout)) || // New checkin during event
                ($newCheckout->between($eventCheckin, $eventCheckout)) || // New checkout during event
                ($eventCheckin->between($newCheckin, $newCheckout)) || // Event checkin during new booking
                ($eventCheckout->between($newCheckin, $newCheckout)) || // Event checkout during new booking
                ($newCheckin->eq($eventCheckin) && $newCheckout->eq($eventCheckout)) // Exact same dates
            );

            if ($hasOverlap) {
                Log::info('❌ SPECIAL EVENT CONFLICT DETECTED - Normal booking overlaps with special event');
                return [
                    'has_conflict' => true,
                    'message' => 'Cannot book normal booking. There is a special event on the selected dates. Please choose different dates.'
                ];
            }
        }

        Log::info('✅ NO SPECIAL EVENT CONFLICT');
        return [
            'has_conflict' => false,
            'message' => 'No special events conflict'
        ];
    }

    /**
     * FIXED NORMAL BOOKING CONFLICT CHECKING
     * Handles conflicts between normal bookings only with strict date checking
     */
    private function checkNormalBookingConflict($unitId, $checkinDate, $checkoutDate, $bookingType, $excludeBookingId = null, $checkBlockDates = true)
    {
        Log::info('=== ENHANCED NORMAL BOOKING CONFLICT CHECK ===', [
            'unit_id' => $unitId,
            'checkin' => $checkinDate,
            'checkout' => $checkoutDate,
            'type' => $bookingType,
            'check_block_dates' => $checkBlockDates
        ]);

        // Check if unit is blocked for these dates (if requested)
        if ($checkBlockDates) {
            $unit = Unit::find($unitId);
            if ($unit) {
                $unitBlocked = $this->isUnitBlocked($unit, $checkinDate, $checkoutDate);
                if ($unitBlocked) {
                    Log::info('❌ UNIT BLOCKED CONFLICT - Unit is blocked for maintenance or other reasons');
                    return true;
                }
            }
        }

        // Get only normal bookings for this unit
        $existingBookings = Booking::whereHas('cart.cartItems', function($q) use ($unitId) {
                $q->where('unitID', $unitId);
            })
            ->whereIn('bookingStatus', ['confirmed', 'pending'])
            ->where('bookingType', '!=', 'special-event')
            ->when($excludeBookingId, function($q) use ($excludeBookingId) {
                $q->where('bookingID', '!=', $excludeBookingId);
            })
            ->with('cart')
            ->get();

        if ($existingBookings->isEmpty()) {
            Log::info('No existing normal bookings - NO CONFLICT');
            return false;
        }

        // Parse new booking dates
        $newCheckin = Carbon::parse($checkinDate)->startOfDay();
        $newCheckout = $checkoutDate ? Carbon::parse($checkoutDate)->startOfDay() : $newCheckin->copy()->addDay();

        // Adjust for day-use bookings
        if ($bookingType === 'day-use') {
            // Day-use bookings occupy the entire day
            $newCheckout = $newCheckin->copy()->endOfDay();
        }

        Log::info('New booking date range:', [
            'new_checkin' => $newCheckin->toDateTimeString(),
            'new_checkout' => $newCheckout->toDateTimeString(),
            'booking_type' => $bookingType
        ]);

        foreach ($existingBookings as $booking) {
            if (!$booking->cart) continue;

            // Parse existing booking dates
            $existingCheckin = Carbon::parse($booking->cart->checkInDate)->startOfDay();
            $existingCheckout = Carbon::parse($booking->cart->checkOutDate)->startOfDay();

            // Adjust for day-use bookings
            if ($booking->bookingType === 'day-use') {
                // Day-use bookings occupy the entire day
                $existingCheckout = $existingCheckin->copy()->endOfDay();
            } else {
                // Overnight bookings - check-out date is the next day morning
                $existingCheckout = Carbon::parse($booking->cart->checkOutDate)->startOfDay();
            }

            Log::info('Checking against existing booking:', [
                'booking_id' => $booking->bookingID,
                'existing_type' => $booking->bookingType,
                'existing_checkin' => $existingCheckin->toDateTimeString(),
                'existing_checkout' => $existingCheckout->toDateTimeString()
            ]);

            // Check for date overlap
            $hasOverlap = (
                ($newCheckin->between($existingCheckin, $existingCheckout)) || // New checkin during existing booking
                ($newCheckout->between($existingCheckin, $existingCheckout)) || // New checkout during existing booking
                ($existingCheckin->between($newCheckin, $newCheckout)) || // Existing checkin during new booking
                ($existingCheckout->between($newCheckin, $newCheckout)) || // Existing checkout during new booking
                ($newCheckin->eq($existingCheckin) && $newCheckout->eq($existingCheckout)) // Exact same dates
            );

            if ($hasOverlap) {
                Log::info('❌ NORMAL BOOKING CONFLICT DETECTED', [
                    'conflict_reason' => 'Booking overlaps with existing booking',
                    'new_type' => $bookingType,
                    'existing_type' => $booking->bookingType,
                    'new_dates' => $newCheckin->toDateString() . ' to ' . $newCheckout->toDateString(),
                    'existing_dates' => $existingCheckin->toDateString() . ' to ' . $existingCheckout->toDateString()
                ]);
                return true;
            }
        }

        Log::info('✅ NO NORMAL BOOKING CONFLICT');
        return false;
    }

    /**
     * Check if unit is blocked for maintenance or other reasons
     * IGNORES unitStatus - only checks blockStartDate and blockEndDate
     */
    private function isUnitBlocked($unit, $checkinDate, $checkoutDate)
    {
        Log::info('=== UNIT BLOCK CHECK (IGNORE STATUS) ===', [
            'unit_id' => $unit->unitID,
            'unit_name' => $unit->unitName,
            'unit_status' => $unit->unitStatus, // For logging only
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'block_start_date' => $unit->blockStartDate,
            'block_end_date' => $unit->blockEndDate
        ]);

        // Kung walang block dates set, UNIT IS ALWAYS AVAILABLE (ignore status)
        if (!$unit->blockStartDate || !$unit->blockEndDate) {
            Log::info('✅ NO BLOCK DATES SET - Unit is available (ignoring status)');
            return false;
        }

        $newCheckin = Carbon::parse($checkinDate)->startOfDay();
        $newCheckout = Carbon::parse($checkoutDate ?? $checkinDate)->startOfDay();
        $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
        $blockEnd = Carbon::parse($unit->blockEndDate)->startOfDay();

        // Check if booking dates overlap with block period
        $isBlocked = (
            ($newCheckin->between($blockStart, $blockEnd)) || // Checkin during block
            ($newCheckout->between($blockStart, $blockEnd)) || // Checkout during block
            ($blockStart->between($newCheckin, $newCheckout)) || // Block starts during booking
            ($blockEnd->between($newCheckin, $newCheckout)) || // Block ends during booking
            ($newCheckin->lte($blockStart) && $newCheckout->gte($blockEnd)) // Booking encompasses entire block
        );

        if ($isBlocked) {
            Log::info('❌ UNIT BLOCKED - Booking dates overlap with block period', [
                'booking_dates' => $newCheckin->toDateString() . ' to ' . $newCheckout->toDateString(),
                'block_dates' => $blockStart->toDateString() . ' to ' . $blockEnd->toDateString()
            ]);
        } else {
            Log::info('✅ UNIT AVAILABLE - Booking dates are outside block period', [
                'booking_dates' => $newCheckin->toDateString() . ' to ' . $newCheckout->toDateString(),
                'block_dates' => $blockStart->toDateString() . ' to ' . $blockEnd->toDateString()
            ]);
        }

        return $isBlocked;
    }

    /**
     * COMPREHENSIVE DATE CONFLICT CHECKING (for backward compatibility)
     */
    private function checkDateConflict($unitId, $checkinDate, $checkoutDate, $bookingType, $excludeBookingId = null)
    {
        // First check for special event conflicts
        $specialEventConflict = $this->checkSpecialEventConflict($checkinDate, $checkoutDate);
        if ($specialEventConflict['has_conflict']) {
            return true;
        }

        // Then check for normal booking conflicts
        return $this->checkNormalBookingConflict($unitId, $checkinDate, $checkoutDate, $bookingType, $excludeBookingId);
    }

    public function show($id)
    {
        try {
            $booking = Booking::with(['cart.user', 'cart.cartItems.unit', 'payments', 'entranceFee'])
                ->findOrFail($id);

            $user = $booking->cart->user;
            $units = $booking->cart->cartItems->map(function($item) {
                return [
                    'unitID' => $item->unit->unitID,
                    'unitName' => $item->unit->unitName,
                    'unitType' => $item->unit->unitType,
                    'isBooked' => $item->isBooked
                ];
            });

            $paymentSummary = $this->calculatePaymentSummary($booking);

            // ✅ STANDARDIZED DATE FORMAT
            $checkInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
            $checkOutDate = Carbon::parse($booking->cart->checkOutDate)->format('Y-m-d');

            return response()->json([
                'success' => true,
                'data' => [
                    'bookingID' => $booking->bookingID,
                    'guest_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phoneNumber,
                    'checkin_date' => $checkInDate,
                    'checkout_date' => $checkOutDate,
                    'booking_type' => $booking->bookingType,
                    'num_guests' => $booking->cart->numGuests,
                    'total_price' => $booking->totalPrice,
                    'total_paid' => $paymentSummary['total_paid'],
                    'total_refunded' => $paymentSummary['total_refunded'],
                    'net_paid' => $paymentSummary['net_paid'],
                    'remaining_balance' => $paymentSummary['remaining_balance'],
                    'booking_status' => $booking->bookingStatus,
                    'special_requirements' => $booking->specialRequirements,
                    'units' => $units,
                    'payments' => $booking->payments->map(function($payment) {
                        // ✅ STANDARDIZED DATE FORMAT FOR PAYMENTS
                        $paymentDate = $payment->paymentDate ? Carbon::parse($payment->paymentDate)->format('Y-m-d') : null;
                        $refundDate = $payment->refundDate ? Carbon::parse($payment->refundDate)->format('Y-m-d') : null;
                        
                        return [
                            'paymentID' => $payment->paymentID,
                            'paymentReference' => $payment->paymentReference,
                            'paymentMethod' => $payment->paymentMethod,
                            'paymentType' => $payment->paymentType,
                            'amountPaid' => $payment->amountPaid,
                            'remainingBalance' => $payment->remainingBalance,
                            'paymentDate' => $paymentDate,
                            'paymentStatus' => $payment->paymentStatus,
                            'isRefunded' => $payment->isRefunded,
                            'refundDate' => $refundDate,
                            'refundAmount' => $payment->refundAmount,
                            'refundReason' => $payment->refundReason
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'guest_name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $value = strtolower(trim($value));
                        
                        if (!str_ends_with($value, '@gmail.com')) {
                            $fail('Only @gmail.com email addresses are accepted.');
                            return;
                        }
                        
                        $originalValue = request()->input($attribute);
                        if (preg_match('/[A-Z]/', $originalValue)) {
                            $fail('Email must be in lowercase letters only.');
                            return;
                        }
                        
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Please enter a valid email address.');
                            return;
                        }
                    },
                ],
                'phone' => 'required|string|regex:/^09\d{9}$/',
                'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
                'checkin_date' => 'required|date',
                'checkout_date' => 'nullable|date|after_or_equal:checkin_date',
                'num_guests' => 'required|integer|min:1',
                'unit_id' => 'required|exists:units,unitID',
                'unit_type' => 'required|in:room,cottage',
                'booking_type' => 'required|in:day-use,overnight',
                'total_price' => 'required|numeric|min:0',
                'special_requirements' => 'nullable|string',
                'cancellation_reason' => 'nullable|string',
                'refund_amount' => 'nullable|numeric|min:0',
                'refund_method' => 'nullable|in:cash,bank_transfer,gcash,credit_card'
            ]);

            // ✅ Convert email to lowercase
            $validated['email'] = strtolower(trim($validated['email']));

            // ✅ STANDARDIZED DATE FORMAT
            $checkInDate = Carbon::parse($validated['checkin_date'])->format('Y-m-d');
            $checkOutDate = $validated['checkout_date'] ? 
                Carbon::parse($validated['checkout_date'])->format('Y-m-d') : $checkInDate;

            DB::beginTransaction();

            $booking = Booking::with('cart.user', 'cart.cartItems.unit', 'payments')->findOrFail($id);
            
            // Store old status and unit for comparison
            $oldStatus = $booking->bookingStatus;
            $newStatus = $validated['booking_status'];
            $oldUnitId = $booking->cart->cartItems->first()->unitID;
            $newUnitId = $validated['unit_id'];

            // ✅ TRACK CANCELLATION DATA
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $booking->cancelledAt = now();
                $booking->cancelledBy = Auth::id();
                $booking->cancellationReason = $validated['cancellation_reason'] ?? null;
                
                Log::info('Booking cancelled', [
                    'booking_id' => $id,
                    'cancelled_by' => Auth::id(),
                    'cancelled_at' => now(),
                    'reason' => $validated['cancellation_reason']
                ]);

                // ✅ AUTO-PROCESS REFUND IF CANCELLATION HAS REFUND AMOUNT
                if (!empty($validated['refund_amount']) && $validated['refund_amount'] > 0 && !empty($validated['refund_method'])) {
                    // Calculate current net paid to validate refund amount
                    $paymentSummary = $this->calculatePaymentSummary($booking);
                    $currentNetPaid = $paymentSummary['net_paid'];

                    if ($validated['refund_amount'] > $currentNetPaid) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Refund amount exceeds net paid amount. Available for refund: ₱' . number_format($currentNetPaid, 2)
                        ], 422);
                    }

                    $newNetPaid = $currentNetPaid - $validated['refund_amount'];
                    $newRemainingBalance = max(0, $booking->totalPrice - $newNetPaid);
                    $refundDate = Carbon::now()->format('Y-m-d');

                    Payment::create([
                        'bookingID' => $id,
                        'paymentReference' => 'REF-' . time(),
                        'paymentMethod' => $validated['refund_method'],
                        'paymentType' => 'refund',
                        'amountPaid' => $validated['refund_amount'],
                        'remainingBalance' => $newRemainingBalance,
                        'paymentDate' => $refundDate,
                        'paymentStatus' => 'completed',
                        'isRefunded' => true,
                        'refundDate' => $refundDate,
                        'refundAmount' => $validated['refund_amount'],
                        'refundReason' => $validated['cancellation_reason'] ?? 'Booking cancelled'
                    ]);

                    Log::info('Refund processed on cancellation', [
                        'booking_id' => $id,
                        'refund_amount' => $validated['refund_amount'],
                        'refund_method' => $validated['refund_method'],
                        'new_net_paid' => $newNetPaid,
                        'new_remaining_balance' => $newRemainingBalance
                    ]);
                }
            }

            // ✅ VALIDATE UNIT TYPE AND BOOKING TYPE COMPATIBILITY
            if ($validated['unit_type'] === 'cottage' && $validated['booking_type'] === 'overnight') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cottage units are only available for day-use bookings'
                ], 422);
            }

            // ✅ VALIDATE DAY-USE CHECKOUT DATE
            if ($validated['booking_type'] === 'day-use') {
                if ($checkOutDate !== $checkInDate) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'For day-use bookings, check-out date must be the same as check-in date'
                    ], 422);
                }
            }

            // ✅ VALIDATE OVERNIGHT CHECKOUT DATE
            if ($validated['booking_type'] === 'overnight') {
                if (!$validated['checkout_date']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Check-out date is required for overnight bookings'
                    ], 422);
                }
                if ($checkOutDate === $checkInDate) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'For overnight bookings, check-out date must be after check-in date'
                    ], 422);
                }
            }

            // ✅ HANDLE UNIT CHANGE
            if ($newUnitId != $oldUnitId) {
                $newUnit = Unit::find($newUnitId);

                if (!$newUnit) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected unit not found'
                    ], 422);
                }

                // Verify unit type matches
                if ($newUnit->unitType !== $validated['unit_type']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected unit does not match the unit type'
                    ], 422);
                }

                // Check if new unit is blocked
                $unitBlocked = $this->isUnitBlocked($newUnit, $checkInDate, $checkOutDate);
                if ($unitBlocked) {
                    $blockStart = $newUnit->blockStartDate ? Carbon::parse($newUnit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd = $newUnit->blockEndDate ? Carbon::parse($newUnit->blockEndDate)->format('M d, Y') : null;

                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Selected unit ({$newUnit->unitName}) is blocked from {$blockStart} to {$blockEnd}",
                        'conflict_type' => 'unit_blocked'
                    ], 422);
                }

                Log::info('Unit changed for booking', [
                    'booking_id' => $id,
                    'old_unit_id' => $oldUnitId,
                    'new_unit_id' => $newUnitId,
                    'old_unit_name' => Unit::find($oldUnitId)->unitName ?? 'Unknown',
                    'new_unit_name' => $newUnit->unitName
                ]);
            }

            // ✅ CHECK FOR SPECIAL EVENT CONFLICTS (only for active bookings)
            if (in_array($newStatus, ['confirmed', 'pending'])) {
                $specialEventConflict = $this->checkSpecialEventConflict(
                    $checkInDate,
                    $checkOutDate
                );

                if ($specialEventConflict['has_conflict']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $specialEventConflict['message'],
                        'conflict_type' => 'special_event_blocking'
                    ], 422);
                }
            }

            // ✅ CHECK FOR NORMAL BOOKING CONFLICTS (use new unit ID)
            if (in_array($newStatus, ['confirmed', 'pending'])) {
                $normalConflict = $this->checkNormalBookingConflict(
                    $newUnitId,
                    $checkInDate,
                    $checkOutDate,
                    $validated['booking_type'],
                    $id, // Exclude current booking
                    false // Don't check block dates (already checked above)
                );

                if ($normalConflict) {
                    $unit = Unit::find($newUnitId);
                    $unitName = $unit ? $unit->unitName : 'Selected unit';
                    $checkinFormatted = Carbon::parse($checkInDate)->format('M d, Y');
                    $checkoutFormatted = Carbon::parse($checkOutDate)->format('M d, Y');

                    $message = $validated['booking_type'] === 'day-use'
                        ? "Unit ({$unitName}) is already booked for {$checkinFormatted}. Please choose different dates or another unit."
                        : "Unit ({$unitName}) is already booked from {$checkinFormatted} to {$checkoutFormatted}. Please choose different dates or another unit.";

                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'conflict_type' => 'normal_booking'
                    ], 422);
                }
            }

            // ✅ UPDATE USER INFORMATION
            $booking->cart->user->update([
                'name' => $validated['guest_name'],
                'email' => $validated['email'],
                'phoneNumber' => $validated['phone']
            ]);

            // ✅ CALCULATE DAYS COUNT
            if ($validated['booking_type'] === 'day-use') {
                $daysCount = 1;
            } else {
                $daysCount = max(1, Carbon::parse($checkOutDate)->diffInDays(Carbon::parse($checkInDate)));
            }

            // ✅ UPDATE CART INFORMATION
            $isActive = !in_array($newStatus, ['completed', 'cancelled']);

            $booking->cart->update([
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
                'numGuests' => $validated['num_guests'],
                'daysCount' => $daysCount,
                'is_active' => $isActive
            ]);

            // ✅ UPDATE CART ITEM (UNIT) IF CHANGED
            if ($newUnitId != $oldUnitId) {
                $cartItem = $booking->cart->cartItems->first();
                $cartItem->update([
                    'unitID' => $newUnitId,
                    'subtotalPrice' => $validated['total_price']
                ]);
            } else {
                // Update subtotal price even if unit didn't change (price may differ due to guest/date changes)
                $cartItem = $booking->cart->cartItems->first();
                $cartItem->update([
                    'subtotalPrice' => $validated['total_price']
                ]);
            }

            // ✅ UPDATE BOOKING INFORMATION
            $booking->update([
                'totalPrice' => $validated['total_price'],
                'bookingStatus' => $newStatus,
                'specialRequirements' => $validated['special_requirements']
            ]);

            DB::commit();

            // ✅ SEND STATUS CHANGE EMAILS (after successful commit)
            if ($oldStatus !== $newStatus) {
                try {
                    if ($newStatus === 'completed') {
                        Mail::to($validated['email'])->send(new BookingCompletedEmail($booking));
                        Log::info("Booking completed email sent to {$validated['email']} for booking #{$booking->bookingID}");
                    } 
                    elseif ($newStatus === 'cancelled') {
                        $cancellationReason = $validated['cancellation_reason'] ?? null;
                        $refundAmount = $validated['refund_amount'] ?? 0;
                        $refundMethod = $validated['refund_method'] ?? null;
                        
                        Mail::to($validated['email'])->send(new BookingCancelledEmail(
                            $booking, 
                            $cancellationReason,
                            $refundAmount,
                            $refundMethod
                        ));
                        Log::info("Booking cancelled email sent to {$validated['email']} for booking #{$booking->bookingID}");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send status change email: " . $e->getMessage());
                    // Continue even if email fails
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking->fresh()->load('cart.user', 'cart.cartItems.unit', 'payments')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::with(['cart.user', 'cart.cartItems', 'payments'])->findOrFail($id);
            $cartID = $booking->cartID;
            $userID = $booking->cart->user_id;

            Payment::where('bookingID', $id)->delete();
            $booking->delete();
            CartItem::where('cartID', $cartID)->delete();
            Cart::where('cartID', $cartID)->delete();

            $userHasOtherBookings = $this->checkUserHasOtherBookings($userID, $cartID);
            
            if (!$userHasOtherBookings) {
               // User::where('userID', $userID)->delete();
                Log::info("User {$userID} deleted along with booking {$id}");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking and associated user data deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting booking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkUserHasOtherBookings($userID, $currentCartID)
    {
        $otherCarts = Cart::where('user_id', $userID)
            ->where('cartID', '!=', $currentCartID)
            ->exists();

        return $otherCarts;
    }

    public function getAvailableUnits(Request $request)
    {
        try {
            $unitType = $request->query('unit_type', 'room');
            $checkinDate = $request->query('checkin_date');
            $checkoutDate = $request->query('checkout_date');
            $bookingType = $request->query('booking_type', 'day-use');
            
            if ($unitType === 'cottage' && $bookingType === 'overnight') {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'entrance_fee' => 0,
                    'message' => 'Cottage units are not available for overnight bookings'
                ]);
            }

            // GET ALL UNITS REGARDLESS OF STATUS
            $units = Unit::where('unitType', $unitType)
                ->select('unitID', 'unitName', 'unitType', 'capacity', 'unitRatePrice', 'blockStartDate', 'blockEndDate', 'unitStatus')
                ->get();

            $entranceFee = EntranceFee::active()->first();
            $entranceFeeAmount = $entranceFee ? $entranceFee->amount : 0;

            if ($checkinDate) {
                // ✅ STANDARDIZED DATE FORMAT
                $finalCheckoutDate = $bookingType === 'day-use' ? $checkinDate : ($checkoutDate ?? $checkinDate);
                
                // First check for special event conflicts
                $specialEventConflict = $this->checkSpecialEventConflict($checkinDate, $finalCheckoutDate);
                
                if ($specialEventConflict['has_conflict']) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'entrance_fee' => $entranceFeeAmount,
                        'message' => 'No units available due to special event',
                        'has_special_event_conflict' => true
                    ]);
                }

                // Filter units based on both booking conflicts AND block dates
                $availableUnits = $units->filter(function($unit) use ($checkinDate, $finalCheckoutDate, $bookingType) {
                    // Check if unit is blocked for these dates (ignores unitStatus)
                    $isBlocked = $this->isUnitBlocked($unit, $checkinDate, $finalCheckoutDate);
                    if ($isBlocked) {
                        Log::info('Unit filtered out due to block:', [
                            'unit_id' => $unit->unitID,
                            'unit_name' => $unit->unitName,
                            'block_start' => $unit->blockStartDate,
                            'block_end' => $unit->blockEndDate,
                            'unit_status' => $unit->unitStatus
                        ]);
                        return false;
                    }
                    
                    // Check for booking conflicts (don't check block dates again)
                    $hasConflict = $this->checkNormalBookingConflict(
                        $unit->unitID, 
                        $checkinDate, 
                        $finalCheckoutDate, 
                        $bookingType,
                        null,
                        false  // Don't check block dates again (we already did)
                    );
                    
                    if ($hasConflict) {
                        Log::info('Unit filtered out due to booking conflict:', [
                            'unit_id' => $unit->unitID,
                            'unit_name' => $unit->unitName
                        ]);
                    }
                    
                    return !$hasConflict;
                });
                
                return response()->json([
                    'success' => true,
                    'data' => $availableUnits->values(),
                    'entrance_fee' => $entranceFeeAmount,
                    'has_special_event_conflict' => false,
                    'message' => $availableUnits->count() . ' unit(s) available'
                ]);
            }

            // Kung walang checkin date, ipakita lahat ng units
            return response()->json([
                'success' => true,
                'data' => $units,
                'entrance_fee' => $entranceFeeAmount,
                'has_special_event_conflict' => false,
                'message' => 'All units loaded'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching units: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkUnitAvailability(Request $request)
    {
        try {
            $request->validate([
                'unit_id' => 'required|exists:units,unitID',
                'checkin_date' => 'required|date',
                'checkout_date' => 'required|date',
                'booking_type' => 'required|in:day-use,overnight'
            ]);

            // ✅ STANDARDIZED DATE FORMAT
            $checkInDate = Carbon::parse($request->checkin_date)->format('Y-m-d');
            $checkOutDate = Carbon::parse($request->checkout_date)->format('Y-m-d');

            $unit = Unit::find($request->unit_id);
            
            if ($unit->unitType === 'cottage' && $request->booking_type === 'overnight') {
                return response()->json([
                    'success' => false,
                    'available' => false,
                    'message' => 'Cottage units are only available for day-use bookings'
                ], 422);
            }

            // First check if unit is blocked - IGNORES unitStatus
            $unitBlocked = $this->isUnitBlocked($unit, $checkInDate, $checkOutDate);
            if ($unitBlocked) {
                $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                
                return response()->json([
                    'success' => true,
                    'available' => false,
                    'message' => "Unit is blocked from {$blockStart} to {$blockEnd}",
                    'conflict_type' => 'unit_blocked'
                ]);
            }

            // Check for special event conflicts
            $specialEventConflict = $this->checkSpecialEventConflict($checkInDate, $checkOutDate);

            if ($specialEventConflict['has_conflict']) {
                return response()->json([
                    'success' => true,
                    'available' => false,
                    'message' => $specialEventConflict['message'],
                    'conflict_type' => 'special_event'
                ]);
            }

            // Get exclude booking ID if provided (for edit functionality)
            $excludeBookingId = $request->query('exclude_booking_id');
            
            $isAvailable = !$this->checkNormalBookingConflict(
                $request->unit_id,
                $checkInDate,
                $checkOutDate,
                $request->booking_type,
                $excludeBookingId,
                false  // Don't check block dates (already checked above)
            );

            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'message' => $isAvailable 
                    ? 'Unit is available for the selected dates' 
                    : 'Unit is not available for the selected dates',
                'conflict_type' => $isAvailable ? null : 'normal_booking'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking availability: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking availability'
            ], 500);
        }
    }

    public function getPayments($id)
    {
        try {
            $payments = Payment::where('bookingID', $id)
                ->orderBy('paymentDate', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $payments->map(function($payment) {
                    // ✅ STANDARDIZED DATE FORMAT FOR PAYMENTS
                    $paymentDate = $payment->paymentDate ? Carbon::parse($payment->paymentDate)->format('Y-m-d') : null;
                    $refundDate = $payment->refundDate ? Carbon::parse($payment->refundDate)->format('Y-m-d') : null;
                    
                    return [
                        'paymentID' => $payment->paymentID,
                        'paymentReference' => $payment->paymentReference,
                        'paymentMethod' => $payment->paymentMethod,
                        'paymentType' => $payment->paymentType,
                        'amountPaid' => $payment->amountPaid,
                        'remainingBalance' => $payment->remainingBalance,
                        'paymentDate' => $paymentDate,
                        'paymentStatus' => $payment->paymentStatus,
                        'isRefunded' => $payment->isRefunded,
                        'refundDate' => $refundDate,
                        'refundAmount' => $payment->refundAmount,
                        'refundReason' => $payment->refundReason,
                        'created_at' => $payment->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payments'
            ], 500);
        }
    }

    public function addPayment(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'payment_type' => 'required|in:downpayment,full,remaining',
                'payment_method' => 'required|in:cash,gcash,bank_transfer,credit_card,debit_card',
                'amount_paid' => 'required|numeric|min:0.01',
                'payment_date' => 'required|date',
                'payment_reference' => 'nullable|string'
            ]);

            DB::beginTransaction();

            $booking = Booking::findOrFail($id);
            
            $paymentSummary = $this->calculatePaymentSummary($booking);
            $currentNetPaid = $paymentSummary['net_paid'];
            $remainingBalance = $paymentSummary['remaining_balance'];

            if ($validated['amount_paid'] > $remainingBalance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds remaining balance. Available balance: ₱' . number_format($remainingBalance, 2)
                ], 422);
            }

            $newNetPaid = $currentNetPaid + $validated['amount_paid'];
            $newRemainingBalance = max(0, $booking->totalPrice - $newNetPaid);

            // ✅ STANDARDIZED DATE FORMAT
            $paymentDate = Carbon::parse($validated['payment_date'])->format('Y-m-d');

            $payment = Payment::create([
                'bookingID' => $id,
                'paymentReference' => $validated['payment_reference'] ?? 'PAY-' . time(),
                'paymentMethod' => $validated['payment_method'],
                'paymentType' => $validated['payment_type'],
                'amountPaid' => $validated['amount_paid'],
                'remainingBalance' => $newRemainingBalance,
                'paymentDate' => $paymentDate,
                'paymentStatus' => 'completed',
                'isRefunded' => false
            ]);

            if ($newNetPaid >= $booking->totalPrice) {
                $booking->update(['bookingStatus' => 'confirmed']);
            } elseif ($booking->bookingStatus === 'pending' && $newNetPaid > 0) {
                $booking->update(['bookingStatus' => 'confirmed']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment added successfully',
                'data' => $payment
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processRefund(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'refund_amount' => 'required|numeric|min:0.01',
                'refund_date' => 'required|date',
                'refund_reason' => 'nullable|string',
                'refund_method' => 'required|in:cash,bank_transfer,gcash,credit_card'
            ]);

            DB::beginTransaction();

            $booking = Booking::findOrFail($id);
            
            $paymentSummary = $this->calculatePaymentSummary($booking);
            $currentNetPaid = $paymentSummary['net_paid'];

            if ($validated['refund_amount'] > $currentNetPaid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund amount exceeds net paid amount. Available for refund: ₱' . number_format($currentNetPaid, 2)
                ], 422);
            }

            $newNetPaid = $currentNetPaid - $validated['refund_amount'];
            $newRemainingBalance = max(0, $booking->totalPrice - $newNetPaid);

            // ✅ STANDARDIZED DATE FORMAT
            $refundDate = Carbon::parse($validated['refund_date'])->format('Y-m-d');

            $refundPayment = Payment::create([
                'bookingID' => $id,
                'paymentReference' => 'REF-' . time(),
                'paymentMethod' => $validated['refund_method'],
                'paymentType' => 'refund',
                'amountPaid' => $validated['refund_amount'],
                'remainingBalance' => $newRemainingBalance,
                'paymentDate' => $refundDate,
                'paymentStatus' => 'completed',
                'isRefunded' => true,
                'refundDate' => $refundDate,
                'refundAmount' => $validated['refund_amount'],
                'refundReason' => $validated['refund_reason']
            ]);

            if ($newNetPaid < $booking->totalPrice && $booking->bookingStatus === 'confirmed') {
                $booking->update(['bookingStatus' => 'pending']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'data' => $refundPayment
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing refund: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentSummary($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $paymentSummary = $this->calculatePaymentSummary($booking);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_amount' => $booking->totalPrice,
                    'total_paid' => $paymentSummary['total_paid'],
                    'total_refunded' => $paymentSummary['total_refunded'],
                    'net_paid' => $paymentSummary['net_paid'],
                    'remaining_balance' => $paymentSummary['remaining_balance'],
                    'refundable_amount' => $paymentSummary['net_paid'],
                    'is_fully_paid' => $paymentSummary['net_paid'] >= $booking->totalPrice,
                    'payment_progress' => $booking->totalPrice > 0 ? ($paymentSummary['net_paid'] / $booking->totalPrice) * 100 : 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting payment summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting payment summary'
            ], 500);
        }
    }

    /**
     * NEW METHOD: Check special event availability for specific dates
     */
    public function checkSpecialEventAvailability(Request $request)
    {
        try {
            $request->validate([
                'checkin_date' => 'required|date',
                'checkout_date' => 'required|date'
            ]);

            // ✅ STANDARDIZED DATE FORMAT
            $checkInDate = Carbon::parse($request->checkin_date)->format('Y-m-d');
            $checkOutDate = Carbon::parse($request->checkout_date)->format('Y-m-d');

            $conflictCheck = $this->checkSpecialEventConflict($checkInDate, $checkOutDate);

            return response()->json([
                'success' => true,
                'available' => !$conflictCheck['has_conflict'],
                'has_conflict' => $conflictCheck['has_conflict'],
                'message' => $conflictCheck['message']
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking special event availability: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking availability'
            ], 500);
        }
    }
}