<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index()
    {
        return view('adminFolder.history.history');
    }

    public function getHistory(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        // Build query for completed and cancelled bookings
        $query = DB::table('bookings')
            ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
            ->join('users', 'carts.user_id', '=', 'users.userID')
            ->leftJoin('cart_items', 'carts.cartID', '=', 'cart_items.cartID')
            ->leftJoin('units', 'cart_items.unitID', '=', 'units.unitID')
            ->select(
                'bookings.bookingID',
                'bookings.bookingStatus',
                'bookings.bookingType',
                'carts.numGuests', // CHANGED: Moved from bookings to carts
                'bookings.totalPrice',
                'bookings.specialRequirements',
                'bookings.eventType',
                'bookings.cancelledAt',
                'bookings.cancellationReason',
                'bookings.created_at',
                'carts.checkInDate',
                'carts.checkOutDate',
                'users.name as guest_name',
                'users.email',
                'users.phoneNumber as phone',
                DB::raw('GROUP_CONCAT(DISTINCT units.unitName SEPARATOR ", ") as units')
            )
            ->whereIn('bookings.bookingStatus', ['completed', 'cancelled'])
            ->groupBy(
                'bookings.bookingID',
                'bookings.bookingStatus', 
                'bookings.bookingType',
                'carts.numGuests', // CHANGED: Moved from bookings to carts
                'bookings.totalPrice',
                'bookings.specialRequirements',
                'bookings.eventType',
                'bookings.cancelledAt',
                'bookings.cancellationReason',
                'bookings.created_at',
                'carts.checkInDate',
                'carts.checkOutDate',
                'users.name',
                'users.email',
                'users.phoneNumber'
            );

        // Apply status filter
        if ($status !== 'all') {
            $query->where('bookings.bookingStatus', $status);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.phoneNumber', 'like', "%{$search}%")
                  ->orWhere('bookings.eventType', 'like', "%{$search}%") // ADDED: Search by event type
                  ->orWhere('units.unitName', 'like', "%{$search}%"); // ADDED: Search by unit name
            });
        }

        // Get total count for pagination
        $total = $query->count();

        // Apply pagination and ordering
        $bookings = $query->orderBy('bookings.created_at', 'desc')
                         ->skip(($page - 1) * $perPage)
                         ->take($perPage)
                         ->get();

        // Calculate payment summaries
        $bookingsWithPayments = $bookings->map(function($booking) {
            $payments = DB::table('payments')
                ->where('bookingID', $booking->bookingID)
                ->get();

            $totalPaid = $payments->where('paymentStatus', 'completed')
                                ->where('paymentType', '!=', 'refund')
                                ->sum('amountPaid');
            
            $totalRefunded = $payments->where('paymentType', 'refund')
                                    ->sum('amountPaid');
            
            $netPaid = $totalPaid - $totalRefunded;

            return [
                'bookingID' => $booking->bookingID,
                'guest_name' => $booking->guest_name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'booking_status' => $booking->bookingStatus,
                'booking_type' => $booking->bookingType,
                'checkin_date' => $booking->checkInDate,
                'checkout_date' => $booking->checkOutDate,
                'num_guests' => $booking->numGuests, // CHANGED: Now comes from carts table
                'total_price' => $booking->totalPrice,
                'units' => $booking->units,
                'special_requirements' => $booking->specialRequirements,
                'event_type' => $booking->eventType,
                'cancelled_at' => $booking->cancelledAt,
                'cancellation_reason' => $booking->cancellationReason,
                'created_at' => $booking->created_at,
                'total_paid' => $totalPaid,
                'total_refunded' => $totalRefunded,
                'net_paid' => $netPaid,
                'remaining_balance' => max(0, $booking->totalPrice - $netPaid)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $bookingsWithPayments,
            'total' => $total,
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    /**
     * Get detailed history for a specific booking
     */
    public function show($id)
    {
        try {
            // Get booking details
            $booking = DB::table('bookings')
                ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
                ->join('users', 'carts.user_id', '=', 'users.userID')
                ->leftJoin('cart_items', 'carts.cartID', '=', 'cart_items.cartID')
                ->leftJoin('units', 'cart_items.unitID', '=', 'units.unitID')
                ->select(
                    'bookings.bookingID',
                    'bookings.bookingStatus',
                    'bookings.bookingType',
                    'carts.numGuests', // CHANGED: Moved from bookings to carts
                    'bookings.totalPrice',
                    'bookings.specialRequirements',
                    'bookings.eventType',
                    'bookings.cancelledAt',
                    'bookings.cancellationReason',
                    'bookings.created_at',
                    'carts.checkInDate',
                    'carts.checkOutDate',
                    'carts.daysCount',
                    'users.name as guest_name',
                    'users.email',
                    'users.phoneNumber as phone',
                    DB::raw('GROUP_CONCAT(DISTINCT units.unitName SEPARATOR ", ") as units'),
                    DB::raw('GROUP_CONCAT(DISTINCT units.unitID SEPARATOR ", ") as unit_ids')
                )
                ->where('bookings.bookingID', $id)
                ->whereIn('bookings.bookingStatus', ['completed', 'cancelled'])
                ->groupBy(
                    'bookings.bookingID',
                    'bookings.bookingStatus', 
                    'bookings.bookingType',
                    'carts.numGuests', // CHANGED: Moved from bookings to carts
                    'bookings.totalPrice',
                    'bookings.specialRequirements',
                    'bookings.eventType',
                    'bookings.cancelledAt',
                    'bookings.cancellationReason',
                    'bookings.created_at',
                    'carts.checkInDate',
                    'carts.checkOutDate',
                    'carts.daysCount',
                    'users.name',
                    'users.email',
                    'users.phoneNumber'
                )
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'History record not found'
                ], 404);
            }

            // Get payment details
            $payments = DB::table('payments')
                ->where('bookingID', $id)
                ->orderBy('paymentDate', 'desc')
                ->get();

            $totalPaid = $payments->where('paymentStatus', 'completed')
                                ->where('paymentType', '!=', 'refund')
                                ->sum('amountPaid');
            
            $totalRefunded = $payments->where('paymentType', 'refund')
                                    ->sum('amountPaid');
            
            $netPaid = $totalPaid - $totalRefunded;

            $formattedPayments = $payments->map(function($payment) {
                return [
                    'paymentID' => $payment->paymentID,
                    'paymentReference' => $payment->paymentReference,
                    'paymentMethod' => $payment->paymentMethod,
                    'paymentType' => $payment->paymentType,
                    'amountPaid' => $payment->amountPaid,
                    'remainingBalance' => $payment->remainingBalance,
                    'paymentDate' => $payment->paymentDate,
                    'paymentStatus' => $payment->paymentStatus,
                    'isRefunded' => $payment->isRefunded,
                    'refundDate' => $payment->refundDate,
                    'refundAmount' => $payment->refundAmount,
                    'refundReason' => $payment->refundReason
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'bookingID' => $booking->bookingID,
                    'guest_name' => $booking->guest_name,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                    'booking_status' => $booking->bookingStatus,
                    'booking_type' => $booking->bookingType,
                    'checkin_date' => $booking->checkInDate,
                    'checkout_date' => $booking->checkOutDate,
                    'days_count' => $booking->daysCount,
                    'num_guests' => $booking->numGuests, // CHANGED: Now comes from carts table
                    'total_price' => $booking->totalPrice,
                    'units' => $booking->units,
                    'unit_ids' => $booking->unit_ids,
                    'special_requirements' => $booking->specialRequirements,
                    'event_type' => $booking->eventType,
                    'cancelled_at' => $booking->cancelledAt,
                    'cancellation_reason' => $booking->cancellationReason,
                    'created_at' => $booking->created_at,
                    'total_paid' => $totalPaid,
                    'total_refunded' => $totalRefunded,
                    'net_paid' => $netPaid,
                    'remaining_balance' => max(0, $booking->totalPrice - $netPaid),
                    'payments' => $formattedPayments
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching history details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export history data
     */
    public function exportHistory(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $search = $request->get('search', '');

            // Build the same query as getHistory but without pagination
            $query = DB::table('bookings')
                ->join('carts', 'bookings.cartID', '=', 'carts.cartID')
                ->join('users', 'carts.user_id', '=', 'users.userID')
                ->leftJoin('cart_items', 'carts.cartID', '=', 'cart_items.cartID')
                ->leftJoin('units', 'cart_items.unitID', '=', 'units.unitID')
                ->select(
                    'bookings.bookingID',
                    'bookings.bookingStatus',
                    'bookings.bookingType',
                    'carts.numGuests', // CHANGED: Moved from bookings to carts
                    'bookings.totalPrice',
                    'bookings.specialRequirements',
                    'bookings.eventType',
                    'bookings.cancelledAt',
                    'bookings.cancellationReason',
                    'bookings.created_at',
                    'carts.checkInDate',
                    'carts.checkOutDate',
                    'users.name as guest_name',
                    'users.email',
                    'users.phoneNumber as phone',
                    DB::raw('GROUP_CONCAT(DISTINCT units.unitName SEPARATOR ", ") as units')
                )
                ->whereIn('bookings.bookingStatus', ['completed', 'cancelled'])
                ->groupBy(
                    'bookings.bookingID',
                    'bookings.bookingStatus', 
                    'bookings.bookingType',
                    'carts.numGuests', // CHANGED: Moved from bookings to carts
                    'bookings.totalPrice',
                    'bookings.specialRequirements',
                    'bookings.eventType',
                    'bookings.cancelledAt',
                    'bookings.cancellationReason',
                    'bookings.created_at',
                    'carts.checkInDate',
                    'carts.checkOutDate',
                    'users.name',
                    'users.email',
                    'users.phoneNumber'
                );

            // Apply status filter
            if ($status !== 'all') {
                $query->where('bookings.bookingStatus', $status);
            }

            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%")
                      ->orWhere('users.phoneNumber', 'like', "%{$search}%");
                });
            }

            $bookings = $query->orderBy('bookings.created_at', 'desc')->get();

            // Format data for export
            $exportData = $bookings->map(function($booking) {
                $payments = DB::table('payments')
                    ->where('bookingID', $booking->bookingID)
                    ->get();

                $totalPaid = $payments->where('paymentStatus', 'completed')
                                    ->where('paymentType', '!=', 'refund')
                                    ->sum('amountPaid');
                
                $totalRefunded = $payments->where('paymentType', 'refund')
                                        ->sum('amountPaid');
                
                $netPaid = $totalPaid - $totalRefunded;

                return [
                    'Booking ID' => $booking->bookingID,
                    'Guest Name' => $booking->guest_name,
                    'Email' => $booking->email,
                    'Phone' => $booking->phone,
                    'Status' => ucfirst($booking->bookingStatus),
                    'Booking Type' => ucfirst(str_replace('-', ' ', $booking->bookingType)),
                    'Check-in Date' => $booking->checkInDate,
                    'Check-out Date' => $booking->checkOutDate,
                    'Number of Guests' => $booking->numGuests, // CHANGED: Now comes from carts table
                    'Total Price' => '₱' . number_format($booking->totalPrice, 2),
                    'Units' => $booking->units,
                    'Event Type' => $booking->eventType ?: 'Normal Booking',
                    'Total Paid' => '₱' . number_format($totalPaid, 2),
                    'Total Refunded' => '₱' . number_format($totalRefunded, 2),
                    'Net Paid' => '₱' . number_format($netPaid, 2),
                    'Remaining Balance' => '₱' . number_format(max(0, $booking->totalPrice - $netPaid), 2),
                    'Cancellation Reason' => $booking->cancellationReason ?: 'N/A',
                    'Cancelled At' => $booking->cancelledAt ?: 'N/A',
                    'Created Date' => $booking->created_at
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'filename' => 'booking_history_' . date('Y-m-d_H-i-s') . '.csv'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting history: ' . $e->getMessage()
            ], 500);
        }
    }
}