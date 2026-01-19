<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Unit;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('adminFolder.adminDashboard');
    }

    public function getStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Total Bookings Statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('bookingStatus', 'pending')->count();
        $confirmedBookings = Booking::where('bookingStatus', 'confirmed')->count();
        $completedBookings = Booking::where('bookingStatus', 'completed')->count();
        
        // Today's Check-ins and Check-outs
        $todayCheckIns = Booking::whereHas('cart', function($query) use ($today) {
            $query->where('checkInDate', $today);
        })->where('bookingStatus', 'confirmed')->count();
        
        $todayCheckOuts = Booking::whereHas('cart', function($query) use ($today) {
            $query->where('checkOutDate', $today);
        })->where('bookingStatus', 'confirmed')->count();
        
        // Units Statistics
        $totalUnits = Unit::count();
        $availableUnits = Unit::where('unitStatus', 'available')->count();
        $maintenanceUnits = Unit::where('unitStatus', 'maintenance')->count();
        $blockedUnits = Unit::where('unitStatus', 'blocked')->count();
        
        // Units by Type
        $roomsCount = Unit::where('unitType', 'room')->count();
        $cottagesCount = Unit::where('unitType', 'cottage')->count();
        $specialUnitsCount = Unit::where('unitType', 'special')->count();
        
        // Revenue Statistics
        $totalRevenue = Payment::where('paymentStatus', 'completed')->sum('amountPaid');
        $monthlyRevenue = Payment::where('paymentStatus', 'completed')
            ->whereDate('paymentDate', '>=', $thisMonth)
            ->sum('amountPaid');
        
        $todayRevenue = Payment::where('paymentStatus', 'completed')
            ->whereDate('paymentDate', $today)
            ->sum('amountPaid');
        
        // Pending Payments
        $pendingPayments = Payment::where('paymentStatus', 'pending')->count();
        $pendingPaymentAmount = Payment::where('paymentStatus', 'pending')->sum('amountPaid');
        
        // Guest Statistics
        $totalGuests = User::where('role', 'guest')->count();
        $staffCount = User::where('role', 'staff')->count();
        $managerCount = User::where('role', 'manager')->count();
        
        // Booking Types Distribution
        $dayUseBookings = Booking::where('bookingType', 'day-use')->count();
        $overnightBookings = Booking::where('bookingType', 'overnight')->count();
        $specialEventBookings = Booking::where('bookingType', 'special-event')->count();
        
        // Recent Bookings (Last 5)
        $recentBookings = Booking::with(['cart.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->bookingID,
                    'guest_name' => $booking->cart->user->name ?? 'N/A',
                    'booking_type' => $booking->bookingType,
                    'status' => $booking->bookingStatus,
                    'total_price' => $booking->totalPrice,
                    'created_at' => $booking->created_at->format('M d, Y H:i'),
                ];
            });

        // Upcoming Check-ins (Next 7 days)
        $upcomingCheckIns = Booking::with(['cart.user', 'cart.cartItems.unit'])
            ->whereHas('cart', function($query) {
                $query->whereBetween('checkInDate', [Carbon::today(), Carbon::today()->addDays(7)]);
            })
            ->where('bookingStatus', 'confirmed')
            ->orderBy('created_at')
            ->limit(5)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->bookingID,
                    'guest_name' => $booking->cart->user->name ?? 'N/A',
                    'check_in_date' => Carbon::parse($booking->cart->checkInDate)->format('M d, Y'),
                    'check_out_date' => Carbon::parse($booking->cart->checkOutDate)->format('M d, Y'),
                    'units' => $booking->cart->cartItems->pluck('unit.unitName')->implode(', '),
                    'num_guests' => $booking->cart->numGuests,
                ];
            });

        // Monthly Revenue Chart Data (Last 6 months)
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::where('paymentStatus', 'completed')
                ->whereYear('paymentDate', $month->year)
                ->whereMonth('paymentDate', $month->month)
                ->sum('amountPaid');
            
            $monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => (float) $revenue
            ];
        }

        // Booking Status Distribution
        $bookingStatusData = [
            ['status' => 'Pending', 'count' => $pendingBookings],
            ['status' => 'Confirmed', 'count' => $confirmedBookings],
            ['status' => 'Completed', 'count' => $completedBookings],
            ['status' => 'Cancelled', 'count' => Booking::where('bookingStatus', 'cancelled')->count()],
        ];

        return response()->json([
            'summary' => [
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'confirmed_bookings' => $confirmedBookings,
                'completed_bookings' => $completedBookings,
                'today_check_ins' => $todayCheckIns,
                'today_check_outs' => $todayCheckOuts,
            ],
            'units' => [
                'total' => $totalUnits,
                'available' => $availableUnits,
                'maintenance' => $maintenanceUnits,
                'blocked' => $blockedUnits,
                'rooms' => $roomsCount,
                'cottages' => $cottagesCount,
                'special' => $specialUnitsCount,
            ],
            'revenue' => [
                'total' => (float) $totalRevenue,
                'monthly' => (float) $monthlyRevenue,
                'today' => (float) $todayRevenue,
                'pending_count' => $pendingPayments,
                'pending_amount' => (float) $pendingPaymentAmount,
            ],
            'users' => [
                'guests' => $totalGuests,
                'staff' => $staffCount,
                'managers' => $managerCount,
            ],
            'booking_types' => [
                'day_use' => $dayUseBookings,
                'overnight' => $overnightBookings,
                'special_event' => $specialEventBookings,
            ],
            'recent_bookings' => $recentBookings,
            'upcoming_check_ins' => $upcomingCheckIns,
            'monthly_revenue_chart' => $monthlyRevenueData,
            'booking_status_chart' => $bookingStatusData,
        ]);
    }

    public function getOccupancyRate(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $totalUnits = Unit::where('unitStatus', 'available')->count();
        $totalDays = Carbon::parse($startDate)->diffInDays($endDate) + 1;
        $totalPossibleBookings = $totalUnits * $totalDays;

        $occupiedDays = DB::table('cart_items')
            ->join('carts', 'cart_items.cartID', '=', 'carts.cartID')
            ->join('bookings', 'carts.cartID', '=', 'bookings.cartID')
            ->whereBetween('carts.checkInDate', [$startDate, $endDate])
            ->where('bookings.bookingStatus', 'confirmed')
            ->sum('carts.daysCount');

        $occupancyRate = $totalPossibleBookings > 0 
            ? ($occupiedDays / $totalPossibleBookings) * 100 
            : 0;

        return response()->json([
            'occupancy_rate' => round($occupancyRate, 2),
            'total_units' => $totalUnits,
            'occupied_days' => $occupiedDays,
            'total_possible_bookings' => $totalPossibleBookings,
        ]);
    }
}