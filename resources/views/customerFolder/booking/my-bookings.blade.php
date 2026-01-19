<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Villa Elena</title>
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
        ::-webkit-scrollbar {
            width: 12px;
            background-color: #F1F1F1;
            }
            ::-webkit-scrollbar-thumb {
            background-color: #FFD700; 
            border-radius: 6px;
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
        .bookings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Section Title */
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

        .section-subtitle {
            text-align: center;
            color: var(--text-medium);
            margin-bottom: 3rem;
            font-size: 1.1rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
        }

        .stat-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-medium);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        /* Filter Tabs */
        .filter-container {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: var(--primary-yellow);
        }

        .filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-tab {
            padding: 10px 24px;
            border-radius: 50px;
            background: rgba(255, 215, 0, 0.1);
            color: var(--text-dark);
            font-weight: 600;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .filter-tab:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
        }

        .filter-tab.active {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            border-color: var(--primary-yellow);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .filter-tab i {
            font-size: 0.9rem;
        }

        /* Booking Cards */
        .booking-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            opacity: 0.8;
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .booking-info h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .booking-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-medium);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary-yellow);
            width: 16px;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid;
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .status-confirmed {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(21, 128, 61, 0.1));
            color: #065f46;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .status-completed {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
            color: #1e40af;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .status-cancelled {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            color: #991b1b;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .status-refunded {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(147, 51, 234, 0.1));
            color: #6b21a8;
            border-color: rgba(168, 85, 247, 0.3);
        }

        /* Booking Content */
        .booking-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .detail-group {
            background: rgba(255, 215, 0, 0.05);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid rgba(255, 215, 0, 0.1);
        }

        .detail-group h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-medium);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-group h4 i {
            color: var(--primary-yellow);
        }

        .detail-content {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Price Summary */
        .price-summary {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), rgba(255, 165, 0, 0.05));
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        }

        .price-item:last-child {
            border-bottom: none;
        }

        .price-item.total {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 2px solid rgba(255, 215, 0, 0.3);
        }

        .price-amount {
            font-weight: 600;
            color: var(--text-dark);
        }

        .price-amount.paid {
            color: #059669;
        }

        /* Action Buttons */
        .booking-actions {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
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

        .btn-view {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
        }

        .btn-cancel {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-cancel:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
        }

        .btn-disabled {
            background: linear-gradient(135deg, #9ca3af, #6b7280);
            color: white;
            cursor: not-allowed;
        }

        .btn-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3.5rem;
            color: var(--primary-yellow);
            animation: gentlePulse 2s ease-in-out infinite;
        }

        @keyframes gentlePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .empty-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .empty-description {
            color: var(--text-medium);
            line-height: 1.6;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 215, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .modal-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header h2 i {
            color: var(--primary-yellow);
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-medium);
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            background: rgba(255, 215, 0, 0.1);
            color: var(--text-dark);
            transform: rotate(90deg);
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

        /* Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .section-title {
                font-size: 2.5rem;
            }
            
            .booking-content {
                grid-template-columns: 1fr;
            }
            
            .booking-details {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-top: 70px;
            }
            
            .section-title {
                font-size: 2.25rem;
                margin-bottom: 0.5rem;
            }
            
            .section-subtitle {
                margin-bottom: 2rem;
            }
            
            .booking-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .booking-meta {
                justify-content: flex-start;
            }
            
            .booking-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .modal-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .bookings-container {
                padding: 1.5rem 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .filter-tabs {
                justify-content: center;
            }
            
            .filter-tab {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
            
            .stat-card {
                padding: 1.25rem;
            }
            
            .stat-number {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="bookings-container">
            <h1 class="section-title cursive-font">My Bookings</h1>
            <p class="section-subtitle">Track and manage all your reservations in one place</p>
            
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <h3>Total Bookings</h3>
                            <div class="stat-number" id="total-bookings">0</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1)); color: #3b82f6;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <h3>Pending</h3>
                            <div class="stat-number text-yellow-600" id="pending-bookings">0</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1)); color: #f59e0b;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <h3>Confirmed</h3>
                            <div class="stat-number text-green-600" id="confirmed-bookings">0</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(21, 128, 61, 0.1)); color: #10b981;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <h3>Completed</h3>
                            <div class="stat-number text-blue-600" id="completed-bookings">0</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1)); color: #3b82f6;">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Tabs -->
            <div class="filter-container">
                <h3 class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter by Status
                </h3>
                <div class="filter-tabs">
                    <button class="filter-tab active" data-status="all">
                        <i class="fas fa-list"></i> All Bookings
                    </button>
                    <button class="filter-tab" data-status="pending">
                        <i class="fas fa-clock"></i> Pending
                    </button>
                    <button class="filter-tab" data-status="confirmed">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </button>
                    <button class="filter-tab" data-status="completed">
                        <i class="fas fa-flag-checkered"></i> Completed
                    </button>
                    <button class="filter-tab" data-status="cancelled">
                        <i class="fas fa-times-circle"></i> Cancelled
                    </button>
                </div>
            </div>
            
            <!-- Bookings List -->
            <div id="bookings-list">
                <div class="loading-container" id="loading-spinner">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Loading your bookings...</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Details Modal -->
    <div class="modal" id="booking-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-calendar-alt"></i>
                    Booking Details
                </h2>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="booking-details">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Include Footer -->
    @include('customerFolder.partials.footer')

    <script>
        let currentStatus = 'all';
        let currentBookingId = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadBookings();
            setupEventListeners();
        });
        
        function setupEventListeners() {
            // Filter tabs
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Load bookings with new filter
                    currentStatus = this.dataset.status;
                    loadBookings();
                });
            });
        }
        
        function loadBookings() {
            const bookingsList = document.getElementById('bookings-list');
            const loadingSpinner = document.getElementById('loading-spinner');
            
            // Show loading
            bookingsList.innerHTML = '';
            loadingSpinner.style.display = 'flex';
            
            fetch(`/api/my-bookings?status=${currentStatus}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingSpinner.style.display = 'none';
                
                if (data.success) {
                    // Update statistics
                    updateStatistics(data);
                    
                    if (data.bookings.length === 0) {
                        showNoBookingsMessage();
                    } else {
                        renderBookingsCards(data.bookings);
                    }
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                loadingSpinner.style.display = 'none';
                bookingsList.innerHTML = `
                    <div class="booking-card text-center">
                        <div class="text-red-500 text-4xl mb-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Error loading bookings</h3>
                        <p class="text-gray-600 mb-4">${error.message}</p>
                        <button onclick="loadBookings()" class="action-btn btn-view" style="max-width: 150px; margin: 0 auto;">
                            <i class="fas fa-redo mr-2"></i> Retry
                        </button>
                    </div>
                `;
            });
        }
        
        function updateStatistics(data) {
            document.getElementById('total-bookings').textContent = data.total;
            document.getElementById('pending-bookings').textContent = data.pending;
            document.getElementById('confirmed-bookings').textContent = data.confirmed;
            document.getElementById('completed-bookings').textContent = data.completed;
        }
        
        function renderBookingsCards(bookings) {
            const bookingsList = document.getElementById('bookings-list');
            
            const cardsHTML = bookings.map(booking => {
                // Get status badge
                let statusClass = 'status-pending';
                let statusIcon = 'fa-clock';
                
                switch(booking.bookingStatus) {
                    case 'confirmed':
                        statusClass = 'status-confirmed';
                        statusIcon = 'fa-check-circle';
                        break;
                    case 'completed':
                        statusClass = 'status-completed';
                        statusIcon = 'fa-flag-checkered';
                        break;
                    case 'cancelled':
                        statusClass = 'status-cancelled';
                        statusIcon = 'fa-times-circle';
                        break;
                    case 'refunded':
                        statusClass = 'status-refunded';
                        statusIcon = 'fa-undo';
                        break;
                }
                
                // Format prices
                const totalPrice = parseFloat(booking.totalPrice).toFixed(2);
                const totalPaid = parseFloat(booking.total_paid).toFixed(2);
                const remainingBalance = (parseFloat(totalPrice) - parseFloat(totalPaid)).toFixed(2);
                
                // Create accommodations list
                const accommodationsHTML = booking.accommodations.map(acc => `
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-700">${acc.name}</span>
                        <span class="font-semibold text-gray-800">₱${parseFloat(acc.price).toFixed(2)}</span>
                    </div>
                `).join('');
                
                return `
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="booking-info">
                                <h3>Booking #${booking.bookingID}</h3>
                                <div class="booking-meta">
                                    <span class="meta-item">
                                        <i class="far fa-calendar"></i>
                                        ${booking.formatted_event_start}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-users"></i>
                                        ${booking.numGuests} guest${booking.numGuests > 1 ? 's' : ''}
                                    </span>
                                    <span class="meta-item">
                                        <i class="far fa-clock"></i>
                                        ${booking.formatted_created_at}
                                    </span>
                                </div>
                            </div>
                            <span class="${statusClass} status-badge">
                                <i class="fas ${statusIcon}"></i>
                                ${booking.bookingStatus.charAt(0).toUpperCase() + booking.bookingStatus.slice(1)}
                            </span>
                        </div>
                        
                        <div class="booking-content">
                            <div class="booking-details">
                                <div class="detail-group">
                                    <h4><i class="fas fa-calendar-day"></i> Event Dates</h4>
                                    <div class="detail-content">
                                        ${booking.formatted_event_start} to ${booking.formatted_event_end}
                                    </div>
                                </div>
                                
                                <div class="detail-group">
                                    <h4><i class="fas fa-home"></i> Accommodations</h4>
                                    <div class="detail-content">
                                        ${booking.accommodations.length} item${booking.accommodations.length > 1 ? 's' : ''}
                                    </div>
                                </div>
                                
                                <div class="detail-group">
                                    <h4><i class="fas fa-star"></i> Booking Type</h4>
                                    <div class="detail-content">
                                        ${booking.eventType || 'Normal Booking'}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="price-summary">
                                <h4 class="font-semibold mb-3 text-lg">Payment Summary</h4>
                                <div class="price-item">
                                    <span>Total Amount</span>
                                    <span class="price-amount">₱${totalPrice}</span>
                                </div>
                                <div class="price-item">
                                    <span>Amount Paid</span>
                                    <span class="price-amount paid">₱${totalPaid}</span>
                                </div>
                                <div class="price-item total">
                                    <span>Remaining Balance</span>
                                    <span class="price-amount ${remainingBalance === '0.00' ? 'paid' : ''}">
                                        ₱${remainingBalance}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <button onclick="viewBooking(${booking.bookingID})" class="action-btn btn-view">
                                <i class="fas fa-eye mr-2"></i> View Details
                            </button>
                            ${(booking.bookingStatus === 'pending' || booking.bookingStatus === 'confirmed') ? `
                                <button onclick="cancelBooking(${booking.bookingID})" class="action-btn btn-cancel">
                                    <i class="fas fa-times mr-2"></i> Cancel Booking
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
            
            bookingsList.innerHTML = cardsHTML;
        }
        
        function showNoBookingsMessage() {
            const bookingsList = document.getElementById('bookings-list');
            
            const emptyMessage = currentStatus !== 'all' 
                ? `You don't have any ${currentStatus} bookings yet.`
                : "You haven't made any bookings yet.";
            
            const actionButton = currentStatus !== 'all' 
                ? `<button onclick="currentStatus = 'all'; document.querySelector('.filter-tab[data-status=\"all\"]').click();" class="action-btn btn-view" style="max-width: 200px; margin: 0 auto;">
                        <i class="fas fa-list mr-2"></i> View All Bookings
                   </button>`
                : `<a href="{{ route('roomBooking') }}" class="action-btn btn-view" style="max-width: 200px; margin: 0 auto; text-decoration: none;">
                        <i class="fas fa-plus mr-2"></i> Make a New Booking
                   </a>`;
            
            bookingsList.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h3 class="empty-title">No bookings found</h3>
                    <p class="empty-description">${emptyMessage}</p>
                    ${actionButton}
                </div>
            `;
        }
        
        function viewBooking(bookingId) {
            currentBookingId = bookingId;
            
            const modal = document.getElementById('booking-modal');
            const detailsContainer = document.getElementById('booking-details');
            
            // Show loading in modal
            detailsContainer.innerHTML = `
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Loading booking details...</p>
                </div>
            `;
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Fetch booking details
            fetch(`/api/my-bookings/${bookingId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    renderBookingDetails(data.booking);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                detailsContainer.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-500 text-4xl mb-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Error loading booking details</h3>
                        <p class="text-gray-600">${error.message}</p>
                    </div>
                `;
            });
        }
        
        function renderBookingDetails(booking) {
            const detailsContainer = document.getElementById('booking-details');
            
            // Get status badge
            let statusClass = 'status-pending';
            let statusIcon = 'fa-clock';
            
            switch(booking.bookingStatus) {
                case 'confirmed':
                    statusClass = 'status-confirmed';
                    statusIcon = 'fa-check-circle';
                    break;
                case 'completed':
                    statusClass = 'status-completed';
                    statusIcon = 'fa-flag-checkered';
                    break;
                case 'cancelled':
                    statusClass = 'status-cancelled';
                    statusIcon = 'fa-times-circle';
                    break;
            }
            
            // Format payment history
            const paymentsHTML = booking.payments && booking.payments.length > 0 
                ? booking.payments.map(payment => `
                    <div class="border border-gray-200 rounded-lg p-3 mb-2 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-sm">${payment.paymentReference || 'Payment'}</p>
                                <p class="text-xs text-gray-600">
                                    ${new Date(payment.paymentDate).toLocaleDateString('en-US', { 
                                        year: 'numeric', 
                                        month: 'short', 
                                        day: 'numeric'
                                    })} • 
                                    ${payment.paymentMethod || 'N/A'} • ${payment.paymentType || 'Payment'}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg">₱${parseFloat(payment.amountPaid || 0).toFixed(2)}</p>
                                <span class="${payment.paymentStatus === 'completed' ? 'status-confirmed' : 'status-pending'} status-badge" style="font-size: 0.75rem;">
                                    ${payment.paymentStatus || 'pending'}
                                </span>
                            </div>
                        </div>
                    </div>
                `).join('')
                : `<div class="text-center py-4 text-gray-500">
                    <i class="fas fa-receipt text-2xl mb-2"></i>
                    <p>No payment records found.</p>
                   </div>`;
            
            detailsContainer.innerHTML = `
                <div class="space-y-6">
                    <!-- Status and ID -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Booking #${booking.bookingID}</h3>
                            <p class="text-gray-600">${booking.eventType || 'Normal Booking'}</p>
                        </div>
                        <span class="${statusClass} status-badge">
                            <i class="fas ${statusIcon}"></i>
                            ${booking.bookingStatus.charAt(0).toUpperCase() + booking.bookingStatus.slice(1)}
                        </span>
                    </div>
                    
                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dates Card -->
                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                            <h4 class="font-semibold text-lg mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-yellow-500"></i>
                                Dates
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <i class="fas fa-play-circle text-green-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-sm text-gray-600">Event Start</p>
                                        <p class="text-gray-800">${booking.formatted_details?.event_start || 'N/A'}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-stop-circle text-red-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-sm text-gray-600">Event End</p>
                                        <p class="text-gray-800">${booking.formatted_details?.event_end || 'N/A'}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-purple-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-sm text-gray-600">Booked On</p>
                                        <p class="text-gray-800">${booking.formatted_details?.created_at || 'N/A'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Card -->
                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                            <h4 class="font-semibold text-lg mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fas fa-money-bill-wave text-yellow-500"></i>
                                Payment Summary
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Total Amount:</span>
                                    <span class="font-bold text-lg text-gray-900">${booking.formatted_details?.total_price || '₱0.00'}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-700">Amount Paid:</span>
                                    <span class="font-bold text-lg text-green-600">${booking.formatted_details?.total_paid || '₱0.00'}</span>
                                </div>
                                <div class="flex justify-between items-center border-t pt-3">
                                    <span class="font-medium text-gray-700">Remaining Balance:</span>
                                    <span class="font-bold text-lg ${booking.formatted_details?.remaining_balance === '₱0.00' ? 'text-green-600' : 'text-yellow-600'}">
                                        ${booking.formatted_details?.remaining_balance || '₱0.00'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accommodations -->
                    <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                        <h4 class="font-semibold text-lg mb-4 text-gray-800 flex items-center gap-2">
                            <i class="fas fa-home text-yellow-500"></i>
                            Accommodations
                        </h4>
                        <div class="space-y-3">
                            ${booking.cart?.items ? booking.cart.items.map(item => `
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3 last:border-0">
                                    <div>
                                        <h5 class="font-semibold text-gray-800">${item.unit?.unitName || 'N/A'}</h5>
                                        <p class="text-sm text-gray-600 flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas ${item.unit?.unitType === 'room' ? 'fa-bed' : 'fa-home'}"></i>
                                                ${item.unit?.unitType ? item.unit.unitType.charAt(0).toUpperCase() + item.unit.unitType.slice(1) : 'N/A'}
                                            </span>
                                            <span>•</span>
                                            <span>${booking.numGuests || 1} guest${booking.numGuests > 1 ? 's' : ''}</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-lg">₱${parseFloat(item.subtotalPrice || 0).toFixed(2)}</span>
                                        <p class="text-xs text-gray-500">Unit price</p>
                                    </div>
                                </div>
                            `).join('') : '<p class="text-gray-500">No accommodations found.</p>'}
                        </div>
                    </div>
                    
                    <!-- Payment History -->
                    <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                        <h4 class="font-semibold text-lg mb-4 text-gray-800 flex items-center gap-2">
                            <i class="fas fa-history text-yellow-500"></i>
                            Payment History
                        </h4>
                        <div class="space-y-2">
                            ${paymentsHTML}
                        </div>
                    </div>
                    
                    <!-- Special Requirements -->
                    ${booking.specialRequirements ? `
                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                            <h4 class="font-semibold text-lg mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fas fa-sticky-note text-yellow-500"></i>
                                Special Requirements
                            </h4>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-gray-700">${booking.specialRequirements}</p>
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- Actions -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-3">
                            ${(booking.bookingStatus === 'pending' || booking.bookingStatus === 'confirmed') ? `
                                <button onclick="cancelBooking(${booking.bookingID}, true)" class="action-btn btn-cancel">
                                    <i class="fas fa-times mr-2"></i> Cancel Booking
                                </button>
                            ` : ''}
                            <button onclick="closeModal()" class="action-btn btn-view">
                                <i class="fas fa-times mr-2"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function cancelBooking(bookingId, fromModal = false) {
            if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                return;
            }
            
            fetch(`/api/my-bookings/${bookingId}/cancel`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking cancelled successfully.');
                    if (fromModal) {
                        closeModal();
                    }
                    loadBookings(); // Refresh the list
                } else {
                    alert('Failed to cancel booking: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error cancelling booking: ' + error.message);
            });
        }
        
        function closeModal() {
            document.getElementById('booking-modal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.getElementById('booking-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>