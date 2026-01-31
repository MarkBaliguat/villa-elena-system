<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <title>Booking History - Villa Elena</title>
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

        /* ===== RESPONSIVE TABLE ===== */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        #historyTable {
            width: 100%;
            table-layout: auto;
        }

        #historyTable th,
        #historyTable td {
            white-space: nowrap;
            transition: padding 0.3s ease, font-size 0.3s ease;
        }

        /* Compact when sidebar expanded */
        #mainContent.ml-64 #historyTable th,
        #mainContent.ml-64 #historyTable td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        /* Spacious when sidebar collapsed */
        #mainContent.ml-24 #historyTable th,
        #mainContent.ml-24 #historyTable td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Column sizing */
        #historyTable th:nth-child(1), #historyTable td:nth-child(1) { min-width: 160px; }
        #historyTable th:nth-child(2), #historyTable td:nth-child(2) { min-width: 200px; }
        #historyTable th:nth-child(3), #historyTable td:nth-child(3) { min-width: 160px; }
        #historyTable th:nth-child(4), #historyTable td:nth-child(4) { min-width: 140px; }
        #historyTable th:nth-child(5), #historyTable td:nth-child(5) { min-width: 100px; }
        #historyTable th:nth-child(6), #historyTable td:nth-child(6) { min-width: 130px; }

        /* Allow wrapping on booking details column */
        #historyTable td:nth-child(2) {
            white-space: normal;
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .filter-bar .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 180px;
            max-width: 280px;
            transition: max-width 0.3s ease;
        }

        #mainContent.ml-24 .filter-bar .search-wrapper {
            max-width: 340px;
        }

        /* ===== EXPORT BUTTONS ===== */
        .export-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        #mainContent.ml-64 .export-btn {
            padding: 0.625rem 0.75rem;
            font-size: 0.8125rem;
        }

        #mainContent.ml-24 .export-btn {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
        }

        /* ===== HEADER ROW ===== */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        /* ===== MOBILE CARD VIEW ===== */
        .history-card {
            display: none;
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #3b82f6;
        }

        .history-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .history-card .card-body {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .history-card .card-item {
            display: flex;
            flex-direction: column;
        }

        .history-card .card-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .history-card .card-value {
            font-size: 0.875rem;
            color: #111827;
        }

        .history-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        /* Table row hover */
        .table-row-hover {
            transition: all 0.2s ease;
        }

        .table-row-hover:hover {
            background-color: #f9fafb !important;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ===== SHOW CARDS / HIDE TABLE ON MOBILE ===== */
        @media (max-width: 1024px) {
            #historyTable {
                display: none;
            }

            .history-card {
                display: block;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar .search-wrapper {
                max-width: 100% !important;
            }

            .filter-bar select {
                width: 100%;
            }

            .export-buttons {
                width: 100%;
                justify-content: space-between;
            }

            .export-btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* ===== TABLET ===== */
        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }

            .history-card .card-body {
                grid-template-columns: 1fr;
            }

            .export-buttons {
                flex-direction: column;
            }

            .export-btn {
                width: 100%;
            }

            .header-row {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* ===== EXTRA SMALL ===== */
        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }

            .history-card {
                padding: 0.75rem;
            }

            .filter-bar button {
                width: 100%;
            }
        }

        /* Pagination responsive */
        .pagination-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            justify-content: center;
        }

        @media (max-width: 640px) {
            .pagination-container button {
                min-width: 36px;
                padding: 0.5rem 0.625rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="page-container">
        {{-- Sidebar --}}
        @include('adminFolder.partials.sidebar')
        
        {{-- Main Content --}}
        <div class="p-8" id="mainContent">
            {{-- Header Section --}}
            <div class="mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Booking History</h1>
                        <p class="text-gray-500 text-sm mt-1">View completed and cancelled bookings</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs md:text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>

            {{-- Filters and Stats --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

                {{-- Top Row: Title --}}
                <div class="header-row">
                    <h2 class="text-xl font-semibold text-gray-800">Historical Records</h2>
                </div>

                {{-- Search and Filter --}}
                <form method="GET" action="" id="searchForm">
                    <div class="filter-bar mb-6">
                        <div class="search-wrapper">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                name="search"
                                id="searchInput"
                                placeholder="Search by guest name, email, or phone" 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <select id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="all">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button type="button" onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium">
                            Clear
                        </button>
                        {{-- Export Buttons --}}
                        <div class="export-buttons">
                            <button type="button" onclick="exportToCSV()" class="export-btn bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                <i class="fas fa-file-csv"></i>
                                <span class="hidden sm:inline">Export CSV</span>
                                <span class="sm:hidden">CSV</span>
                            </button>
                            <button type="button" onclick="printTable()" class="export-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                <i class="fas fa-print"></i>
                                <span class="hidden sm:inline">Print</span>
                                <span class="sm:hidden">Print</span>
                            </button>
                            <button type="button" onclick="exportToPDF()" class="export-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                <i class="fas fa-file-pdf"></i>
                                <span class="hidden sm:inline">Export PDF</span>
                                <span class="sm:hidden">PDF</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Desktop Table View --}}
                <div class="table-responsive">
                    <table class="w-full" id="historyTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Guest Info</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Booking Details</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Payment</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Units</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Status</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                    <p>Loading history...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div id="historyCardsContainer">
                    <!-- Cards generated here for mobile -->
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200 gap-4">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> results
                    </div>
                    <div class="pagination-container" id="pagination">
                        <!-- Pagination buttons generated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        });
        // ============================================
        // END SIDEBAR RESPONSIVE
        // ============================================

        // Setup CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Pagination variables
        let currentPage = 1;
        const perPage = 10;
        let totalRecords = 0;
        let allHistory = [];

        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Clear filters
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            loadHistory('all', '', 1);
        }

        // Load history data
        function loadHistory(status = 'all', search = '', page = 1) {
            const tbody = document.getElementById('historyTableBody');
            const cardsContainer = document.getElementById('historyCardsContainer');
            
            const loadingRow = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading history...</p>
                    </td>
                </tr>`;

            tbody.innerHTML = loadingRow;
            cardsContainer.innerHTML = `<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-500"></i></div>`;

            let url = `/admin/history/data?`;
            if (status !== 'all') url += `status=${status}&`;
            if (search) url += `search=${search}&`;
            url += `page=${page}&per_page=${perPage}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allHistory = data.data;
                        totalRecords = data.total || data.data.length;
                        currentPage = page;
                        
                        displayHistory(data.data);
                        displayHistoryCards(data.data);
                        updatePagination(data.total || data.data.length, page);
                        updateShowingText(data.data.length, page, data.total || data.data.length);
                    } else {
                        const errorMsg = `<tr><td colspan="6" class="text-center py-8 text-red-500">Error loading history</td></tr>`;
                        tbody.innerHTML = errorMsg;
                        cardsContainer.innerHTML = `<div class="text-center py-8 text-red-500">Error loading history</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-red-500">Error loading history</td></tr>`;
                    cardsContainer.innerHTML = `<div class="text-center py-8 text-red-500">Error loading history</div>`;
                });
        }

        // Display history in table (Desktop)
        function displayHistory(history) {
            const tbody = document.getElementById('historyTableBody');
            
            if (history.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No historical records found</p>
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = history.map(record => `
                <tr class="border-b border-gray-100 table-row-hover">
                    <td class="py-4 px-4">
                        <div>
                            <p class="font-medium text-gray-900">${record.guest_name}</p>
                            <p class="text-sm text-gray-500">${record.email}</p>
                            <p class="text-sm text-gray-500">${record.phone}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div>
                            <p class="text-sm"><span class="font-medium">Type:</span> ${record.booking_type}</p>
                            <p class="text-sm"><span class="font-medium">Check-in:</span> ${record.checkin_date}</p>
                            <p class="text-sm"><span class="font-medium">Check-out:</span> ${record.checkout_date || 'N/A'}</p>
                            <p class="text-sm"><span class="font-medium">Guests:</span> ${record.num_guests}</p>
                            <p class="text-sm"><span class="font-medium">Price:</span> ₱${parseFloat(record.total_price).toFixed(2)}</p>
                            ${record.event_type !== 'normal-booking' ? `<p class="text-sm"><span class="font-medium">Event:</span> ${record.event_type}</p>` : ''}
                            ${record.cancellation_reason ? `<p class="text-sm"><span class="font-medium">Cancel Reason:</span> ${record.cancellation_reason}</p>` : ''}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="space-y-1">
                            <p class="text-sm"><span class="font-medium">Total:</span> ₱${parseFloat(record.total_price).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Paid:</span> ₱${parseFloat(record.total_paid || 0).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Refunded:</span> ₱${parseFloat(record.total_refunded || 0).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Net Paid:</span> ₱${parseFloat(record.net_paid || 0).toFixed(2)}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm text-gray-700">${record.units}</p>
                    </td>
                    <td class="py-4 px-4">
                        <span class="status-badge ${getStatusColor(record.booking_status)}">
                            ${record.booking_status.toUpperCase()}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-600">
                            <p>Created: ${new Date(record.created_at).toLocaleDateString()}</p>
                            ${record.cancelled_at ? `<p>Cancelled: ${new Date(record.cancelled_at).toLocaleDateString()}</p>` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Display history as cards (Mobile)
        function displayHistoryCards(history) {
            const container = document.getElementById('historyCardsContainer');

            if (history.length === 0) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = history.map(record => `
                <div class="history-card">
                    <div class="card-header">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">${record.guest_name}</h3>
                            <p class="text-sm text-gray-500">${record.email}</p>
                            <p class="text-sm text-gray-500">${record.phone}</p>
                        </div>
                        <span class="status-badge ${getStatusColor(record.booking_status)}">
                            ${record.booking_status.toUpperCase()}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-item">
                            <span class="card-label">Booking Type</span>
                            <span class="card-value">${record.booking_type}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Check-in</span>
                            <span class="card-value">${record.checkin_date}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Check-out</span>
                            <span class="card-value">${record.checkout_date || 'N/A'}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Guests</span>
                            <span class="card-value">${record.num_guests}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Units</span>
                            <span class="card-value">${record.units}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Total Price</span>
                            <span class="card-value font-semibold text-blue-600">₱${parseFloat(record.total_price).toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Amount Paid</span>
                            <span class="card-value text-green-600">₱${parseFloat(record.total_paid || 0).toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Refunded</span>
                            <span class="card-value text-orange-600">₱${parseFloat(record.total_refunded || 0).toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Net Paid</span>
                            <span class="card-value font-semibold">₱${parseFloat(record.net_paid || 0).toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Created Date</span>
                            <span class="card-value">${new Date(record.created_at).toLocaleDateString()}</span>
                        </div>
                        ${record.event_type !== 'normal-booking' ? `
                        <div class="card-item">
                            <span class="card-label">Event Type</span>
                            <span class="card-value">${record.event_type}</span>
                        </div>` : ''}
                        ${record.cancellation_reason ? `
                        <div class="card-item" style="grid-column: 1 / -1;">
                            <span class="card-label">Cancellation Reason</span>
                            <span class="card-value">${record.cancellation_reason}</span>
                        </div>` : ''}
                        ${record.cancelled_at ? `
                        <div class="card-item">
                            <span class="card-label">Cancelled Date</span>
                            <span class="card-value">${new Date(record.cancelled_at).toLocaleDateString()}</span>
                        </div>` : ''}
                    </div>
                </div>
            `).join('');
        }

        // Update pagination
        function updatePagination(total, currentPage) {
            const totalPages = Math.ceil(total / perPage);
            const pagination = document.getElementById('pagination');

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = '';

            // Previous
            if (currentPage > 1) {
                html += `<button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${currentPage - 1})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-chevron-left"></i></button>`;
            }

            const maxVisible = window.innerWidth < 640 ? 3 : 5;
            let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(totalPages, start + maxVisible - 1);
            if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);

            // First + ellipsis
            if (start > 1) {
                html += `<button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), 1)" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">1</button>`;
                if (start > 2) html += `<span class="px-3 py-2 text-sm text-gray-400">...</span>`;
            }

            // Pages
            for (let i = start; i <= end; i++) {
                if (i === currentPage) {
                    html += `<button class="px-3 py-2 text-sm border border-blue-500 bg-blue-500 text-white rounded-lg transition">${i}</button>`;
                } else {
                    html += `<button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${i})" 
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">${i}</button>`;
                }
            }

            // Last + ellipsis
            if (end < totalPages) {
                if (end < totalPages - 1) html += `<span class="px-3 py-2 text-sm text-gray-400">...</span>`;
                html += `<button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${totalPages})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">${totalPages}</button>`;
            }

            // Next
            if (currentPage < totalPages) {
                html += `<button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${currentPage + 1})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-chevron-right"></i></button>`;
            }

            pagination.innerHTML = html;
        }

        // Update showing text
        function updateShowingText(showing, currentPage, total) {
            const from = ((currentPage - 1) * perPage) + 1;
            const to = Math.min(from + showing - 1, total);
            document.getElementById('showingFrom').textContent = total === 0 ? 0 : from;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('totalRecords').textContent = total;
        }

        // Get current filter values
        function getCurrentStatus() { return document.getElementById('statusFilter').value; }
        function getCurrentSearch() { return document.getElementById('searchInput').value; }

        // Status colors
        function getStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'completed': return 'bg-green-100 text-green-800';
                case 'cancelled': return 'bg-red-100 text-red-800';
                default:          return 'bg-gray-100 text-gray-800';
            }
        }

        // Export to CSV
        function exportToCSV() {
            if (allHistory.length === 0) { alert('No data to export'); return; }

            const headers = ['Guest Name','Email','Phone','Booking Type','Status','Check-in','Check-out','Guests','Units','Total Price','Amount Paid','Amount Refunded','Net Paid','Event Type','Cancellation Reason','Created Date','Cancelled Date'];
            const rows = allHistory.map(r => [
                `"${r.guest_name}"`,`"${r.email}"`,`"${r.phone}"`,`"${r.booking_type}"`,`"${r.booking_status}"`,
                `"${r.checkin_date}"`,`"${r.checkout_date || 'N/A'}"`,`"${r.num_guests}"`,`"${r.units}"`,
                `"₱${parseFloat(r.total_price).toFixed(2)}"`,
                `"₱${parseFloat(r.total_paid || 0).toFixed(2)}"`,
                `"₱${parseFloat(r.total_refunded || 0).toFixed(2)}"`,
                `"₱${parseFloat(r.net_paid || 0).toFixed(2)}"`,
                `"${r.event_type}"`,`"${r.cancellation_reason || 'N/A'}"`,
                `"${new Date(r.created_at).toLocaleDateString()}"`,
                `"${r.cancelled_at ? new Date(r.cancelled_at).toLocaleDateString() : 'N/A'}"`
            ]);

            const csv = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.setAttribute('href', URL.createObjectURL(blob));
            link.setAttribute('download', `booking_history_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print table
        function printTable() {
            if (allHistory.length === 0) { alert('No data to print'); return; }

            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html><html><head>
                    <title>Booking History Report - Villa Elena</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2d3748; text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        .status-completed { background-color: #d1fae5; color: #065f46; }
                        .status-cancelled { background-color: #fecaca; color: #991b1b; }
                        .print-date { text-align: right; margin-bottom: 20px; color: #6b7280; }
                    </style>
                </head><body>
                    <h1>Booking History Report - Villa Elena</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleString()}</div>
                    <table><thead><tr>
                        <th>Guest Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Status</th>
                        <th>Check-in</th><th>Check-out</th><th>Guests</th><th>Units</th>
                        <th>Total</th><th>Paid</th><th>Refunded</th><th>Net Paid</th>
                    </tr></thead><tbody>
                        ${allHistory.map(r => `<tr>
                            <td>${r.guest_name}</td><td>${r.email}</td><td>${r.phone}</td>
                            <td>${r.booking_type}</td>
                            <td><span class="status-${r.booking_status}">${r.booking_status.toUpperCase()}</span></td>
                            <td>${r.checkin_date}</td><td>${r.checkout_date || 'N/A'}</td>
                            <td>${r.num_guests}</td><td>${r.units}</td>
                            <td>₱${parseFloat(r.total_price).toFixed(2)}</td>
                            <td>₱${parseFloat(r.total_paid || 0).toFixed(2)}</td>
                            <td>₱${parseFloat(r.total_refunded || 0).toFixed(2)}</td>
                            <td>₱${parseFloat(r.net_paid || 0).toFixed(2)}</td>
                        </tr>`).join('')}
                    </tbody></table>
                    <div style="margin-top:20px;text-align:center;color:#6b7280;">Total Records: ${allHistory.length}</div>
                </body></html>`;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        }

        // Export to PDF
        function exportToPDF() {
            if (allHistory.length === 0) { alert('No data to export'); return; }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Booking History Report - Villa Elena', 14, 15);
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);

            const headers = ['Guest','Email','Phone','Type','Status','Check-in','Check-out','Guests','Units','Total','Paid','Refunded','Net'];
            const rows = allHistory.map(r => [
                r.guest_name, r.email, r.phone, r.booking_type, r.booking_status.toUpperCase(),
                r.checkin_date, r.checkout_date || 'N/A', r.num_guests, r.units,
                `₱${parseFloat(r.total_price).toFixed(2)}`,
                `₱${parseFloat(r.total_paid || 0).toFixed(2)}`,
                `₱${parseFloat(r.total_refunded || 0).toFixed(2)}`,
                `₱${parseFloat(r.net_paid || 0).toFixed(2)}`
            ]);

            doc.autoTable({
                head: [headers], body: rows, startY: 30,
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [59, 130, 246] },
                alternateRowStyles: { fillColor: [249, 250, 251] }
            });

            doc.save(`booking_history_${new Date().toISOString().split('T')[0]}.pdf`);
        }

        // Filter and search listeners
        document.getElementById('statusFilter').addEventListener('change', function() {
            loadHistory(this.value, document.getElementById('searchInput').value, 1);
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                loadHistory(getCurrentStatus(), this.value, 1);
            }, 500);
        });

        // Repaginate on resize
        window.addEventListener('resize', () => {
            if (totalRecords > 0) updatePagination(totalRecords, currentPage);
        });

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== HISTORY PAGE LOADED ===');
            loadHistory('all', '', 1);
        });
    </script>
</body>
</html>