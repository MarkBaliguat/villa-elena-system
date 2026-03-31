<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Unit;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\SpecialBookingCreatedEmail;
use App\Mail\SpecialBookingCancelledEmail;
use App\Mail\SpecialBookingCompletedEmail;
use Illuminate\Support\Facades\Mail;

class SpecialEventsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Booking::with(['cart.user', 'cart.cartItems.unit', 'payments'])
                ->where('bookingType', 'special-event')
                ->whereIn('bookingStatus', ['pending', 'confirmed'])
                ->orderBy('created_at', 'desc');

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('bookingStatus', $request->status);
            }

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('cart.user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phoneNumber', 'like', "%{$search}%");
                    })
                    ->orWhere('eventType', 'like', "%{$search}%")
                    ->orWhere('specialRequirements', 'like', "%{$search}%")
                    ->orWhere('bookingType', 'like', "%{$search}%");
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
                    'event_name' => $booking->eventType,
                    'checkin_date' => $checkInDate,
                    'checkout_date' => $checkOutDate,
                    'event_start_time' => $booking->eventStartTime ? Carbon::parse($booking->eventStartTime)->format('H:i') : null,
                    'event_end_time' => $booking->eventEndTime ? Carbon::parse($booking->eventEndTime)->format('H:i') : null,
                    'booking_type' => $booking->bookingType,
                    'num_guests' => $booking->cart->cartItems->first()?->numGuests,
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
            Log::error('Error fetching special events: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching special events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate payment summary correctly
     */
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

    /**
     * Check if unit is blocked based ONLY on blockStartDate and blockEndDate
     */
    private function isUnitBlockedForSpecialEvent($unit, $checkinDate)
    {
        Log::info('=== SPECIAL EVENT UNIT BLOCK CHECK ===', [
            'unit_id' => $unit->unitID,
            'unit_name' => $unit->unitName,
            'unit_status' => $unit->unitStatus, // For logging only
            'checkin_date' => $checkinDate,
            'block_start_date' => $unit->blockStartDate,
            'block_end_date' => $unit->blockEndDate
        ]);

        // Kung walang block dates set, UNIT IS ALWAYS AVAILABLE (ignore unitStatus)
        if (!$unit->blockStartDate || !$unit->blockEndDate) {
            Log::info('✅ NO BLOCK DATES SET - Unit is available for special events');
            return false;
        }

        $eventDate = Carbon::parse($checkinDate)->startOfDay();
        $blockStart = Carbon::parse($unit->blockStartDate)->startOfDay();
        $blockEnd = Carbon::parse($unit->blockEndDate)->startOfDay();

        // Check if event date is within block period
        $isBlocked = $eventDate->between($blockStart, $blockEnd);

        if ($isBlocked) {
            Log::info('❌ UNIT BLOCKED FOR SPECIAL EVENT - Date is within block period', [
                'event_date' => $eventDate->toDateString(),
                'block_period' => $blockStart->toDateString() . ' to ' . $blockEnd->toDateString()
            ]);
        } else {
            Log::info('✅ UNIT AVAILABLE FOR SPECIAL EVENT - Date is outside block period', [
                'event_date' => $eventDate->toDateString(),
                'block_period' => $blockStart->toDateString() . ' to ' . $blockEnd->toDateString()
            ]);
        }

        return $isBlocked;
    }

    /**
     * ENHANCED CONFLICT CHECKER FOR SPECIAL EVENTS
     */
    private function checkSpecialEventConflicts($checkinDate, $unitId = null, $excludeBookingId = null)
    {
        Log::info('=== ENHANCED SPECIAL EVENT CONFLICT CHECKER START ===', [
            'date' => $checkinDate,
            'unit_id' => $unitId,
            'exclude_booking' => $excludeBookingId
        ]);

        // ✅ STANDARDIZED DATE FORMAT
        $eventDate = Carbon::parse($checkinDate)->format('Y-m-d');

        // FIRST: Check for NORMAL bookings on this date - PREVENTS SPECIAL EVENTS IF NORMAL BOOKINGS EXIST
        $normalBookingQuery = Booking::whereIn('bookingStatus', ['pending', 'confirmed'])
            ->where('bookingType', '!=', 'special-event') // Exclude special events
            ->whereHas('cart', function($q) use ($eventDate) {
                $q->whereDate('checkInDate', $eventDate);
            });

        $normalBookingsOnDate = $normalBookingQuery->get();

        Log::info('Normal bookings on same date:', [
            'count' => $normalBookingsOnDate->count(),
            'bookings' => $normalBookingsOnDate->map(function($booking) {
                $checkInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                return [
                    'id' => $booking->bookingID,
                    'type' => $booking->bookingType,
                    'status' => $booking->bookingStatus,
                    'date' => $checkInDate
                ];
            })->toArray()
        ]);

        if ($normalBookingsOnDate->count() > 0) {
            Log::info('❌ CONFLICT: Normal bookings exist on this date - Special events not allowed');
            return [
                'has_conflict' => true,
                'message' => 'This date has existing normal bookings. Special events cannot be scheduled on dates with normal bookings.',
                'conflicting_events' => $normalBookingsOnDate,
                'conflict_type' => 'date_has_normal_bookings'
            ];
        }

        // SECOND: If specific unit is provided, check for other special events in the same unit
        if ($unitId) {
            $unit = Unit::find($unitId);
            if ($unit) {
                // Check if unit is blocked for the selected date
                $isUnitBlocked = $this->isUnitBlockedForSpecialEvent($unit, $checkinDate);
                if ($isUnitBlocked) {
                    return [
                        'has_conflict' => true,
                        'message' => 'Selected venue is blocked for maintenance on the selected date.',
                        'conflict_type' => 'unit_blocked'
                    ];
                }
            }

            // Check for other special events in the same unit on this date
            $specialEventQuery = Booking::whereIn('bookingStatus', ['pending', 'confirmed'])
                ->where('bookingType', 'special-event')
                ->whereHas('cart.cartItems', function($q) use ($unitId) {
                    $q->where('unitID', $unitId);
                })
                ->whereHas('cart', function($q) use ($eventDate) {
                    $q->whereDate('checkInDate', $eventDate);
                });

            if ($excludeBookingId) {
                $specialEventQuery->where('bookingID', '!=', $excludeBookingId);
            }

            $existingSpecialEvents = $specialEventQuery->get();

            Log::info('Other special events in same unit on same date:', [
                'unit_id' => $unitId,
                'count' => $existingSpecialEvents->count(),
                'bookings' => $existingSpecialEvents->map(function($booking) {
                    $checkInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                    return [
                        'id' => $booking->bookingID,
                        'type' => $booking->bookingType,
                        'status' => $booking->bookingStatus,
                        'date' => $checkInDate
                    ];
                })->toArray()
            ]);

            if ($existingSpecialEvents->count() > 0) {
                Log::info('❌ CONFLICT: Special event already exists in this unit on the same date');
                return [
                    'has_conflict' => true,
                    'message' => 'This venue is already booked for a special event on the selected date.',
                    'conflicting_events' => $existingSpecialEvents,
                    'conflict_type' => 'unit_has_special_event'
                ];
            }
        }

        Log::info('✅ NO CONFLICTS FOUND - Date and venue are available for special event');
        return [
            'has_conflict' => false,
            'message' => 'No conflicts found'
        ];
    }

    /**
     * Check unit availability for specific date
     */
    private function checkUnitAvailability($unitId, $checkinDate, $excludeBookingId = null)
    {
        Log::info('=== UNIT AVAILABILITY CHECK ===', [
            'unit_id' => $unitId,
            'date' => $checkinDate
        ]);

        // ✅ STANDARDIZED DATE FORMAT
        $eventDate = Carbon::parse($checkinDate)->format('Y-m-d');

        // Check for any bookings (both normal and special) for this unit on this date
        $bookingQuery = Booking::whereIn('bookingStatus', ['pending', 'confirmed'])
            ->whereHas('cart.cartItems', function($q) use ($unitId) {
                $q->where('unitID', $unitId);
            })
            ->whereHas('cart', function($q) use ($eventDate) {
                $q->whereDate('checkInDate', $eventDate);
            });

        if ($excludeBookingId) {
            $bookingQuery->where('bookingID', '!=', $excludeBookingId);
        }

        $existingBookings = $bookingQuery->get();

        Log::info('Existing bookings for unit:', [
            'count' => $existingBookings->count(),
            'bookings' => $existingBookings->map(function($booking) {
                $checkInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');
                return [
                    'id' => $booking->bookingID,
                    'type' => $booking->bookingType,
                    'status' => $booking->bookingStatus,
                    'date' => $checkInDate
                ];
            })->toArray()
        ]);

        return $existingBookings->isEmpty();
    }

    /**
     * NEW: Comprehensive availability check for special events
     */
    private function checkSpecialEventAvailability($checkinDate, $unitId = null, $excludeBookingId = null)
    {
        $conflictCheck = $this->checkSpecialEventConflicts($checkinDate, $unitId, $excludeBookingId);
        
        if ($conflictCheck['has_conflict']) {
            return [
                'available' => false,
                'message' => $conflictCheck['message'],
                'conflict_type' => $conflictCheck['conflict_type'] ?? 'unknown'
            ];
        }

        return [
            'available' => true,
            'message' => 'Date and venue are available for special event'
        ];
    }

    // Create new special event
    public function store(Request $request)
    {
        try {
            Log::info('=== SPECIAL EVENT CREATION ATTEMPT ===', $request->all());

            // Basic validation
            $validated = $request->validate([
                'guest_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|regex:/^09\d{9}$/',
                'event_name' => 'required|string|max:255',
                'checkin_date' => 'required|date|after_or_equal:today',
                'num_guests' => 'required|integer|min:1',
                'unit_id' => 'required|exists:units,unitID',
                'total_price' => 'required|numeric|min:0',
                'event_start_time' => 'required|date_format:H:i',
                'event_end_time' => 'required|date_format:H:i',
                'special_requirements' => 'nullable|string'
            ]);

            // Manual time validation
            if ($validated['event_start_time'] >= $validated['event_end_time']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event end time must be after start time'
                ], 422);
            }

            Log::info('Validation passed', $validated);

            // Get unit details - IGNORE unitStatus, check only if unit is for special events
            $unit = Unit::where('unitID', $validated['unit_id'])
                        ->where('for_special_events', true)
                        ->first();

            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected venue is not available for special events'
                ], 422);
            }

            // Check if unit is blocked based on block dates ONLY (ignore unitStatus)
            $isUnitBlocked = $this->isUnitBlockedForSpecialEvent($unit, $validated['checkin_date']);
            if ($isUnitBlocked) {
                $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;
                
                return response()->json([
                    'success' => false,
                    'message' => "Selected venue is blocked for maintenance from {$blockStart} to {$blockEnd}.",
                    'conflict_type' => 'unit_blocked'
                ], 422);
            }

            // Check capacity
            if ($validated['num_guests'] > $unit->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => "Number of guests ({$validated['num_guests']}) exceeds venue capacity ({$unit->capacity})"
                ], 422);
            }

            // ENHANCED CONFLICT CHECKING - PREVENTS SPECIAL EVENTS IF ANY BOOKING EXISTS
            $availabilityCheck = $this->checkSpecialEventAvailability(
                $validated['checkin_date'],
                $validated['unit_id']
            );

            if (!$availabilityCheck['available']) {
                return response()->json([
                    'success' => false,
                    'message' => $availabilityCheck['message'],
                    'conflict_type' => $availabilityCheck['conflict_type'] ?? 'unknown'
                ], 422);
            }

            DB::beginTransaction();

            // Find or create user
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $validated['guest_name'],
                    'username' => strtolower(str_replace(' ', '', $validated['guest_name'])) . rand(1000, 9999),
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phone'],
                    'password' => bcrypt('Temporary123!'),
                    'email_verified_at' => now(),
                    'role' => 'guest'
                ]);
            }

            // ✅ STANDARDIZED DATE FORMAT FOR CART
            $checkInDate = Carbon::parse($validated['checkin_date'])->format('Y-m-d');

            // Create cart
            $cart = Cart::create([
                'user_id' => $user->userID,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkInDate,
                'daysCount' => 1,
            ]);

            // Create cart item
            CartItem::create([
                'cartID' => $cart->cartID,
                'unitID' => $validated['unit_id'],
                'subtotalPrice' => $validated['total_price'],
                'isBooked' => true,
                'numGuests'     => $validated['num_guests']
            ]);

            // Create special event booking
            $booking = Booking::create([
                'cartID' => $cart->cartID,
                'totalPrice' => $validated['total_price'],
                'bookingStatus' => 'confirmed',
                'bookingType' => 'special-event',
                'eventType' => $validated['event_name'],
                'eventStartTime' => $validated['event_start_time'],
                'eventEndTime' => $validated['event_end_time'],
                'specialRequirements' => $validated['special_requirements']
            ]);

            DB::commit();

            Log::info('Special event created successfully', ['booking_id' => $booking->bookingID]);

            // Send booking confirmation email with try-catch
            try {
                Mail::to($validated['email'])->send(new SpecialBookingCreatedEmail($booking->load('cart.user', 'cart.cartItems.unit')));
                Log::info("Special booking confirmation email sent to {$validated['email']} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send special booking confirmation email: " . $e->getMessage());
                // Continue even if email fails - don't rollback the transaction
            }

            return response()->json([
                'success' => true,
                'message' => 'Special event created successfully',
                'data' => $booking->load('cart.user', 'cart.cartItems.unit')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error details:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating special event: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update special event
    public function update(Request $request, $id)
    {
        try {
            // Basic validation
            $validated = $request->validate([
                'guest_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|regex:/^09\d{9}$/',
                'event_name' => 'required|string|max:255',
                'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
                'checkin_date' => 'required|date',
                'num_guests' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
                'event_start_time' => 'required|date_format:H:i',
                'event_end_time' => 'required|date_format:H:i',
                'special_requirements' => 'nullable|string'
            ]);

            // Manual time validation
            if ($validated['event_start_time'] >= $validated['event_end_time']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event end time must be after start time'
                ], 422);
            }

            DB::beginTransaction();

            $booking = Booking::with('cart.user')->where('bookingType', 'special-event')->findOrFail($id);
            
            $oldStatus = $booking->bookingStatus;
            $newStatus = $validated['booking_status'];
            
            // ✅ STANDARDIZED DATE FORMAT FOR COMPARISON
            $newCheckInDate = Carbon::parse($validated['checkin_date'])->format('Y-m-d');
            $currentCheckInDate = Carbon::parse($booking->cart->checkInDate)->format('Y-m-d');

            // Get unit from cart items
            $unit = null;
            if ($booking->cart && $booking->cart->cartItems && $booking->cart->cartItems->count() > 0) {
                $unit = $booking->cart->cartItems->first()->unit;
            }

            // Check if unit is blocked based on block dates ONLY (ignore unitStatus)
            if ($unit) {
                $isUnitBlocked = $this->isUnitBlockedForSpecialEvent($unit, $validated['checkin_date']);
                if ($isUnitBlocked) {
                    $blockStart = $unit->blockStartDate ? Carbon::parse($unit->blockStartDate)->format('M d, Y') : null;
                    $blockEnd = $unit->blockEndDate ? Carbon::parse($unit->blockEndDate)->format('M d, Y') : null;

                    return response()->json([
                        'success' => false,
                        'message' => "Selected venue is blocked for maintenance from {$blockStart} to {$blockEnd}.",
                        'conflict_type' => 'unit_blocked'
                    ], 422);
                }
            }

            // ENHANCED CONFLICT CHECKING FOR UPDATE - ALWAYS CHECK AVAILABILITY
            $availabilityCheck = $this->checkSpecialEventAvailability(
                $validated['checkin_date'],
                $unit ? $unit->unitID : null,
                $id
            );

            if (!$availabilityCheck['available']) {
                return response()->json([
                    'success' => false,
                    'message' => $availabilityCheck['message'],
                    'conflict_type' => $availabilityCheck['conflict_type'] ?? 'unknown'
                ], 422);
            }

            // Update user info
            $booking->cart->user->update([
                'name' => $validated['guest_name'],
                'email' => $validated['email'],
                'phoneNumber' => $validated['phone']
            ]);

            // ✅ STANDARDIZED DATE FORMAT FOR CART UPDATE
            $checkInDate = Carbon::parse($validated['checkin_date'])->format('Y-m-d');
            
            $isActive = !in_array($newStatus, ['completed', 'cancelled']);

            // Update cart dates and numGuests
            $booking->cart->update([
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkInDate,
                'is_active' => $isActive
            ]);

            $booking->cart->cartItems->first()?->update([
                'numGuests' => $validated['num_guests']
            ]);

            // Update booking
            $booking->update([
                'eventType' => $validated['event_name'],
                'totalPrice' => $validated['total_price'],
                'bookingStatus' => $newStatus,
                'eventStartTime' => $validated['event_start_time'],
                'eventEndTime' => $validated['event_end_time'],
                'specialRequirements' => $validated['special_requirements']
            ]);

            DB::commit();

            // Send status change emails if status changed
            if ($oldStatus !== $newStatus) {
                try {
                    switch ($newStatus) {
                        case 'cancelled':
                            // Calculate refund amount if any
                            $paymentSummary = $this->calculatePaymentSummary($booking);
                            $refundAmount = $paymentSummary['net_paid'];
                            $refundMethod = 'To be determined';
                            
                            Mail::to($validated['email'])
                                ->send(new SpecialBookingCancelledEmail($booking, $refundAmount, $refundMethod));
                            Log::info("Special booking cancelled email sent to {$validated['email']} for booking #{$booking->bookingID}");
                            break;
                            
                        case 'completed':
                            Mail::to($validated['email'])
                                ->send(new SpecialBookingCompletedEmail($booking));
                            Log::info("Special booking completed email sent to {$validated['email']} for booking #{$booking->bookingID}");
                            break;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send status change email for booking #{$booking->bookingID}: " . $e->getMessage());
                    // Continue even if email fails
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Special event updated successfully',
                'data' => $booking->fresh()->load('cart.user', 'cart.cartItems.unit')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error details:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating special event: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get single special event
    public function show($id)
    {
        try {
            $booking = Booking::with(['cart.user', 'cart.cartItems.unit', 'payments'])
                ->where('bookingType', 'special-event')
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
            
            // Extract time from timestamp fields
            $eventStartTime = $booking->eventStartTime ? Carbon::parse($booking->eventStartTime)->format('H:i') : null;
            $eventEndTime = $booking->eventEndTime ? Carbon::parse($booking->eventEndTime)->format('H:i') : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'bookingID' => $booking->bookingID,
                    'guest_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phoneNumber,
                    'event_name' => $booking->eventType,
                    'checkin_date' => $checkInDate,
                    'checkout_date' => $checkOutDate,
                    'event_start_time' => $eventStartTime,
                    'event_end_time' => $eventEndTime,
                    'booking_type' => $booking->bookingType,
                    'num_guests' => $booking->cart->cartItems->first()?->numGuests,
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
            Log::error('Error fetching special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Special event not found'
            ], 404);
        }
    }

    // Delete special event
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::with(['cart.user', 'cart.cartItems', 'payments'])
                ->where('bookingType', 'special-event')
                ->findOrFail($id);
                
            $cartID = $booking->cartID;
            $userID = $booking->cart->user_id;
            $userEmail = $booking->cart->user->email;

            // Send cancellation email before deleting
            try {
                $paymentSummary = $this->calculatePaymentSummary($booking);
                $refundAmount = $paymentSummary['net_paid'];
                
                Mail::to($userEmail)
                    ->send(new SpecialBookingCancelledEmail($booking, $refundAmount, 'account deletion'));
                Log::info("Special booking deletion email sent to {$userEmail} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send deletion email for booking #{$booking->bookingID}: " . $e->getMessage());
                // Continue even if email fails
            }

            // Delete payments first
            Payment::where('bookingID', $id)->delete();

            // Delete booking
            $booking->delete();

            // Delete cart items
            CartItem::where('cartID', $cartID)->delete();

            // Delete cart
            Cart::where('cartID', $cartID)->delete();

            // Check if user has other bookings before deleting
            $userHasOtherBookings = $this->checkUserHasOtherBookings($userID, $cartID);
            
            if (!$userHasOtherBookings) {
                //User::where('userID', $userID)->delete();
                Log::info("User {$userID} deleted along with special event {$id}");
            } else {
                Log::info("User {$userID} has other bookings, preserved user data");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Special event and associated user data deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting special event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has other bookings besides the current one being deleted
     */
    private function checkUserHasOtherBookings($userID, $currentCartID)
    {
        $otherCarts = Cart::where('user_id', $userID)
            ->where('cartID', '!=', $currentCartID)
            ->exists();

        return $otherCarts;
    }

    // Get available special event units
    public function getAvailableUnits(Request $request)
    {
        try {
            $checkinDate = $request->query('checkin_date');
            $bookingType = $request->query('booking_type', 'special-event');
            
            // Get units for special events - IGNORE unitStatus
            $units = Unit::where('for_special_events', true)
                ->select('unitID', 'unitName', 'unitType', 'capacity', 'unitRatePrice', 'blockStartDate', 'blockEndDate', 'unitStatus')
                ->get();

            // If dates are provided, filter out units that are blocked or already booked
            if ($checkinDate) {
                $availabilityCheck = $this->checkSpecialEventAvailability($checkinDate);
                
                $availableUnits = $units->filter(function($unit) use ($checkinDate, $availabilityCheck) {
                    // Check if unit is blocked based on block dates ONLY
                    $isUnitBlocked = $this->isUnitBlockedForSpecialEvent($unit, $checkinDate);
                    if ($isUnitBlocked) {
                        return false;
                    }

                    // If there's a date conflict (ANY bookings), no units available
                    if (!$availabilityCheck['available']) {
                        return false;
                    }

                    // Check if this specific unit is available (not booked)
                    $isUnitAvailable = $this->checkUnitAvailability($unit->unitID, $checkinDate);
                    return $isUnitAvailable;
                });
                
                return response()->json([
                    'success' => true,
                    'data' => $availableUnits->values(),
                    'date_availability' => $availabilityCheck
                ]);
            }

            // Kung walang checkin date, ipakita lahat ng units na for special events
            return response()->json([
                'success' => true,
                'data' => $units
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching special event units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching special event units: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get payments for a special event
    public function getPayments($id)
    {
        try {
            $payments = Payment::where('bookingID', $id)
                ->orderBy('paymentDate', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $payments->map(function($payment) {
                    // ✅ STANDARDIZED DATE FORMAT
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
            Log::error('Error fetching payments for special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payments'
            ], 500);
        }
    }

    // Add payment to special event
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

            $booking = Booking::where('bookingType', 'special-event')->findOrFail($id);
            
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

            // Send payment receipt email
            try {
                // You can create a separate PaymentReceiptEmail class if needed
                Mail::to($booking->cart->user->email)
                    ->send(new SpecialBookingCreatedEmail($booking->fresh()->load('cart.user', 'cart.cartItems.unit')));
                Log::info("Payment receipt email sent to {$booking->cart->user->email} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send payment receipt email for booking #{$booking->bookingID}: " . $e->getMessage());
                // Continue even if email fails
            }

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
            Log::error('Error adding payment to special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Process refund for special event
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

            $booking = Booking::where('bookingType', 'special-event')->findOrFail($id);
            
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

            // Send refund notification email
            try {
                $refundMethod = $validated['refund_method'];
                Mail::to($booking->cart->user->email)
                    ->send(new SpecialBookingCancelledEmail($booking, $validated['refund_amount'], $refundMethod));
                Log::info("Refund notification email sent to {$booking->cart->user->email} for booking #{$booking->bookingID}");
            } catch (\Exception $e) {
                Log::error("Failed to send refund notification email for booking #{$booking->bookingID}: " . $e->getMessage());
                // Continue even if email fails
            }

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
            Log::error('Error processing refund for special event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * NEW METHOD: Check date availability for special events
     */
    public function checkDateAvailability(Request $request)
    {
        try {
            $request->validate([
                'checkin_date' => 'required|date',
                'unit_id' => 'nullable|exists:units,unitID',
                'exclude_booking_id' => 'nullable|exists:bookings,bookingID'
            ]);

            $checkinDate = $request->checkin_date;
            $unitId = $request->unit_id;
            $excludeBookingId = $request->exclude_booking_id;

            $availabilityCheck = $this->checkSpecialEventAvailability($checkinDate, $unitId, $excludeBookingId);

            return response()->json([
                'success' => true,
                'available' => $availabilityCheck['available'],
                'has_conflict' => !$availabilityCheck['available'],
                'message' => $availabilityCheck['message'],
                'conflict_type' => $availabilityCheck['conflict_type'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking date availability: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking date availability'
            ], 500);
        }
    }
}