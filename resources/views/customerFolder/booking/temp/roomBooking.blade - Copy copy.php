<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Room Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            scroll-behavior: smooth;
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

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 70vh;
            min-height: 500px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), 
                        url('/images/main-photo.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
            max-width: 1200px;
            width: 100%;
        }

        /* Booking Card */
        .booking-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: 3rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
        }

        /* Form Elements */
        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            text-align: left;
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

        /* Search Button */
        .search-btn {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-btn:active {
            transform: translateY(-1px);
        }

        .search-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .search-btn:hover::after {
            left: 100%;
        }

        /* Accommodation Section */
        .accommodation-section {
            padding: 5rem 0;
            background: linear-gradient(to bottom, var(--light-bg), #FFFFFF);
        }

        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--text-dark), #4B5563);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-medium);
            max-width: 700px;
            margin: 0 auto 4rem;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        /* Tabs Navigation */
        .tabs-container {
            display: flex;
            justify-content: center;
            margin-bottom: 3.5rem;
            position: relative;
        }

        .tab-nav {
            display: flex;
            background: white;
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-medium);
            background: transparent;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .tab-btn:hover {
            color: var(--text-dark);
            background: rgba(255, 215, 0, 0.05);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.3);
        }

        .tab-btn i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .tab-btn:hover i {
            transform: scale(1.1);
        }

        .tab-btn.active i {
            transform: scale(1.1);
        }

        /* Accommodation Cards */
        .accommodation-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 2.5rem;
            margin-top: 2rem;
        }

        .accommodation-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .accommodation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .card-image-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .accommodation-card:hover .card-image {
            transform: scale(1.08);
        }

        /* Image Placeholder Style */
        .card-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-medium);
        }

        .card-image-placeholder i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            color: var(--primary-yellow);
        }

        .card-image-placeholder p {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .card-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            z-index: 1;
        }

        .card-content {
            padding: 1.75rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .card-description {
            color: var(--text-medium);
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            line-height: 1.6;
            flex-grow: 1;
        }

        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .feature-tag {
            background: rgba(255, 215, 0, 0.1);
            color: var(--text-dark);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s ease;
        }

        .feature-tag:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
        }

        .card-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .price-period {
            font-size: 0.9rem;
            color: var(--text-medium);
            font-weight: 500;
        }

        .card-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .action-btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-btn:active {
            transform: translateY(-1px);
        }

        .btn-cart {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
        }

        .btn-cart:hover {
            background: linear-gradient(135deg, #FFC800, #FF9500);
        }

        .btn-book {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
        }

        .btn-book:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-icon {
            font-size: 4rem;
            color: rgba(255, 215, 0, 0.3);
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .empty-description {
            color: var(--text-medium);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Notification System */
        .notification {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 9999;
            padding: 20px 25px;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 400px;
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }

        .notification.error {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
        }

        .notification.info {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: white;
        }

        .notification.warning {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: white;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 1rem;
        }

        .notification-message {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.5;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: opacity 0.3s;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #FFC800, #FF9500);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .accommodation-cards {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .hero-section {
                height: 60vh;
                min-height: 450px;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .booking-card {
                padding: 2rem;
            }
            
            .tab-btn {
                padding: 14px 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-top: 70px;
            }
            
            .hero-section {
                height: 50vh;
                min-height: 400px;
                background-attachment: scroll;
            }
            
            .section-title {
                font-size: 2.25rem;
            }
            
            .booking-card {
                padding: 1.75rem;
                margin-top: 2rem;
            }
            
            .tab-nav {
                flex-direction: column;
                gap: 8px;
                padding: 12px;
            }
            
            .tab-btn {
                width: 100%;
                justify-content: center;
            }
            
            .accommodation-cards {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
            }
            
            .notification {
                left: 20px;
                right: 20px;
                max-width: none;
                top: 80px;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 1rem;
                height: auto;
                min-height: 450px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .booking-card {
                padding: 1.5rem;
            }
            
            .form-input {
                padding: 12px 14px;
            }
            
            .search-btn {
                padding: 14px 24px;
            }
            
            .card-title {
                font-size: 1.35rem;
            }
            
            .card-price {
                font-size: 1.35rem;
            }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 fade-in">Find Your Perfect Stay</h1>
                <p class="text-xl md:text-2xl mb-8 fade-in" style="animation-delay: 0.2s;">Experience luxury and comfort at Villa Elena</p>
                
                <!-- Booking Card -->
                <div class="booking-card fade-in" style="animation-delay: 0.4s;">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Book Your Stay</h3>
                    
                    <form id="bookingForm" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @csrf
                        
                        <!-- Check-in Date -->
                        <div class="form-group">
                            <label class="form-label">Check-in Date</label>
                            <input type="date" name="check_in" id="check_in" class="form-input" required>
                        </div>
                        
                        <!-- Check-out Date -->
                        <div class="form-group">
                            <label class="form-label">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out" class="form-input" required>
                        </div>
                        
                        <!-- Number of Guests -->
                        <div class="form-group">
                            <label class="form-label">Number of Guests</label>
                            <input type="number" name="guests" id="guest-number" class="form-input" value="1" min="1" max="20" required>
                            <p class="text-xs text-gray-500 mt-2">Use arrow keys or scroll to adjust</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-group flex items-end">
                            <button type="button" id="searchBtn" class="search-btn">
                                <i class="fas fa-search mr-2"></i>
                                Search Rooms
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Accommodation Section -->
        <section class="accommodation-section">
            <div class="container mx-auto px-4 md:px-6">
                <div class="text-center mb-12">
                    <h2 class="section-title">Our Accommodations</h2>
                    <p class="section-subtitle">
                        Discover our selection of beautifully designed rooms and cottages, 
                        each offering comfort and tranquility for your perfect getaway.
                    </p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tabs-container">
                    <div class="tab-nav">
                        <a href="{{ route('roomBooking') }}" id="room-tab" class="tab-btn active">
                            <i class="fas fa-bed"></i>
                            <span>Rooms</span>
                        </a>
                        <a href="{{ route('cottageBooking') }}" id="cottage-tab" class="tab-btn">
                            <i class="fas fa-home"></i>
                            <span>Cottages</span>
                        </a>
                    </div>
                </div>

                <!-- Rooms Content -->
                <div id="rooms-content" class="accommodation-content active">
                    <div class="loading-container" id="rooms-loading" style="display: none;">
                        <div class="loading-spinner"></div>
                        <p class="loading-text">Loading available rooms...</p>
                    </div>
                    <div id="rooms-container" class="accommodation-cards fade-in">
                        <!-- Rooms will be loaded here -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Include Footer -->
    @include('customerFolder.partials.footer')

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <script>
        // Global variables
        let currentCheckIn = '';
        let currentCheckOut = '';
        let currentGuests = 1;
        let cartItemCount = 0;
        let cartItems = [];
        let cartDates = { checkIn: '', checkOut: '' };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in').min = today;
            document.getElementById('check_out').min = today;
            
            // Set default check-out to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('check_out').value = tomorrow.toISOString().split('T')[0];
            currentCheckOut = tomorrow.toISOString().split('T')[0];

            // Set active tab
            const currentPath = window.location.pathname;
            const roomTab = document.getElementById('room-tab');
            const cottageTab = document.getElementById('cottage-tab');
            
            if (currentPath.includes('cottage')) {
                roomTab.classList.remove('active');
                cottageTab.classList.add('active');
            } else {
                roomTab.classList.add('active');
                cottageTab.classList.remove('active');
            }
            
            // Search button functionality
            document.getElementById('searchBtn').addEventListener('click', function() {
                const checkIn = document.getElementById('check_in').value;
                const checkOut = document.getElementById('check_out').value;
                const guests = document.getElementById('guest-number').value;
                
                if (!checkIn || !checkOut) {
                    showNotification('Please select both check-in and check-out dates', 'error');
                    return;
                }
                
                // Convert to Date objects for comparison
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                
                // Check if check-out is before check-in
                if (checkOutDate < checkInDate) {
                    showNotification('Check-out date cannot be before check-in date', 'error');
                    return;
                }
                
                currentCheckIn = checkIn;
                currentCheckOut = checkOut;
                currentGuests = guests;
                
                loadUnits('rooms');
            });
            
            // Guest number picker functionality
            const guestNumberInput = document.getElementById('guest-number');
            
            guestNumberInput.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    let value = parseInt(this.value);
                    if (value < 20) {
                        this.value = value + 1;
                    }
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    let value = parseInt(this.value);
                    if (value > 1) {
                        this.value = value - 1;
                    }
                }
            });
            
            guestNumberInput.addEventListener('input', function() {
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    value = 1;
                } else if (value > 20) {
                    value = 20;
                }
                this.value = value;
            });
            
            guestNumberInput.addEventListener('wheel', function(e) {
                e.preventDefault();
                let value = parseInt(this.value);
                
                if (e.deltaY < 0) {
                    if (value < 20) {
                        this.value = value + 1;
                    }
                } else {
                    if (value > 1) {
                        this.value = value - 1;
                    }
                }
            });

            // Date input event listeners
            document.getElementById('check_in').addEventListener('change', function() {
                const checkIn = this.value;
                const checkOut = document.getElementById('check_out').value;
                
                if (checkIn) {
                    document.getElementById('check_out').min = checkIn;
                    
                    if (checkOut && checkOut < checkIn) {
                        document.getElementById('check_out').value = checkIn;
                    }
                }
            });

            document.getElementById('check_out').addEventListener('change', function() {
                const checkIn = document.getElementById('check_in').value;
                const checkOut = this.value;
                
                if (checkIn && checkOut) {
                    const checkInDate = new Date(checkIn);
                    const checkOutDate = new Date(checkOut);
                    
                    if (checkOutDate < checkInDate) {
                        showNotification('Check-out date cannot be before check-in date', 'error');
                        this.value = checkIn;
                    }
                }
            });

            // Load initial rooms
            loadUnits('rooms');
            
            // Load cart count and items
            loadCartCount();
            loadCartItemsForValidation();
        });

        // Function to load cart count
        function loadCartCount() {
            fetch('/api/cart/items')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.cart && data.items.length > 0) {
                        cartItemCount = data.items.length;
                        updateCartBadge(cartItemCount);
                    } else {
                        cartItemCount = 0;
                        updateCartBadge(0);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }

        // Function to load cart items for date validation
        function loadCartItemsForValidation() {
            fetch('/api/cart/items')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.cart && data.items.length > 0) {
                        cartItems = data.items;
                        cartDates.checkIn = data.cart.checkInDate;
                        cartDates.checkOut = data.cart.checkOutDate;
                        
                        // Update current dates from cart
                        currentCheckIn = cartDates.checkIn;
                        currentCheckOut = cartDates.checkOut;
                        
                        // Update date inputs to match cart
                        document.getElementById('check_in').value = currentCheckIn;
                        document.getElementById('check_out').value = currentCheckOut;
                        
                        // Set min dates
                        document.getElementById('check_out').min = currentCheckIn;
                        
                        // Show notification about existing cart items
                        if (data.items.length > 0) {
                            showNotification(
                                `You have ${data.items.length} item(s) in your cart. New items must use the same dates (${currentCheckIn} to ${currentCheckOut}).`, 
                                'info', 
                                5000
                            );
                        }
                    } else {
                        cartItems = [];
                        cartDates = { checkIn: '', checkOut: '' };
                    }
                })
                .catch(error => {
                    console.error('Error loading cart items:', error);
                    cartItems = [];
                    cartDates = { checkIn: '', checkOut: '' };
                });
        }

        // Function to update cart badge in navbar
        function updateCartBadge(count) {
            const navbarCartBadge = document.getElementById('navbar-cart-badge');
            if (navbarCartBadge) {
                if (count > 0) {
                    navbarCartBadge.textContent = count;
                    navbarCartBadge.style.display = 'flex';
                    // Add animation
                    navbarCartBadge.style.animation = 'badgePop 0.3s ease';
                } else {
                    navbarCartBadge.style.display = 'none';
                }
            }
        }

        // Function to load units based on type
        function loadUnits(type) {
            const container = document.getElementById(`${type}-container`);
            const loading = document.getElementById(`${type}-loading`);
            
            // Show loading and clear container
            if (container) {
                container.innerHTML = '';
                container.style.display = 'none';
            }
            if (loading) {
                loading.style.display = 'flex';
            }
            
            // Build query parameters
            const params = new URLSearchParams();
            
            if (currentCheckIn) params.append('check_in', currentCheckIn);
            if (currentCheckOut) params.append('check_out', currentCheckOut);
            if (currentGuests) params.append('guests', currentGuests);
            
            fetch(`/api/available-rooms?${params}`)
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
                    
                    if (data.success) {
                        renderUnits(data.rooms, container, type);
                    } else {
                        renderEmptyState(container, 'No rooms available for the selected dates. Please try different dates.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (loading) {
                        loading.style.display = 'none';
                    }
                    renderEmptyState(container, 'Error loading rooms. Please try again.');
                });
        }

        // Render function with Add to Cart and Book Now buttons
        function renderUnits(units, container, type) {
            if (!units || units.length === 0) {
                renderEmptyState(container, 'No ' + type + ' available for the selected dates. Please try different dates.');
                return;
            }
            
            container.innerHTML = '';
            container.style.display = 'grid';
            
            units.forEach(unit => {
                // Use first image if available, otherwise use placeholder
                let imageUrl = '';
                
                try {
                    if (unit.images) {
                        const images = typeof unit.images === 'string' ? JSON.parse(unit.images) : unit.images;
                        if (Array.isArray(images) && images.length > 0 && images[0]) {
                            imageUrl = images[0];
                            // If it's a local path, convert to full URL
                            if (!imageUrl.startsWith('http')) {
                                imageUrl = `/storage/${imageUrl}`;
                            }
                        }
                    }
                } catch (e) {
                    console.error('Error parsing images:', e);
                }
                
                const card = document.createElement('div');
                card.className = 'accommodation-card fade-in';
                card.innerHTML = `
                    <div class="card-image-container">
                        ${imageUrl ? 
                            `<img src="${imageUrl}" alt="${unit.unitName}" class="card-image">` :
                            `<div class="card-image-placeholder">
                                <i class="fas fa-bed"></i>
                                <p>No image available</p>
                            </div>`
                        }
                        <div class="card-badge">
                            Available
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">${unit.unitName}</h3>
                        <p class="card-description">${unit.description || 'Experience comfort and luxury in this beautifully designed accommodation.'}</p>
                        <div class="card-features">
                            <span class="feature-tag">
                                <i class="fas fa-users mr-1"></i>
                                ${unit.capacity} guests
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-home mr-1"></i>
                                ${unit.unitType}
                            </span>
                            <span class="feature-tag" style="background: rgba(16, 185, 129, 0.1); color: #065f46; border-color: rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-check-circle mr-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="card-price">
                            ₱${parseInt(unit.unitRatePrice).toLocaleString()}
                            <span class="price-period">/ night</span>
                        </div>
                        <div class="card-actions">
                            <button onclick="addToCart(${unit.unitID}, this)" class="action-btn btn-cart">
                                <i class="fas fa-cart-plus"></i>
                                Add to Cart
                            </button>
                            <button onclick="bookNow(${unit.unitID}, this)" class="action-btn btn-book">
                                <i class="fas fa-calendar-check"></i>
                                Book Now
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Render empty state
        function renderEmptyState(container, message) {
            container.style.display = 'block';
            container.innerHTML = `
                <div class="empty-state fade-in">
                    <div class="empty-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3 class="empty-title">No Rooms Available</h3>
                    <p class="empty-description">${message}</p>
                    <button onclick="resetSearch()" class="action-btn btn-book" style="max-width: 200px; margin: 0 auto;">
                        <i class="fas fa-calendar-alt"></i>
                        Try Different Dates
                    </button>
                </div>
            `;
        }

        // Reset search function
        function resetSearch() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            document.getElementById('check_in').value = '';
            document.getElementById('check_out').value = '';
            document.getElementById('guest-number').value = 1;
            
            currentCheckIn = '';
            currentCheckOut = '';
            currentGuests = 1;
            
            loadUnits('rooms');
        }

        // Add to Cart function
        function addToCart(unitId, button) {
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            const guests = document.getElementById('guest-number').value;
            
            if (!checkIn || !checkOut) {
                showNotification('Please select check-in and check-out dates first', 'error');
                return;
            }
            
            // Convert to Date objects for validation
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            
            if (checkOutDate < checkInDate) {
                showNotification('Check-out date cannot be before check-in date', 'error');
                return;
            }
            
            // Validate dates match existing cart (if any)
            if (cartItems.length > 0) {
                const selectedCheckIn = checkIn;
                const selectedCheckOut = checkOut;
                
                if (selectedCheckIn !== cartDates.checkIn || selectedCheckOut !== cartDates.checkOut) {
                    showNotification(
                        `All items in cart must have the same check-in and check-out dates. You already have items with dates ${cartDates.checkIn} to ${cartDates.checkOut}.`, 
                        'error', 
                        6000
                    );
                    return;
                }
            }
            
            const originalContent = button.innerHTML;
            
            // Show loading on button
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            button.disabled = true;
            
            fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    unit_id: unitId,
                    check_in: checkIn,
                    check_out: checkOut,
                    guests: guests
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.login_required) {
                        window.location.href = '/login';
                    } else {
                        // Update cart counter
                        cartItemCount = data.cart_count;
                        updateCartBadge(cartItemCount);
                        
                        // Update cart items for validation
                        cartItems = data.items || [];
                        cartDates.checkIn = checkIn;
                        cartDates.checkOut = checkOut;
                        
                        // Update current dates
                        currentCheckIn = checkIn;
                        currentCheckOut = checkOut;
                        
                        // Show success message
                        const days = data.calculation?.days || 1;
                        const effectiveGuests = data.calculation?.effective_guests || guests;
                        
                        let message = 'Room added to cart successfully! ';
                        if (days === 1 && checkIn === checkOut) {
                            message += `(Same-day booking for ${effectiveGuests} guest${effectiveGuests > 1 ? 's' : ''})`;
                        } else {
                            message += `(${days} day${days > 1 ? 's' : ''} for ${effectiveGuests} guest${effectiveGuests > 1 ? 's' : ''})`;
                        }
                        
                        showNotification(message, 'success');
                        
                        // Update button to show success
                        button.innerHTML = '<i class="fas fa-check"></i> Added!';
                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }, 2000);
                    }
                } else {
                    showNotification('Error: ' + data.message, 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add room to cart. Please try again.', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }

        // Book Now function
        function bookNow(unitId, button) {
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            const guests = document.getElementById('guest-number').value;
            
            if (!checkIn || !checkOut) {
                showNotification('Please select check-in and check-out dates first', 'error');
                return;
            }
            
            // Convert to Date objects for validation
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            
            if (checkOutDate < checkInDate) {
                showNotification('Check-out date cannot be before check-in date', 'error');
                return;
            }
            
            // Validate dates match existing cart (if any)
            if (cartItems.length > 0) {
                const selectedCheckIn = checkIn;
                const selectedCheckOut = checkOut;
                
                if (selectedCheckIn !== cartDates.checkIn || selectedCheckOut !== cartDates.checkOut) {
                    showNotification(
                        `All items in cart must have the same check-in and check-out dates. You already have items with dates ${cartDates.checkIn} to ${cartDates.checkOut}.`, 
                        'error', 
                        6000
                    );
                    return;
                }
            }
            
            // First add to cart, then redirect to booking page
            const originalContent = button.innerHTML;
            
            // Show loading on button
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
            
            fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    unit_id: unitId,
                    check_in: checkIn,
                    check_out: checkOut,
                    guests: guests
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.login_required) {
                        window.location.href = '/login';
                    } else {
                        // Update cart counter
                        cartItemCount = data.cart_count;
                        updateCartBadge(cartItemCount);
                        
                        // Redirect to booking page
                        window.location.href = "{{ route('booking.page') }}";
                    }
                } else {
                    showNotification('Error: ' + data.message, 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to book room. Please try again.', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }

        // Notification system
        function showNotification(message, type = 'info', duration = 3000) {
            const container = document.getElementById('notification-container');
            const id = 'notification-' + Date.now();
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-triangle',
                warning: 'fa-exclamation-circle',
                info: 'fa-info-circle'
            };
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas ${icons[type]} notification-icon"></i>
                <div class="notification-content">
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="closeNotification('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto remove after duration
            const autoRemove = setTimeout(() => {
                closeNotification(id);
            }, duration);
            
            // Store timer ID for manual close
            notification.dataset.timer = autoRemove;
        }

        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                // Clear auto-remove timer
                clearTimeout(notification.dataset.timer);
                
                // Remove show class and then remove element
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }
        }
    </script>
</body>
</html>