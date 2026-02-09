<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Villa Elena</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== SIDEBAR RESPONSIVE LAYOUT ===== */
        .page-container {
            display: flex;
            min-height: 100vh;
        }

        #mainContent {
            flex: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }

        #mainContent.ml-24 {
            margin-left: 5.5rem;
            width: calc(100% - 5.5rem);
        }

        #mainContent.ml-64 {
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }

        /* Tablet */
        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }
        }

        /* Extra Small */
        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card .icon-wrapper {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover .icon-wrapper {
            transform: rotate(10deg) scale(1.1);
        }

        /* Responsive stat card text on small screens */
        @media (max-width: 640px) {
            .stat-card h3 {
                font-size: 1.5rem;
            }
            .stat-card .icon-wrapper {
                padding: 0.75rem;
            }
            .stat-card .icon-wrapper i {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .stat-card h3 {
                font-size: 1.25rem;
            }
            .stat-card p {
                font-size: 0.75rem;
            }
            .stat-card .icon-wrapper {
                padding: 0.5rem;
            }
            .stat-card .icon-wrapper i {
                font-size: 1rem;
            }
        }

        /* ===== CHART CARDS ===== */
        .chart-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            border-color: rgba(59, 130, 246, 0.1);
        }

        /* ===== CHART RESPONSIVE CONTAINERS ===== */
        .chart-container {
            position: relative;
            width: 100%;
            min-height: 280px;
            max-width: 100%;
        }

        /* Responsive chart heights */
        @media (min-width: 1024px) {
            .chart-container {
                min-height: 320px;
            }
        }

        @media (max-width: 768px) {
            .chart-container {
                min-height: 240px;
            }
        }

        @media (max-width: 640px) {
            .chart-container {
                min-height: 200px;
            }
            
            .chart-card h3 {
                font-size: 0.875rem;
            }
            
            .chart-card {
                padding: 0.75rem !important;
            }
        }

        @media (max-width: 480px) {
            .chart-container {
                min-height: 180px;
            }
            
            .chart-card h3 {
                font-size: 0.8rem;
            }
        }

        /* Make canvas responsive */
        .chart-container canvas {
            max-height: 380px;
        }

        @media (max-width: 768px) {
            .chart-container canvas {
                max-height: 300px;
            }
        }

        @media (max-width: 640px) {
            .chart-container canvas {
                max-height: 250px;
            }
        }

        @media (max-width: 480px) {
            .chart-container canvas {
                max-height: 200px;
            }
        }

        /* Limit doughnut chart size */
        .chart-container.doughnut-chart {
            max-width: 450px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .chart-container.doughnut-chart {
                max-width: 400px;
            }
        }

        @media (max-width: 640px) {
            .chart-container.doughnut-chart {
                max-width: 100%;
            }
        }

        /* ===== ACTIVITY ITEMS ===== */
        .activity-item {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .activity-item:hover {
            background: linear-gradient(to right, rgba(59, 130, 246, 0.05), transparent) !important;
            border-left-color: rgb(59, 130, 246);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        @media (max-width: 480px) {
            .activity-item {
                padding: 0.5rem !important;
            }
            .activity-item p {
                font-size: 0.8rem;
            }
            .activity-item .font-bold {
                font-size: 0.85rem;
            }
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .activity-item:hover .status-badge {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .status-pending   { background-color: #FEF3C7; color: #92400E; }
        .status-confirmed { background-color: #D1FAE5; color: #065F46; }
        .status-completed { background-color: #DBEAFE; color: #1E40AF; }
        .status-cancelled { background-color: #FEE2E2; color: #991B1B; }

        /* ===== DETAIL CARDS ===== */
        .detail-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.12);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .detail-card .detail-icon {
            transition: all 0.4s ease;
        }

        .detail-card:hover .detail-icon {
            transform: scale(1.2) rotate(5deg);
            filter: brightness(1.2);
        }

        .detail-item {
            transition: all 0.2s ease;
            padding: 0.75rem;
            margin: -0.75rem;
            border-radius: 0.5rem;
        }

        .detail-item:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateX(5px);
        }

        @media (max-width: 480px) {
            .detail-card h3 {
                font-size: 0.95rem;
            }
            .detail-card {
                padding: 1rem !important;
            }
            .detail-item {
                font-size: 0.85rem;
            }
        }

        /* ===== UPCOMING CHECK-INS ===== */
        .checkin-item {
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .checkin-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #10b981, #059669);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .checkin-item:hover {
            background: linear-gradient(to right, rgba(16, 185, 129, 0.05), transparent) !important;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .checkin-item:hover::before {
            transform: scaleY(1);
        }

        .checkin-item .guest-badge {
            transition: all 0.3s ease;
        }

        .checkin-item:hover .guest-badge {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        @media (max-width: 480px) {
            .checkin-item {
                padding: 0.5rem !important;
            }
            .checkin-item p {
                font-size: 0.8rem;
            }
            .checkin-item .guest-badge {
                font-size: 0.65rem;
                padding: 0.125rem 0.375rem;
            }
        }

        /* ===== LOADING SPINNER ===== */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== RESPONSIVE GRIDS ===== */
        /* Stat cards: 2 col on tablet, 1 col on mobile */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }

        /* Detail cards: single column on tablet and below */
        @media (max-width: 1024px) {
            .detail-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            .detail-grid {
                gap: 1rem !important;
            }
        }

        /* Bottom row: single column on tablet and below */
        @media (max-width: 1024px) {
            .bottom-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            .bottom-grid {
                gap: 1rem !important;
            }
        }

        /* Smooth text rendering */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <div class="page-container">
        @include('adminFolder.partials.sidebar')

        <div class="p-8" id="mainContent">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-800">Dashboard Overview</h1>
                        <p class="text-gray-600 mt-2">Welcome back! Here's what's happening today.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs md:text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Loading Spinner --}}
            <div id="loading" class="flex justify-center items-center py-20">
                <div class="loading-spinner"></div>
            </div>

            {{-- Dashboard Content --}}
            <div id="dashboard-content" class="hidden">
                
                {{-- Summary Stats Cards --}}
                <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    {{-- Total Revenue --}}
                    <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Revenue</p>
                                <h3 class="text-3xl font-bold mt-2">₱<span id="total-revenue">0</span></h3>
                                <p class="text-blue-100 text-xs mt-2">This month: ₱<span id="monthly-revenue">0</span></p>
                            </div>
                            <div class="icon-wrapper bg-white bg-opacity-20 p-4 rounded-lg">
                                <i class="fas fa-peso-sign text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Total Bookings --}}
                    <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Bookings</p>
                                <h3 class="text-3xl font-bold mt-2"><span id="total-bookings">0</span></h3>
                                <p class="text-purple-100 text-xs mt-2">Pending: <span id="pending-bookings">0</span></p>
                            </div>
                            <div class="icon-wrapper bg-white bg-opacity-20 p-4 rounded-lg">
                                <i class="fas fa-calendar-check text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Available Units --}}
                    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Available Units</p>
                                <h3 class="text-3xl font-bold mt-2"><span id="available-units">0</span></h3>
                                <p class="text-green-100 text-xs mt-2">Total: <span id="total-units">0</span> units</p>
                            </div>
                            <div class="icon-wrapper bg-white bg-opacity-20 p-4 rounded-lg">
                                <i class="fas fa-home text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Today's Activity --}}
                    <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-xl shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Today's Activity</p>
                                <h3 class="text-3xl font-bold mt-2"><span id="today-checkins">0</span> / <span id="today-checkouts">0</span></h3>
                                <p class="text-orange-100 text-xs mt-2">Check-ins / Check-outs</p>
                            </div>
                            <div class="icon-wrapper bg-white bg-opacity-20 p-4 rounded-lg">
                                <i class="fas fa-clock text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row --}}
                <div class="bottom-grid grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    {{-- Revenue Chart --}}
                    <div class="chart-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                            Revenue Trends (Last 6 Months)
                        </h3>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    {{-- Booking Status Chart --}}
                    <div class="chart-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                            Booking Status Distribution
                        </h3>
                        <div class="chart-container doughnut-chart">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Detailed Stats Grid --}}
                <div class="detail-grid grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {{-- Units Breakdown --}}
                    <div class="detail-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="detail-icon fas fa-building text-indigo-500 mr-2 inline-block"></i>
                            Units Overview
                        </h3>
                        <div class="space-y-2">
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Rooms</span>
                                <span class="font-bold text-gray-800" id="rooms-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Cottages</span>
                                <span class="font-bold text-gray-800" id="cottages-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Special Units</span>
                                <span class="font-bold text-gray-800" id="special-count">0</span>
                            </div>
                            <hr>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Blocked</span>
                                <span class="font-bold text-red-600" id="blocked-count">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Types --}}
                    <div class="detail-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="detail-icon fas fa-list text-teal-500 mr-2 inline-block"></i>
                            Booking Types
                        </h3>
                        <div class="space-y-2">
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Day Use</span>
                                <span class="font-bold text-gray-800" id="dayuse-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Overnight</span>
                                <span class="font-bold text-gray-800" id="overnight-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Special Events</span>
                                <span class="font-bold text-gray-800" id="specialevent-count">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Users Stats --}}
                    <div class="detail-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="detail-icon fas fa-users text-pink-500 mr-2 inline-block"></i>
                            Users Overview
                        </h3>
                        <div class="space-y-2">
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Total Guests</span>
                                <span class="font-bold text-gray-800" id="guests-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Staff Members</span>
                                <span class="font-bold text-gray-800" id="staff-count">0</span>
                            </div>
                            <div class="detail-item flex justify-between items-center">
                                <span class="text-gray-600">Managers</span>
                                <span class="font-bold text-gray-800" id="managers-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bottom-grid grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Recent Bookings --}}
                    <div class="chart-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            Recent Bookings
                        </h3>
                        <div id="recent-bookings" class="space-y-3">
                            <!-- populated by JS -->
                        </div>
                    </div>

                    {{-- Upcoming Check-ins --}}
                    <div class="chart-card bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                            Upcoming Check-ins (Next 7 Days)
                        </h3>
                        <div id="upcoming-checkins" class="space-y-3">
                            <!-- populated by JS -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let revenueChart = null;
        let bookingStatusChart = null;

        // ============================================
        // RESPONSIVE CHART CONFIGURATION
        // ============================================
        function getResponsiveChartOptions() {
            const width = window.innerWidth;
            const isExtraSmall = width < 480;
            const isMobile = width < 640;
            const isTablet = width < 1024;
            
            return {
                fontSize: isExtraSmall ? 8 : isMobile ? 9 : isTablet ? 10 : 12,
                legendPosition: isMobile ? 'bottom' : 'right',
                aspectRatio: isExtraSmall ? 1 : isMobile ? 1.1 : isTablet ? 1.3 : 1.8,
                legendPadding: isExtraSmall ? 8 : isMobile ? 10 : 15,
                legendBoxWidth: isExtraSmall ? 25 : isMobile ? 30 : 40,
                pointRadius: isExtraSmall ? 2 : isMobile ? 3 : 4,
                pointHoverRadius: isExtraSmall ? 4 : isMobile ? 5 : 6
            };
        }

        // ============================================
        // SIDEBAR RESPONSIVE
        // ============================================
        window.addEventListener('sidebarToggled', (event) => {
            const mainContent = document.getElementById('mainContent');
            if (event.detail.collapsed) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            } else {
                mainContent.classList.remove('ml-24');
                mainContent.classList.add('ml-64');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const savedState = localStorage.getItem('sidebarState');
            const mainContent = document.getElementById('mainContent');
            if (savedState === 'collapsed') {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }

            fetchDashboardData();
        });

        // ============================================
        // WINDOW RESIZE HANDLER FOR CHARTS
        // ============================================
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (revenueChart) {
                    revenueChart.options = getRevenueChartOptions();
                    revenueChart.update();
                }
                if (bookingStatusChart) {
                    bookingStatusChart.options = getBookingStatusChartOptions();
                    bookingStatusChart.update();
                }
            }, 250);
        });

        // ============================================
        // END SIDEBAR RESPONSIVE
        // ============================================

        // Fetch dashboard data
        async function fetchDashboardData() {
            try {
                const response = await fetch('/admin/dashboard/stats', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch data');

                const data = await response.json();
                populateDashboard(data);
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
                document.getElementById('loading').innerHTML = `
                    <div class="text-center text-red-500">
                        <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                        <p>Failed to load dashboard data. Please refresh the page.</p>
                    </div>
                `;
            }
        }

        function populateDashboard(data) {
            // Summary Stats
            document.getElementById('total-revenue').textContent      = formatNumber(data.revenue.total);
            document.getElementById('monthly-revenue').textContent    = formatNumber(data.revenue.monthly);
            document.getElementById('total-bookings').textContent     = data.summary.total_bookings;
            document.getElementById('pending-bookings').textContent   = data.summary.pending_bookings;
            document.getElementById('available-units').textContent    = data.units.available;
            document.getElementById('total-units').textContent        = data.units.total;
            document.getElementById('today-checkins').textContent     = data.summary.today_check_ins;
            document.getElementById('today-checkouts').textContent    = data.summary.today_check_outs;

            // Units
            document.getElementById('rooms-count').textContent     = data.units.rooms;
            document.getElementById('cottages-count').textContent  = data.units.cottages;
            document.getElementById('special-count').textContent   = data.units.special;
            document.getElementById('blocked-count').textContent   = data.units.blocked;

            // Booking Types
            document.getElementById('dayuse-count').textContent       = data.booking_types.day_use;
            document.getElementById('overnight-count').textContent    = data.booking_types.overnight;
            document.getElementById('specialevent-count').textContent = data.booking_types.special_event;

            // Users
            document.getElementById('guests-count').textContent   = data.users.guests;
            document.getElementById('staff-count').textContent    = data.users.staff;
            document.getElementById('managers-count').textContent = data.users.managers;

            // Lists
            populateRecentBookings(data.recent_bookings);
            populateUpcomingCheckIns(data.upcoming_check_ins);

            // Charts
            createRevenueChart(data.monthly_revenue_chart);
            createBookingStatusChart(data.booking_status_chart);

            // Show content
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('dashboard-content').classList.remove('hidden');
        }

        function populateRecentBookings(bookings) {
            const container = document.getElementById('recent-bookings');
            if (bookings.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No recent bookings</p>';
                return;
            }

            container.innerHTML = bookings.map(booking => `
                <div class="activity-item flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${booking.guest_name}</p>
                        <p class="text-sm text-gray-600">${booking.booking_type} • ${booking.created_at}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">₱${formatNumber(booking.total_price)}</p>
                        <span class="status-badge status-${booking.status}">${booking.status}</span>
                    </div>
                </div>
            `).join('');
        }

        function populateUpcomingCheckIns(checkIns) {
            const container = document.getElementById('upcoming-checkins');
            if (checkIns.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No upcoming check-ins</p>';
                return;
            }

            container.innerHTML = checkIns.map(checkIn => `
                <div class="checkin-item p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-semibold text-gray-800">${checkIn.guest_name}</p>
                        <span class="guest-badge text-xs bg-green-100 text-green-800 px-2 py-1 rounded">${checkIn.num_guests} guests</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-calendar mr-1"></i>
                        ${checkIn.check_in_date} → ${checkIn.check_out_date}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-home mr-1"></i>
                        ${checkIn.units}
                    </p>
                </div>
            `).join('');
        }

        function getRevenueChartOptions() {
            const responsive = getResponsiveChartOptions();
            
            return {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: responsive.aspectRatio,
                plugins: {
                    legend: { 
                        display: false 
                    },
                    tooltip: {
                        titleFont: {
                            size: responsive.fontSize + 2
                        },
                        bodyFont: {
                            size: responsive.fontSize
                        },
                        padding: window.innerWidth < 480 ? 6 : 10
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: responsive.fontSize
                            },
                            maxRotation: window.innerWidth < 480 ? 65 : 45,
                            minRotation: window.innerWidth < 480 ? 45 : 0,
                            autoSkip: true,
                            maxTicksLimit: window.innerWidth < 480 ? 4 : 6
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: responsive.fontSize
                            },
                            callback: function(value) {
                                if (window.innerWidth < 480) {
                                    // Shorter format for very small screens
                                    if (value >= 1000000) {
                                        return '₱' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return '₱' + (value / 1000).toFixed(0) + 'K';
                                    }
                                    return '₱' + value;
                                }
                                return '₱' + formatNumber(value);
                            },
                            maxTicksLimit: window.innerWidth < 480 ? 5 : 8
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            };
        }

        function getBookingStatusChartOptions() {
            const responsive = getResponsiveChartOptions();
            
            return {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: responsive.aspectRatio,
                plugins: {
                    legend: { 
                        position: responsive.legendPosition,
                        labels: {
                            font: {
                                size: responsive.fontSize
                            },
                            padding: responsive.legendPadding,
                            boxWidth: responsive.legendBoxWidth,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        // Truncate labels on very small screens
                                        if (window.innerWidth < 480 && label.length > 10) {
                                            label = label.substring(0, 8) + '...';
                                        }
                                        return {
                                            text: `${label} (${value})`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        titleFont: {
                            size: responsive.fontSize + 2
                        },
                        bodyFont: {
                            size: responsive.fontSize
                        },
                        padding: window.innerWidth < 480 ? 6 : 10
                    }
                },
                cutout: window.innerWidth < 480 ? '50%' : '60%'
            };
        }

        function createRevenueChart(data) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const responsive = getResponsiveChartOptions();
            if (revenueChart) revenueChart.destroy();
            
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.month),
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: data.map(d => d.revenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: window.innerWidth < 480 ? 1.5 : 2,
                        pointRadius: responsive.pointRadius,
                        pointHoverRadius: responsive.pointHoverRadius
                    }]
                },
                options: getRevenueChartOptions()
            });
        }

        function createBookingStatusChart(data) {
            const ctx = document.getElementById('bookingStatusChart').getContext('2d');
            if (bookingStatusChart) bookingStatusChart.destroy();
            
            bookingStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(d => d.status),
                    datasets: [{
                        data: data.map(d => d.count),
                        backgroundColor: [
                            'rgb(251, 191, 36)',
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: getBookingStatusChartOptions()
            });
        }

        function formatNumber(num) {
            return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Refresh every 5 minutes
        setInterval(fetchDashboardData, 300000);
    </script>
</body>
</html>