<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EntranceFeeController;
use App\Http\Controllers\SpecialEventsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CottageController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Auth;

// Guest routes
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
Route::get('/', function () {
    if (Auth::check() && in_array(Auth::user()->role, ['manager', 'staff'])) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');

// Customer booking routes
Route::get('/booking', function () {
    return view('customerFolder.booking.roomBooking');
})->name('roomBooking');

Route::get('/cottage-booking', function () {
    return view('customerFolder.booking.cottageBooking');
})->name('cottageBooking');

Route::get('/booking-page', function () {
    return view('customerFolder.booking.booking');
})->name('booking.page');

Route::get('/cart', function () {
    return view('customerFolder.cart.cart');
})->name('cart');

Route::get('/about', function () {
    return view('customerFolder.about.about');
})->name('about');

// ========== ✅ GCASH PAYMENT ROUTES (UPDATED) ==========
Route::middleware(['auth'])->group(function () {
    Route::post('/gcash/process-payment', [CustomerBookingController::class, 'processGCashPayment'])
        ->name('customer.payment.process');
    Route::get('/payment/gcash/success', [CustomerBookingController::class, 'gcashPaymentSuccess'])
        ->name('customer.payment.success');
    Route::get('/payment/gcash/verify', [CustomerBookingController::class, 'verifyGCashPayment'])
        ->name('customer.payment.verify');
    Route::get('/payment/gcash/failed', [CustomerBookingController::class, 'gcashPaymentFailed'])
        ->name('customer.payment.failed');
});


// ========== CART ROUTES USING CART CONTROLLER ==========
Route::middleware(['auth', 'verified', 'role:guest,staff,admin,manager'])->group(function () {
    // Main cart API endpoints
    Route::get('/api/cart/items', [CartController::class, 'getActiveCartItems'])->name('api.cart.items');
    Route::get('/api/cart/all-items', [CartController::class, 'getAllCartItems'])->name('api.cart.all-items');
    Route::get('/api/cart/count', [CartController::class, 'getCartItemCount'])->name('api.cart.count');
    Route::delete('/api/cart/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('api.cart.remove');
    Route::delete('/api/cart/clear', [CartController::class, 'clearCart'])->name('api.cart.clear');
    Route::put('/api/cart/update', [CartController::class, 'updateCart'])->name('api.cart.update');
    Route::post('/api/cart/create-new', [CartController::class, 'createNewCart'])->name('api.cart.create-new');
    
    // IMPORTANT: Cart validation before checkout
    Route::post('/api/cart/validate-before-checkout', [CartController::class, 'validateCartBeforeCheckout'])->name('api.cart.validate-before-checkout');
    Route::post('/api/cart/pre-validate', [CustomerBookingController::class, 'preValidateCart'])->name('api.cart.pre-validate');
});

// Room & Cottage specific add to cart routes
Route::post('/api/cart/add', [RoomController::class, 'addToCart'])->name('api.cart.add');
Route::post('/api/cart/add-cottage', [CottageController::class, 'addToCart'])->name('api.cart.add-cottage');

// ========== END CART ROUTES ==========

// API routes for room booking functionality
Route::get('/api/available-rooms', [RoomController::class, 'getAvailableRooms'])->name('api.available-rooms');
Route::get('/api/all-rooms', [RoomController::class, 'getAllRooms'])->name('api.all-rooms');

// API routes for cottage booking functionality
Route::get('/api/available-cottages', [CottageController::class, 'getAvailableCottages'])->name('api.available-cottages');
Route::get('/api/all-cottages', [CottageController::class, 'getAllCottages'])->name('api.all-cottages');

// Entrance Fee API Route
Route::get('/api/entrance-fee', [CottageController::class, 'getEntranceFee'])->name('api.entrance-fee');

// Customer booking submission
Route::post('/api/customer-bookings', [CustomerBookingController::class, 'store'])->name('api.customer.bookings.store');

// Customer booking history routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', [CustomerBookingController::class, 'bookingsPage'])->name('customer.bookings');
    Route::get('/api/my-bookings', [CustomerBookingController::class, 'getCustomerBookings'])->name('api.customer.bookings');
    Route::get('/api/my-bookings/{id}', [CustomerBookingController::class, 'show'])->name('api.customer.bookings.show');
    Route::post('/api/my-bookings/{id}/cancel', [CustomerBookingController::class, 'cancelBooking'])->name('api.customer.bookings.cancel');
});

// Date availability check routes
Route::get('/api/check-date-availability', [RoomController::class, 'checkDateAvailability'])->name('api.check-date-availability');
Route::get('/api/check-cottage-date-availability', [CottageController::class, 'checkDateAvailability'])->name('api.check-cottage-date-availability');

// Special Event Availability Check
Route::get('/api/check-special-event-availability', [BookingController::class, 'checkSpecialEventAvailability'])->name('api.check-special-event-availability');

// ========== ADMIN ROUTES WITH DASHBOARD ==========
Route::middleware(['auth', 'role:manager,staff'])->prefix('admin')->group(function () {
    
    // DASHBOARD ROUTES
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('admin.dashboard.stats');
    Route::get('/dashboard/occupancy-rate', [DashboardController::class, 'getOccupancyRate'])->name('admin.dashboard.occupancy');

    Route::get('/reservation', function () {
        return view('adminFolder.reservation.reservation');
    })->name('admin.reservation');
    
    // Rooms & Cottages routes
    Route::get('/rooms-cottages', [UnitsController::class, 'index'])->name('admin.rooms-cottages');
    
    // Block dates route - accessible to both manager and staff
    Route::post('/units/block-dates', [UnitsController::class, 'blockDates'])->name('admin.units.block-dates');
    Route::post('/admin/units/unblock-dates', [UnitsController::class, 'unblockDates'])->name('admin.units.unblock-dates');
    
    // Manager-only CRUD operations
    Route::middleware(['role:manager'])->group(function () {
        Route::post('/units', [UnitsController::class, 'store'])->name('admin.units.store');
        Route::get('/units/{id}/edit', [UnitsController::class, 'edit'])->name('admin.units.edit');
        Route::put('/units/{id}', [UnitsController::class, 'update'])->name('admin.units.update');
        Route::delete('/units/{id}', [UnitsController::class, 'destroy'])->name('admin.units.destroy') ->middleware('role:manager');
        Route::post('/units/{id}/delete-image', [UnitsController::class, 'deleteImage'])->name('admin.units.delete-image');
        Route::get('/units/{id}/booking-status', [UnitsController::class, 'checkBookingStatus'])->name('admin.units.booking-status');
    
    });

    // Booking Routes (Admin)
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::put('/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('/units/available', [BookingController::class, 'getAvailableUnits'])->name('bookings.units.available');
        Route::get('/check-availability', [BookingController::class, 'checkUnitAvailability'])->name('bookings.check-availability');
        Route::get('/{id}/payments', [BookingController::class, 'getPayments'])->name('bookings.payments');
        Route::get('/{id}/payment-summary', [BookingController::class, 'getPaymentSummary'])->name('bookings.payment-summary');
        Route::post('/{id}/payments', [BookingController::class, 'addPayment'])->name('bookings.payments.store');
        Route::post('/{id}/refund', [BookingController::class, 'processRefund'])->name('bookings.refund');
        
        // EXISTING ROUTES FOR ACTIVE CARTS MANAGEMENT IN ADMIN
        Route::get('/active-carts/list', [BookingController::class, 'getActiveCarts'])->name('bookings.active-carts.list');
        Route::put('/{id}/deactivate-cart', [BookingController::class, 'deactivateCart'])->name('bookings.deactivate-cart');
    });

    // Special Events Routes
    Route::prefix('special-events')->group(function () {
        Route::get('/', [SpecialEventsController::class, 'index'])->name('admin.special-events.index');
        Route::post('/', [SpecialEventsController::class, 'store'])->name('admin.special-events.store');
        Route::get('/{id}', [SpecialEventsController::class, 'show'])->name('admin.special-events.show');
        Route::put('/{id}', [SpecialEventsController::class, 'update'])->name('admin.special-events.update');
        Route::delete('/{id}', [SpecialEventsController::class, 'destroy'])->name('admin.special-events.destroy');
        Route::get('/units/available', [SpecialEventsController::class, 'getAvailableUnits'])->name('admin.special-events.units.available');
        Route::get('/{id}/payments', [SpecialEventsController::class, 'getPayments'])->name('admin.special-events.payments');
        Route::post('/{id}/payments', [SpecialEventsController::class, 'addPayment'])->name('admin.special-events.payments.store');
        Route::post('/{id}/refund', [SpecialEventsController::class, 'processRefund'])->name('admin.special-events.refund');
    });

    // Special Events Page
    Route::get('/special-events-page', function () {
        return view('adminFolder.special-events.special-events');
    })->name('admin.special-events');
    
    // History
    Route::get('/history', function () {
        return view('adminFolder.history.history');
    })->name('admin.history');
    
    // History Data
    Route::get('/history/data', [HistoryController::class, 'getHistory'])->name('admin.history.data');
    
    // Pricing Management - Accessible to both manager and staff (but only manager can edit)
    Route::get('/pricing', [EntranceFeeController::class, 'index'])->name('admin.pricing');
    
    // Staff Management Routes - Manager only
    Route::middleware(['role:manager'])->prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('admin.staff.index');
        Route::get('/create', [StaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit');
        Route::put('/{id}', [StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');
        
        // Email verification routes
        Route::post('/{id}/verify-email', [StaffController::class, 'verifyEmail'])->name('admin.staff.verify-email');
        Route::post('/{id}/unverify-email', [StaffController::class, 'unverifyEmail'])->name('admin.staff.unverify-email');
    });
});

// Pricing Management Routes - Manager only
Route::middleware(['auth', 'role:manager'])->prefix('admin')->group(function () {
    Route::post('/pricing/entrance-fee', [EntranceFeeController::class, 'updateOrCreate'])->name('admin.pricing.entrance-fee.update');
    Route::delete('/pricing/entrance-fee', [EntranceFeeController::class, 'deactivate'])->name('admin.pricing.entrance-fee.deactivate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route for my Virtual Tour
Route::get('/virtual-tour/index', function () {
    return response()->file(public_path('virtual-tour/index.html'));
})->name('virtual-tour.index');

require __DIR__.'/auth.php';