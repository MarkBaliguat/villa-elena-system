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
            --light-bg: #FFFBF0;
            --card-bg: #FFFFFF;
            --text-dark: #1F2937;
            --text-medium: #6B7280;
            --text-light: #9CA3AF;
            --border-color: #E5E7EB;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 300px);
        }

        /* Container */
        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Section Title */
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

        /* Booking Card */
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

        /* Form Elements */
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

        .form-input:hover {
            border-color: #D1D5DB;
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

        .form-select:hover {
            border-color: #D1D5DB;
        }

        .form-select:disabled {
            background-color: #f8f9fa;
            color: var(--text-light);
            cursor: not-allowed;
            opacity: 0.8;
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

        .form-textarea:hover {
            border-color: #D1D5DB;
        }

        /* Summary Items */
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.25rem 0;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .summary-item:hover {
            background: rgba(255, 215, 0, 0.03);
            padding-left: 10px;
            padding-right: 10px;
            margin: 0 -10px;
            border-radius: 8px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item .item-details {
            flex: 1;
        }

        .summary-item .item-price {
            text-align: right;
            font-weight: 600;
            color: var(--text-dark);
            min-width: 100px;
        }

        /* Type Badges */
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
            transition: all 0.3s ease;
        }

        .type-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        /* Calculation Breakdown */
        .calculation-breakdown {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            margin-top: 10px;
            font-size: 0.875rem;
        }

        .calculation-breakdown p {
            margin: 6px 0;
            color: var(--text-medium);
            line-height: 1.5;
        }

        .calculation-breakdown strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        .calculation-breakdown .info-note {
            padding: 8px;
            border-radius: 6px;
            margin-top: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .calculation-breakdown .info-note i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .calculation-breakdown .info-note.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .calculation-breakdown .info-note.green {
            background: rgba(34, 197, 94, 0.1);
            color: #166534;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .calculation-breakdown .info-note.red {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Price Display */
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

        /* Buttons */
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

        .action-btn:active {
            transform: translateY(-1px);
        }

        .action-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .action-btn:hover::after {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
        }

        .btn-primary:disabled {
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #047857, #065f46);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        /* Loading Spinner */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            min-height: 300px;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 215, 0, 0.2);
            border-top-color: var(--primary-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1.5rem;
        }

        .loading-text {
            color: var(--text-medium);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Alerts */
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

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #1e40af;
            border-color: #60a5fa;
        }

        /* Downpayment Info */
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
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .downpayment-info i {
            color: var(--primary-yellow);
            font-size: 1.1rem;
        }

        .downpayment-info strong {
            color: var(--primary-black);
        }

        /* Payment Input Group */
        .payment-input-group {
            position: relative;
        }

        .payment-input-group .currency-symbol {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dark);
            font-weight: 600;
            z-index: 1;
        }

        .payment-input-group .form-input {
            padding-left: 40px;
        }

        /* Help Section */
        .help-section {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .help-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .help-item:last-child {
            margin-bottom: 0;
        }

        .help-item:hover {
            background: rgba(255, 215, 0, 0.1);
            transform: translateX(5px);
        }

        .help-item i {
            color: var(--primary-yellow);
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .help-item span {
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .booking-container {
                padding: 2rem 1rem;
            }
        }

        @media (max-width: 992px) {
            .section-title {
                font-size: 2.5rem;
            }
            
            .booking-card {
                padding: 2rem;
            }
            
            .action-btn {
                padding: 14px 24px;
            }
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
            
            .total-price {
                font-size: 1.5rem;
            }
            
            .total-price .price-amount {
                font-size: 1.75rem;
            }
            
            .form-input,
            .form-select,
            .form-textarea {
                padding: 12px 14px;
            }
        }

        @media (max-width: 576px) {
            .booking-container {
                padding: 1.5rem 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .booking-card {
                padding: 1.5rem;
            }
            
            .action-btn {
                padding: 12px 20px;
                font-size: 0.95rem;
            }
            
            .help-section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
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
                            
                            <!-- Personal Information -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" name="full_name" class="form-input" required  readonly>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i>
                                        Email Address
                                    </label>
                                    <input type="email" name="email" class="form-input" required  readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i>
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" class="form-input" required readonly>
                                </div>
                            </div>
                            
                            <!-- Booking Details -->
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
                            
                            <!-- Payment Section -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Payment Amount
                                </label>
                                <div class="payment-input-group">
                                    <span class="currency-symbol">₱</span>
                                    <input type="number" name="payment_amount" class="form-input" id="payment_amount" required 
                                           step="0.01" min="0" placeholder="Enter payment amount">
                                </div>
                                <p class="text-xs text-gray-500 mt-2 ml-6">Enter the amount you wish to pay</p>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-credit-card"></i>
                                    Payment Method
                                </label>
                                <select name="payment_method" class="form-select" id="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="debit_card">Debit Card</option>
                                </select>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="space-y-4 mt-8">
                                <button type="button" onclick="submitBooking()" class="action-btn btn-primary" id="submit-booking-btn">
                                    <i class="fas fa-check-circle"></i>
                                    Complete Booking
                                </button>
                                
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
                            <div class="loading-container" id="summary-loading">
                                <div class="loading-spinner"></div>
                                <p class="loading-text">Loading booking summary...</p>
                            </div>
                            <!-- Summary will be loaded here -->
                        </div>
                    </div>
                    
                    <div class="booking-card help-section">
                        <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center gap-2">
                            <i class="fas fa-question-circle text-yellow-500"></i>
                            Need Help?
                        </h2>
                        <div class="space-y-2">
                            <div class="help-item">
                                <i class="fas fa-phone"></i>
                                <span>+63 912 345 6789</span>
                            </div>
                            <div class="help-item">
                                <i class="fas fa-envelope"></i>
                                <span>info@villaelena.com</span>
                            </div>
                            <div class="help-item">
                                <i class="fas fa-clock"></i>
                                <span>24/7 Customer Support</span>
                            </div>
                            <div class="help-item">
                                <i class="fas fa-headset"></i>
                                <span>Live Chat Available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('customerFolder.partials.footer')

    <script>
        // Global variables
        let bookingTotal = 0;
        let cartUnitType = null;
        let daysCount = 1;
        let numGuests = 1;
        let entranceFeeAmount = 0;
        let hasActiveEntranceFee = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Load booking summary and entrance fee
            Promise.all([
                loadEntranceFee(),
                loadBookingSummary()
            ]).then(() => {
                // Pre-fill user information if available
                prefillUserInfo();
                // Update payment amount field
                updatePaymentAmountField();
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
                        console.log('Entrance fee loaded:', entranceFeeAmount);
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
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
                            <a href="{{ route('roomBooking') }}" class="btn-secondary mt-4 block text-center">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Booking
                            </a>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading booking summary:', error);
                    if (loading) {
                        loading.style.display = 'none';
                    }
                    summaryContainer.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Error loading booking summary.</strong> Please try again.
                        </div>
                    `;
                });
        }

        function renderBookingSummary(cart, items, container) {
            daysCount = parseInt(cart.daysCount) || 1;
            numGuests = parseInt(cart.numGuests) || 1;
            
            // Determine booking type based on days count
            const bookingType = daysCount === 1 ? 'day-use' : 'overnight';
            
            // Set booking type in form
            const bookingTypeSelect = document.getElementById('booking_type');
            bookingTypeSelect.innerHTML = `
                <option value="day-use" ${bookingType === 'day-use' ? 'selected' : ''}>Day Use</option>
                <option value="overnight" ${bookingType === 'overnight' ? 'selected' : ''}>Overnight</option>
            `;
            
            // Determine cart unit type
            const unitTypes = [...new Set(items.map(item => item.unit?.unitType))];
            if (unitTypes.length === 1) {
                cartUnitType = unitTypes[0];
            }
            
            let subtotal = 0;
            let totalRoomAmount = 0;
            let totalCottageAmount = 0;
            let cottageEntranceFee = 0;
            
            // Process each item with correct calculation
            const itemsHTML = items.map(item => {
                const unit = item.unit;
                const unitPrice = parseFloat(unit.unitRatePrice);
                let itemTotal = 0;
                let calculation = '';
                let itemType = unit.unitType;
                
                if (itemType === 'room') {
                    // ✅ ROOM CALCULATION
                    if (numGuests === 1) {
                        // 1 guest only: unit price × 2 × days of stay
                        itemTotal = unitPrice * 2 * daysCount;
                        calculation = `₱${unitPrice.toFixed(2)} × 2 × ${daysCount} day(s)`;
                    } else {
                        // 2 or more: unit price × numguests × days of stay
                        itemTotal = unitPrice * numGuests * daysCount;
                        calculation = `₱${unitPrice.toFixed(2)} × ${numGuests} × ${daysCount} day(s)`;
                    }
                    totalRoomAmount += itemTotal;
                    
                    return `
                        <div class="summary-item">
                            <div class="item-details">
                                <h4 class="font-semibold text-lg text-gray-800">${unit.unitName}</h4>
                                <span class="type-badge room">
                                    <i class="fas fa-bed"></i>
                                    Room
                                </span>
                                <div class="calculation-breakdown">
                                    <p><strong>Calculation:</strong> ${calculation}</p>
                                    <p><strong>Rate:</strong> ₱${unitPrice.toFixed(2)} per night</p>
                                    <p><strong>Guests:</strong> ${numGuests} ${numGuests === 1 ? 'guest' : 'guests'}</p>
                                    <p><strong>Duration:</strong> ${daysCount} day(s)</p>
                                    ${numGuests === 1 ? 
                                        `<div class="info-note blue">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Single occupancy: minimum charge for 2 guests</span>
                                        </div>` : ''
                                    }
                                </div>
                            </div>
                            <div class="item-price">
                                <p class="text-blue-600 text-xl">₱${itemTotal.toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                } else if (itemType === 'cottage') {
                    // ✅ COTTAGE CALCULATION
                    if (hasActiveEntranceFee) {
                        // Normal calculation: (entrance fee × numguests) + unit price
                        const entranceTotal = entranceFeeAmount * numGuests;
                        itemTotal = entranceTotal + unitPrice;
                        cottageEntranceFee += entranceTotal;
                        calculation = `(₱${entranceFeeAmount.toFixed(2)} × ${numGuests}) + ₱${unitPrice.toFixed(2)}`;
                    } else {
                        // No active entrance fee
                        itemTotal = unitPrice;
                        calculation = `Cottage Price Only`;
                    }
                    totalCottageAmount += itemTotal;
                    
                    return `
                        <div class="summary-item">
                            <div class="item-details">
                                <h4 class="font-semibold text-lg text-gray-800">${unit.unitName}</h4>
                                <span class="type-badge cottage">
                                    <i class="fas fa-home"></i>
                                    Cottage
                                </span>
                                <div class="calculation-breakdown">
                                    <p><strong>Calculation:</strong> ${calculation}</p>
                                    <p><strong>Rate:</strong> ₱${unitPrice.toFixed(2)} (day use only)</p>
                                    <p><strong>Guests:</strong> ${numGuests} ${numGuests === 1 ? 'guest' : 'guests'}</p>
                                    ${hasActiveEntranceFee ? 
                                        `<p><strong>Entrance Fee:</strong> ₱${entranceFeeAmount.toFixed(2)} per guest</p>` :
                                        `<div class="info-note red">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>No active entrance fee found</span>
                                        </div>`
                                    }
                                    <div class="info-note green">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Day use only (not multiplied by days)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="item-price">
                                <p class="${hasActiveEntranceFee ? 'text-green-600' : 'text-red-600'} text-xl">₱${itemTotal.toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                }
                
                return '';
            }).join('');

            // Calculate totals
            const accommodationSubtotal = totalRoomAmount + totalCottageAmount;
            subtotal = accommodationSubtotal;
            
            // Calculate taxes and fees
            const tax = subtotal * 0.12;
            const serviceFee = subtotal * 0.05;
            const total = subtotal + tax + serviceFee;
            
            // Store total for payment validation
            bookingTotal = total;
            
            const summaryHTML = `
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-800">Booking Details</h3>
                    <div class="space-y-3 text-sm bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Check-in:</span>
                            <span class="font-semibold">${formatDate(cart.checkInDate)}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Check-out:</span>
                            <span class="font-semibold">${formatDate(cart.checkOutDate)}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold ${daysCount === 1 ? 'text-blue-600' : 'text-green-600'}">
                                ${daysCount} ${daysCount === 1 ? 'day' : 'days'} (${daysCount === 1 ? 'Day Use' : 'Overnight'})
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Guests:</span>
                            <span class="font-semibold">${numGuests} ${numGuests === 1 ? 'guest' : 'guests'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-800">Selected Accommodations</h3>
                    <div class="space-y-4">
                        ${itemsHTML}
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">Accommodation Subtotal:</span>
                        <span class="font-semibold">₱${subtotal.toFixed(2)}</span>
                    </div>
                    
                    ${cottageEntranceFee > 0 ? `
                    <div class="ml-4 text-sm text-gray-500 mb-2 bg-gray-50 p-3 rounded">
                        <div class="flex justify-between mb-1">
                            <span>Cottage Entrance Fee (${numGuests} × ₱${entranceFeeAmount.toFixed(2)}):</span>
                            <span>₱${cottageEntranceFee.toFixed(2)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cottage Base Price:</span>
                            <span>₱${(totalCottageAmount - cottageEntranceFee).toFixed(2)}</span>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">Tax (12%):</span>
                        <span class="font-semibold">₱${tax.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">Service Fee (5%):</span>
                        <span class="font-semibold">₱${serviceFee.toFixed(2)}</span>
                    </div>
                    <div class="total-price">
                        <span class="text-gray-800">Total Amount:</span>
                        <span class="price-amount">₱${total.toFixed(2)}</span>
                    </div>
                    
                    <div class="downpayment-info">
                        <p>
                            <i class="fas fa-info-circle"></i>
                            <strong>Minimum Downpayment:</strong> 50% (₱${(total * 0.5).toFixed(2)})
                        </p>
                    </div>
                </div>
            `;
            
            container.innerHTML = summaryHTML;
        }

        function updatePaymentAmountField() {
            const paymentInput = document.getElementById('payment_amount');
            const minPayment = bookingTotal * 0.5;
            
            paymentInput.min = minPayment.toFixed(2);
            paymentInput.max = bookingTotal.toFixed(2);
            paymentInput.placeholder = `Min: ₱${minPayment.toFixed(2)}, Max: ₱${bookingTotal.toFixed(2)}`;
            
            // Set default value to minimum downpayment
            paymentInput.value = minPayment.toFixed(2);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }
        
        function prefillUserInfo() {
            // Pre-fill user information from authenticated user
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

        // Helper function to show alerts
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
            
            // Remove alert after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Function for pre-validation
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
                    // Handle validation errors
                    if (data.has_special_event_conflict) {
                        showAlert('error', 'Cannot book: There is a special event during your selected dates.');
                        return false;
                    }
                    
                    if (data.unavailable_items) {
                        const itemNames = data.unavailable_items.map(item => item.unit.unitName).join(', ');
                        showAlert('error', `The following items are no longer available: ${itemNames}. Please update your cart.`);
                        return false;
                    }
                    
                    if (data.validation_errors) {
                        showAlert('error', 'Validation errors: ' + data.validation_errors.join('\n'));
                        return false;
                    }
                    
                    if (data.has_mixed_items) {
                        showAlert('error', 'Cannot have both rooms and cottages in the same booking.');
                        return false;
                    }
                    
                    if (data.has_entrance_fee_issue) {
                        showAlert('error', 'Cottage booking cannot proceed without an active entrance fee.');
                        return false;
                    }
                    
                    // General error message
                    showAlert('error', data.message || 'Cart validation failed.');
                    return false;
                }
                
                return data.success;
            } catch (error) {
                console.error('Validation error:', error);
                showAlert('error', 'Error validating cart. Please try again.');
                return false;
            }
        }

        function validateForm() {
            const form = document.getElementById('bookingForm');
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            const errors = [];

            // Check required fields
            requiredFields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    field.style.borderColor = '#dc2626';
                    const label = field.previousElementSibling?.textContent || 'this field';
                    errors.push(`Please fill in ${label}`);
                } else {
                    field.style.borderColor = '';
                }
            });

            // Check payment method is selected
            const paymentMethod = document.getElementById('payment_method');
            if (!paymentMethod.value) {
                isValid = false;
                paymentMethod.style.borderColor = '#dc2626';
                errors.push('Please select a payment method');
            } else {
                paymentMethod.style.borderColor = '';
            }

            // Check payment amount
            const paymentAmount = parseFloat(document.getElementById('payment_amount').value);
            const minPayment = bookingTotal * 0.5;
            
            if (isNaN(paymentAmount)) {
                isValid = false;
                errors.push('Please enter a valid payment amount');
                document.getElementById('payment_amount').style.borderColor = '#dc2626';
            } else if (paymentAmount < minPayment) {
                isValid = false;
                errors.push(`Minimum payment is ₱${minPayment.toFixed(2)} (50% downpayment)`);
                document.getElementById('payment_amount').style.borderColor = '#dc2626';
            } else if (paymentAmount > bookingTotal) {
                isValid = false;
                errors.push(`Payment cannot exceed total amount of ₱${bookingTotal.toFixed(2)}`);
                document.getElementById('payment_amount').style.borderColor = '#dc2626';
            } else {
                document.getElementById('payment_amount').style.borderColor = '';
            }

            if (!isValid) {
                const errorMessage = errors.join('\n');
                alert('Please fix the following errors:\n\n' + errorMessage);
            }

            return isValid;
        }

        async function submitBooking() {
            if (!validateForm()) return;
            
            // Add pre-validation check
            const isValid = await validateCartBeforeSubmit();
            if (!isValid) {
                return;
            }
            
            const submitBtn = document.getElementById('submit-booking-btn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitBtn.disabled = true;
            
            // Prepare form data
            const form = document.getElementById('bookingForm');
            const formData = new FormData(form);
            
            // Add booking_type if disabled but has value
            const bookingTypeSelect = document.getElementById('booking_type');
            if (bookingTypeSelect.disabled && bookingTypeSelect.value) {
                formData.append('booking_type', bookingTypeSelect.value);
            }
            
            // Add event_type
            formData.append('event_type', 'normal-booking');
            
            fetch('/api/customer-bookings', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            throw new Error(data.message || `Network response was not ok: ${response.status}`);
                        } catch (e) {
                            throw new Error(`Server error: ${response.status}`);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
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
                                        <p class="mb-1"><strong>Amount Paid:</strong> ₱${data.payment_amount?.toFixed(2) || '0.00'}</p>
                                        ${data.calculation_breakdown ? `
                                            <div class="mt-3 pt-3 border-t">
                                                <p class="mb-1"><strong>Payment Details:</strong></p>
                                                <p class="mb-1">Total Amount: ₱${data.calculation_breakdown.total_amount?.toFixed(2) || '0.00'}</p>
                                                <p>Remaining Balance: ₱${data.calculation_breakdown.remaining_balance?.toFixed(2) || '0.00'}</p>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="/" class="action-btn btn-success" style="max-width: 200px;">
                                    <i class="fas fa-home mr-2"></i>Back to Home
                                </a>
                                <a href="/my-bookings" class="action-btn btn-primary" style="max-width: 200px;">
                                    <i class="fas fa-calendar-alt mr-2"></i>View Bookings
                                </a>
                            </div>
                        </div>
                    `;
                    
                    // Clear any existing alerts
                    document.querySelectorAll('.alert.fixed').forEach(alert => alert.remove());
                    
                } else {
                    throw new Error(data.message || 'Booking failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Failed to complete booking: ' + error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel this booking? All selected accommodations will be removed from your cart.')) {
                // Clear cart
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
                    } else {
                        showAlert('error', 'Failed to clear cart: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Failed to cancel booking. Please try again.');
                });
            }
        }
    </script>
</body>
</html>