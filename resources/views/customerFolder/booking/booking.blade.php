<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Booking - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-black: #000000;
            --primary-yellow: #FFD700;
            --secondary-yellow: #FFA500;
            --gcash-blue: #007DFF;
            --light-bg: #FFFBF0;
            --card-bg: #FFFFFF;
            --text-dark: #1F2937;
            --text-medium: #6B7280;
            --text-light: #9CA3AF;
            --border-color: #E5E7EB;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }
        
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 300px);
        }

        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--text-dark), #4B5563);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 2px;
        }

        .booking-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .form-label i {
            color: var(--primary-yellow);
            margin-right: 8px;
            width: 20px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: white;
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
        }

        .form-input:disabled {
            background-color: #f8f9fa;
            color: var(--text-light);
            cursor: not-allowed;
            opacity: 0.8;
        }

        .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            background-color: white;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Poppins', sans-serif;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
        }

        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            background-color: white;
            color: var(--text-dark);
            resize: vertical;
            min-height: 120px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-btn:disabled {
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            cursor: not-allowed;
            transform: none !important;
            opacity: 0.6;
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #047857, #065f46);
        }

        .btn-gcash {
            background: linear-gradient(135deg, #007DFF, #0062CC);
            color: white;
        }

        .btn-gcash:hover:not(:disabled) {
            background: linear-gradient(135deg, #0062CC, #004C99);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
        }

        .payment-option-btn {
            padding: 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .payment-option-btn:hover {
            border-color: var(--gcash-blue);
            background: rgba(0, 125, 255, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 125, 255, 0.2);
        }

        .payment-option-btn.selected {
            border-color: var(--gcash-blue);
            background: rgba(0, 125, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(0, 125, 255, 0.2);
        }

        .payment-option-btn i {
            font-size: 2rem;
            color: var(--gcash-blue);
        }

        .alert {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #1e40af;
            border-color: #60a5fa;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-color: #34d399;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-color: #f87171;
        }

        .downpayment-info {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
            border: 1px solid rgba(255, 215, 0, 0.3);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .downpayment-info p {
            color: var(--text-dark);
            font-size: 0.9rem;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .downpayment-info i {
            color: var(--primary-yellow);
            font-size: 1.1rem;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 215, 0, 0.2);
            border-top-color: var(--primary-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.25rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .type-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .type-badge.room {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
            color: #1e40af;
            border-color: rgba(59, 130, 246, 0.2);
        }

        .type-badge.cottage {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(21, 128, 61, 0.1));
            color: #166534;
            border-color: rgba(34, 197, 94, 0.2);
        }

        .total-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            text-align: right;
            padding-top: 1.5rem;
            border-top: 2px dashed #e5e7eb;
        }

        .total-price .price-amount {
            color: #059669;
            font-size: 2rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-top: 70px;
            }
            
            .section-title {
                font-size: 2.25rem;
                margin-bottom: 2rem;
            }
            
            .booking-card {
                padding: 1.75rem;
            }
        }
    </style>
</head>
<body>
    @include('customerFolder.partials.navbar')
    
    <div class="main-content">
        <div class="booking-container">
            <h1 class="section-title">Complete Your Booking</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Booking Form -->
                <div>
                    <div class="booking-card">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Guest Information</h2>
                        
                        <form id="bookingForm">
                            @csrf
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" name="full_name" class="form-input" required readonly>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i>
                                        Email Address
                                    </label>
                                    <input type="email" name="email" class="form-input" required readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i>
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" class="form-input" required readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Booking Type
                                </label>
                                <select name="booking_type" class="form-select" id="booking_type" required disabled>
                                    <!-- Will be populated by JavaScript -->
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-star"></i>
                                    Event Type
                                </label>
                                <input type="text" name="event_type" class="form-input" value="normal-booking" readonly>
                                <p class="text-xs text-gray-500 mt-2 ml-6">Normal booking for accommodation</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-comment-dots"></i>
                                    Special Requirements
                                </label>
                                <textarea name="special_requirements" class="form-textarea" placeholder="Any special requests or requirements..."></textarea>
                            </div>
                            
                            <!-- Payment Method Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-credit-card"></i>
                                    Payment Method
                                </label>
                                <select name="payment_method" class="form-select" id="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>

                            <!-- Payment Amount Info -->
                            <div id="payment-amount-info" class="downpayment-info" style="display: none;">
                                <p>
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Total Amount:</strong> <span id="total-amount-display">₱0.00</span>
                                </p>
                                <p class="mt-2">
                                    <strong>Minimum Downpayment (50%):</strong> <span id="min-payment-display">₱0.00</span>
                                </p>
                            </div>

                            <!-- Cash Payment Section -->
                            <div id="cash-payment-section" class="space-y-4 mt-8" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Cash Payment:</strong> Please prepare the payment upon arrival or at the resort.
                                </div>
                                <button type="button" onclick="submitCashBooking()" class="action-btn btn-success" id="cash-booking-btn">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Complete Booking (Cash Payment)
                                </button>
                            </div>

                            <!-- GCash Payment Section -->
                            <div id="gcash-payment-section" class="space-y-4 mt-8" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-mobile-alt mr-2"></i>
                                    <strong>GCash Payment:</strong> You will be redirected to GCash to complete your payment securely.
                                </div>
                                
                                <!-- GCash Amount Selection -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Select Payment Amount
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <button type="button" onclick="selectPaymentAmount('downpayment')" 
                                                class="payment-option-btn" id="downpayment-btn">
                                            <i class="fas fa-percent"></i>
                                            <div class="mt-2">
                                                <div class="text-sm text-gray-600">Downpayment (50%)</div>
                                                <div class="text-xl font-bold" id="downpayment-amount">₱0.00</div>
                                            </div>
                                        </button>
                                        <button type="button" onclick="selectPaymentAmount('full')" 
                                                class="payment-option-btn" id="full-payment-btn">
                                            <i class="fas fa-check-circle"></i>
                                            <div class="mt-2">
                                                <div class="text-sm text-gray-600">Full Payment</div>
                                                <div class="text-xl font-bold" id="full-amount">₱0.00</div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                
                                <button type="button" onclick="submitGCashBooking()" class="action-btn btn-gcash" id="gcash-booking-btn" disabled>
                                    <i class="fab fa-google-pay"></i>
                                    Pay with GCash
                                </button>
                            </div>

                            <!-- Cancel Button -->
                            <div class="mt-4">
                                <button type="button" onclick="cancelBooking()" class="action-btn btn-danger">
                                    <i class="fas fa-times"></i>
                                    Cancel Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Booking Summary -->
                <div>
                    <div class="booking-card">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Booking Summary</h2>
                        
                        <div id="booking-summary">
                            <div class="text-center py-12" id="summary-loading">
                                <div class="loading-spinner"></div>
                                <p class="text-gray-600">Loading booking summary...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('customerFolder.partials.footer')

    <script>
        let bookingTotal = 0;
        let selectedGCashAmount = null;
        let currentBookingId = null;
        let daysCount = 1;
        let numGuests = 1;
        let entranceFeeAmount = 0;
        let hasActiveEntranceFee = false;

        document.addEventListener('DOMContentLoaded', function() {
            Promise.all([
                loadEntranceFee(),
                loadBookingSummary()
            ]).then(() => {
                prefillUserInfo();
            });
        });

        async function loadEntranceFee() {
            try {
                const response = await fetch('/api/entrance-fee', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.entrance_fee) {
                        entranceFeeAmount = parseFloat(data.entrance_fee.amount);
                        hasActiveEntranceFee = true;
                    }
                }
            } catch (error) {
                console.error('Error loading entrance fee:', error);
            }
        }

        function loadBookingSummary() {
            const summaryContainer = document.getElementById('booking-summary');
            const loading = document.getElementById('summary-loading');
            
            fetch('/api/cart/items')
                .then(response => response.json())
                .then(data => {
                    if (loading) {
                        loading.style.display = 'none';
                    }
                    
                    if (data.success && data.cart && data.items.length > 0) {
                        renderBookingSummary(data.cart, data.items, summaryContainer);
                    } else {
                        summaryContainer.innerHTML = `
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>No items in cart.</strong> Please add accommodations to your cart first.
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (loading) loading.style.display = 'none';
                    summaryContainer.innerHTML = `<div class="alert alert-error">Error loading summary</div>`;
                });
        }

        function renderBookingSummary(cart, items, container) {
            daysCount = parseInt(cart.daysCount) || 1;
            numGuests = parseInt(cart.numGuests) || 1;
            
            // Determine booking type correctly
            // Same day (check-in = check-out) = day-use
            // Different days (check-in < check-out) = overnight
            const checkInDate = new Date(cart.checkInDate);
            const checkOutDate = new Date(cart.checkOutDate);
            const isSameDay = checkInDate.toDateString() === checkOutDate.toDateString();
            const bookingType = isSameDay ? 'day-use' : 'overnight';
            
            console.log('📅 Booking Type Calculation:', {
                checkInDate: cart.checkInDate,
                checkOutDate: cart.checkOutDate,
                isSameDay: isSameDay,
                daysCount: daysCount,
                determinedBookingType: bookingType
            });
            
            const bookingTypeSelect = document.getElementById('booking_type');
            bookingTypeSelect.innerHTML = `
                <option value="day-use" ${bookingType === 'day-use' ? 'selected' : ''}>Day Use</option>
                <option value="overnight" ${bookingType === 'overnight' ? 'selected' : ''}>Overnight</option>
            `;
            
            let subtotal = 0;
            
            const itemsHTML = items.map(item => {
                const unit = item.unit;
                const unitPrice = parseFloat(unit.unitRatePrice);
                let itemTotal = 0;
                let calculation = '';
                
                if (unit.unitType === 'room') {
                    if (numGuests === 1) {
                        itemTotal = unitPrice * 2 * daysCount;
                        calculation = `₱${unitPrice.toFixed(2)} × 2 × ${daysCount} day(s)`;
                    } else {
                        itemTotal = unitPrice * numGuests * daysCount;
                        calculation = `₱${unitPrice.toFixed(2)} × ${numGuests} × ${daysCount} day(s)`;
                    }
                } else if (unit.unitType === 'cottage') {
                    if (hasActiveEntranceFee) {
                        const entranceTotal = entranceFeeAmount * numGuests;
                        itemTotal = entranceTotal + unitPrice;
                        calculation = `(₱${entranceFeeAmount.toFixed(2)} × ${numGuests}) + ₱${unitPrice.toFixed(2)}`;
                    } else {
                        itemTotal = unitPrice;
                        calculation = `Cottage Price Only`;
                    }
                }
                
                subtotal += itemTotal;
                
                return `
                    <div class="summary-item">
                        <div class="item-details">
                            <h4 class="font-semibold text-lg text-gray-800">${unit.unitName}</h4>
                            <span class="type-badge ${unit.unitType}">
                                <i class="fas fa-${unit.unitType === 'room' ? 'bed' : 'home'}"></i>
                                ${unit.unitType}
                            </span>
                            <p class="text-sm text-gray-600 mt-2">${calculation}</p>
                        </div>
                        <div class="item-price">
                            <p class="text-xl font-bold text-blue-600">₱${itemTotal.toFixed(2)}</p>
                        </div>
                    </div>
                `;
            }).join('');

            const tax = subtotal * 0.12;
            const serviceFee = subtotal * 0.05;
            const total = subtotal + tax + serviceFee;
            
            bookingTotal = total;
            
            const summaryHTML = `
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4">Booking Details</h3>
                    <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-in:</span>
                            <span class="font-semibold">${cart.checkInDate}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Check-out:</span>
                            <span class="font-semibold">${cart.checkOutDate}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold">${daysCount} day(s)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-semibold">${bookingType === 'day-use' ? 'Day Use' : 'Overnight'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Guests:</span>
                            <span class="font-semibold">${numGuests}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4">Accommodations</h3>
                    ${itemsHTML}
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span class="font-semibold">₱${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Tax (12%):</span>
                        <span class="font-semibold">₱${tax.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Service Fee (5%):</span>
                        <span class="font-semibold">₱${serviceFee.toFixed(2)}</span>
                    </div>
                    <div class="total-price">
                        <span>Total Amount:</span>
                        <span class="price-amount">₱${total.toFixed(2)}</span>
                    </div>
                </div>
            `;
            
            container.innerHTML = summaryHTML;
            updatePaymentAmounts();
        }

        function updatePaymentAmounts() {
            const total = bookingTotal;
            const downpayment = total * 0.5;
            
            document.getElementById('total-amount-display').textContent = '₱' + total.toFixed(2);
            document.getElementById('min-payment-display').textContent = '₱' + downpayment.toFixed(2);
            document.getElementById('downpayment-amount').textContent = '₱' + downpayment.toFixed(2);
            document.getElementById('full-amount').textContent = '₱' + total.toFixed(2);
        }

        function prefillUserInfo() {
            const user = {
                name: '{{ Auth::user()->name ?? "" }}',
                email: '{{ Auth::user()->email ?? "" }}',
                phone: '{{ Auth::user()->phoneNumber ?? "" }}'
            };
            
            if (user.name && user.name.trim() !== '') {
                document.querySelector('input[name="full_name"]').value = user.name.trim();
            }
            if (user.email) {
                document.querySelector('input[name="email"]').value = user.email;
            }
            if (user.phone) {
                document.querySelector('input[name="phone"]').value = user.phone;
            }
        }

        // Payment method change handler
        document.getElementById('payment_method').addEventListener('change', function() {
            const paymentMethod = this.value;
            const cashSection = document.getElementById('cash-payment-section');
            const gcashSection = document.getElementById('gcash-payment-section');
            const amountInfo = document.getElementById('payment-amount-info');
            
            cashSection.style.display = 'none';
            gcashSection.style.display = 'none';
            amountInfo.style.display = 'none';
            
            if (paymentMethod === 'cash') {
                cashSection.style.display = 'block';
                amountInfo.style.display = 'block';
            } else if (paymentMethod === 'gcash') {
                gcashSection.style.display = 'block';
                amountInfo.style.display = 'block';
            }
        });

        function selectPaymentAmount(type) {
            const downpaymentBtn = document.getElementById('downpayment-btn');
            const fullPaymentBtn = document.getElementById('full-payment-btn');
            const gcashBtn = document.getElementById('gcash-booking-btn');
            
            downpaymentBtn.classList.remove('selected');
            fullPaymentBtn.classList.remove('selected');
            
            if (type === 'downpayment') {
                downpaymentBtn.classList.add('selected');
                selectedGCashAmount = bookingTotal * 0.5;
            } else {
                fullPaymentBtn.classList.add('selected');
                selectedGCashAmount = bookingTotal;
            }
            
            gcashBtn.disabled = false;
        }

        function validateForm() {
            const form = document.getElementById('bookingForm');
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    field.style.borderColor = '#dc2626';
                } else {
                    field.style.borderColor = '';
                }
            });

            const paymentMethod = document.getElementById('payment_method');
            if (!paymentMethod.value) {
                isValid = false;
                paymentMethod.style.borderColor = '#dc2626';
            }

            if (!isValid) {
                alert('Please fill in all required fields');
            }

            return isValid;
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} fixed top-20 right-4 z-50 max-w-md`;
            alertDiv.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} text-xl mr-3 mt-1"></i>
                    <div>
                        <p class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</p>
                        <p class="text-sm">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        async function validateCartBeforeSubmit() {
            try {
                const response = await fetch('/api/cart/pre-validate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    if (data.validation_errors) {
                        showAlert('error', data.validation_errors.join('\n'));
                    } else {
                        showAlert('error', data.message || 'Cart validation failed.');
                    }
                    return false;
                }
                
                return true;
            } catch (error) {
                console.error('Validation error:', error);
                showAlert('error', 'Error validating cart. Please try again.');
                return false;
            }
        }

        async function submitCashBooking() {
            if (!validateForm()) return;
            
            const isValid = await validateCartBeforeSubmit();
            if (!isValid) return;
            
            const cashBtn = document.getElementById('cash-booking-btn');
            const originalText = cashBtn.innerHTML;
            
            cashBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            cashBtn.disabled = true;
            
            const form = document.getElementById('bookingForm');
            const formData = new FormData(form);
            
            formData.set('payment_amount', bookingTotal.toFixed(2));
            formData.set('payment_method', 'cash');
            
            const bookingTypeSelect = document.getElementById('booking_type');
            if (bookingTypeSelect.disabled && bookingTypeSelect.value) {
                formData.append('booking_type', bookingTypeSelect.value);
            }
            
            formData.append('event_type', 'normal-booking');
            
            try {
                const response = await fetch('/api/customer-bookings', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccessMessage(data);
                } else {
                    throw new Error(data.message || 'Booking failed');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Failed to complete booking: ' + error.message);
                cashBtn.innerHTML = originalText;
                cashBtn.disabled = false;
            }
        }

        async function submitGCashBooking() {
            if (!selectedGCashAmount) {
                showAlert('error', 'Please select payment amount (Downpayment or Full Payment)');
                return;
            }
            
            if (!validateForm()) return;
            
            const isValid = await validateCartBeforeSubmit();
            if (!isValid) return;
            
            const gcashBtn = document.getElementById('gcash-booking-btn');
            const originalText = gcashBtn.innerHTML;
            
            gcashBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating booking...';
            gcashBtn.disabled = true;
            
            try {
                // Step 1: Create booking
                const form = document.getElementById('bookingForm');
                const formData = new FormData(form);
                
                formData.set('payment_amount', selectedGCashAmount.toFixed(2));
                formData.set('payment_method', 'gcash');
                
                const bookingTypeSelect = document.getElementById('booking_type');
                if (bookingTypeSelect.disabled && bookingTypeSelect.value) {
                    formData.append('booking_type', bookingTypeSelect.value);
                }
                
                formData.append('event_type', 'normal-booking');
                
                const bookingResponse = await fetch('/api/customer-bookings', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const bookingData = await bookingResponse.json();
                
                if (!bookingData.success) {
                    throw new Error(bookingData.message || 'Failed to create booking');
                }
                
                currentBookingId = bookingData.booking_id;
                
                // Step 2: Process GCash payment
                gcashBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Redirecting to GCash...';
                
                const paymentResponse = await fetch('/gcash/process-payment', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: currentBookingId,
                        amount: selectedGCashAmount
                    })
                });
                
                const paymentData = await paymentResponse.json();
                
                if (paymentData.success && paymentData.checkout_url) {
                    window.location.href = paymentData.checkout_url;
                } else {
                    throw new Error(paymentData.message || 'Failed to process GCash payment');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Failed to process GCash payment: ' + error.message);
                gcashBtn.innerHTML = originalText;
                gcashBtn.disabled = false;
            }
        }

        function showSuccessMessage(data) {
            const bookingForm = document.getElementById('bookingForm');
            bookingForm.innerHTML = `
                <div class="alert alert-success">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-2xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-xl mb-2">Booking Successful!</h4>
                            <p class="mb-3">${data.message}</p>
                            <div class="bg-white p-4 rounded-lg border border-green-200 mt-3">
                                <p class="mb-1"><strong>Booking Reference:</strong> ${data.booking_reference}</p>
                                <p class="mb-1"><strong>Booking ID:</strong> ${data.booking_id}</p>
                                <p class="mb-1"><strong>Status:</strong> ${data.booking_status}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-6">
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="/" class="action-btn btn-success" style="max-width: 200px;">
                            <i class="fas fa-home mr-2"></i>Back to Home
                        </a>
                        <a href="/my-bookings" class="action-btn btn-success" style="max-width: 200px;">
                            <i class="fas fa-calendar-alt mr-2"></i>View Bookings
                        </a>
                    </div>
                </div>
            `;
        }

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel this booking?')) {
                fetch('/api/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('home') }}";
                    }
                });
            }
        }
    </script>
</body>
</html>