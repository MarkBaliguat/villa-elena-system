<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <title>Booking History - Villa Elena</title>
    <style>
        /* ===== MODAL STYLES ===== */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.2s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: black;
        }

        .modal-close {
            color: gray;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.2s;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-close:hover {
           color: black;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Enhanced Section Headers */
        .section-header {
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-header i {
            color: #667eea;
            font-size: 1.125rem;
        }

        /* Enhanced Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            padding: 1rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.2s;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 0.9375rem;
            color: #1e293b;
            font-weight: 600;
        }

        /* Enhanced Payment Summary */
        .payment-summary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #93c5fd;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #bfdbfe;
        }

        .payment-row:last-child {
            border-bottom: none;
            border-top: 2px solid #3b82f6;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .payment-label {
            font-size: 0.875rem;
            color: #1e40af;
            font-weight: 600;
        }

        .payment-value {
            font-size: 0.875rem;
            color: #1e3a8a;
            font-weight: 600;
        }

        /* Enhanced Cancellation Box */
        .cancellation-box {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #fca5a5;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        /* Enhanced Action Buttons */
        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
        }

        .btn-modal-close {
            background: #64748b;
            color: white;
        }

        .btn-modal-close:hover {
            background: #475569;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
        }

        .btn-modal-print {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-modal-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

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
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #historyTable {
            width: 100%;
            min-width: 900px;
        }

        #historyTable th,
        #historyTable td {
            white-space: nowrap;
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
        }

        #historyTable th {
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* ===== MOBILE CARD VIEW ===== */
        .history-card {
            display: none;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 3px solid #3b82f6;
            transition: all 0.2s ease;
        }

        .history-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
        }

        .history-card .card-header:hover {
            background-color: #f9fafb;
        }

        .history-card .card-body {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            cursor: pointer;
        }

        .history-card .card-item {
            display: flex;
            flex-direction: column;
        }

        .history-card .card-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .history-card .card-value {
            font-size: 0.875rem;
            color: #111827;
        }

        /* Table row hover - only on button */
        .table-row {
            transition: background-color 0.2s;
        }

        .table-row:hover {
            background-color: #f9fafb !important;
        }

        .view-btn {
            cursor: pointer;
            transition: all 0.2s;
        }

        .view-btn:hover {
            transform: scale(1.05);
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

        /* Pagination Styles */
        .pagination-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pagination-btn {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .pagination-btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-ellipsis {
            padding: 0 0.5rem;
            color: #9ca3af;
        }

        /* ===== RESPONSIVE FILTER BAR ===== */
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-wrapper {
            position: relative;
            width: 280px;
            flex-shrink: 0;
        }

        .search-wrapper input {
            width: 100%;
            padding-left: 2.5rem;
            padding-right: 1rem;
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .search-wrapper i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .status-filter {
            min-width: 140px;
        }

        .clear-btn {
            white-space: nowrap;
        }

        .export-buttons {
            display: flex;
            gap: 0.5rem;
           
        }

        .export-btn {
            white-space: nowrap;
        }

        .export-btn .btn-text-full {
            display: inline;
        }

        .export-btn .btn-text-short {
            display: none;
        }

        /* Show short text on smaller screens */
        @media (max-width: 1280px) {
            .export-btn .btn-text-full {
                display: none;
            }

            .export-btn .btn-text-short {
                display: inline;
            }
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        @media (max-width: 1024px) {
            .table-container {
                display: none;
            }

            .history-card {
                display: block;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                width: 100%;
            }

            .status-filter,
            .clear-btn {
                width: 100%;
            }

            .export-buttons {
                width: 100%;
                margin-left: 0;
            }

            .export-btn {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }

            .history-card .card-body {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                max-height: 85vh;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column-reverse;
            }

            .btn-modal {
                width: 100%;
                justify-content: center;
            }

            .payment-row {
                flex-direction: column;
                gap: 0.25rem;
            }
        }

        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }

            .pagination-info {
                font-size: 0.75rem;
            }

            .export-buttons {
                flex-direction: column;
            }

            .export-btn {
                width: 100%;
            }
        }

        /* Enhanced badge colors */
        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .info-highlight {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">History Records</h2>
                </div>

                {{-- Search and Filter --}}
                <form method="GET" action="" id="searchForm">
                    <div class="filter-bar">
                        <div class="search-wrapper">
                            <i class="fas fa-search"></i>
                            <input 
                                type="text" 
                                name="search"
                                id="searchInput"
                                placeholder="Search guest..." 
                            >
                        </div>
                        <select id="statusFilter" class="status-filter px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-sm">
                            <option value="all">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button type="button" onclick="clearFilters()" class="clear-btn bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm">
                            Clear
                        </button>
                        {{-- Export Buttons --}}
                        <div class="export-buttons">
                            <button type="button" onclick="exportToCSV()" class="export-btn bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center justify-center gap-2 transition text-sm">
                                <i class="fas fa-file-csv"></i>
                                <span class="btn-text-full">Export CSV</span>
                                <span class="btn-text-short">CSV</span>
                            </button>
                            <button type="button" onclick="printTable()" class="export-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center justify-center gap-2 transition text-sm">
                                <i class="fas fa-print"></i>
                                <span class="btn-text-full">Print</span>
                                <span class="btn-text-short">Print</span>
                            </button>
                            <button type="button" onclick="exportToPDF()" class="export-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center justify-center gap-2 transition text-sm">
                                <i class="fas fa-file-pdf"></i>
                                <span class="btn-text-full">Export PDF</span>
                                <span class="btn-text-short">PDF</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Desktop Table View --}}
                <div class="table-container">
                    <table class="w-full" id="historyTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Guest Info</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Booking Type</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Check-in</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Units</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Action</th>
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

                {{-- Enhanced Pagination --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-6 pt-6 border-t border-gray-200 gap-4">
                    <div class="pagination-info">
                        <span>Showing <strong id="showingFrom">0</strong> to <strong id="showingTo">0</strong> of <strong id="totalRecords">0</strong> results</span>
                    </div>
                    <div class="pagination-controls" id="paginationControls">
                        <!-- Pagination buttons generated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Details Modal --}}
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="text-xl font-bold">Booking Details</h2>
                </div>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-12">
                    <div class="inline-block">
                        <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-gray-600 font-medium">Loading details...</p>
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

        // Setup CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Pagination variables
        let currentPage = 1;
        const perPage = 5;
        let totalRecords = 0;
        let allHistory = [];
        let filteredHistory = [];

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
            currentPage = 1;
            applyFilters();
        }

        // Load history data
        function loadHistory() {
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

            fetch(`/admin/history/data`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allHistory = data.data;
                        totalRecords = data.data.length;
                        applyFilters();
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

        // Apply filters and pagination
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();

            // Filter the data
            filteredHistory = allHistory.filter(record => {
                const matchesStatus = status === 'all' || record.booking_status === status;
                const matchesSearch = !search || 
                    record.guest_name.toLowerCase().includes(search) ||
                    record.email.toLowerCase().includes(search) ||
                    record.phone.toLowerCase().includes(search);
                
                return matchesStatus && matchesSearch;
            });

            // Update total records
            totalRecords = filteredHistory.length;

            // Reset to page 1 if current page is beyond available pages
            const totalPages = Math.ceil(totalRecords / perPage);
            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            } else if (totalPages === 0) {
                currentPage = 1;
            }

            // Get paginated data
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            const paginatedData = filteredHistory.slice(startIndex, endIndex);

            // Display data
            displayHistory(paginatedData);
            displayHistoryCards(paginatedData);
            updatePagination();
            updateShowingText(paginatedData.length);
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
                <tr class="border-b border-gray-100 table-row">
                    <td class="py-4 px-4">
                        <div>
                            <p class="font-medium text-gray-900">${record.guest_name}</p>
                            <p class="text-sm text-gray-500">${record.email}</p>
                            <p class="text-sm text-gray-500">${record.phone}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm font-medium">${record.booking_type}</p>
                        <p class="text-sm text-gray-500">${record.event_type !== 'normal-booking' ? record.event_type : ''}</p>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm">${record.checkin_date}</p>
                        <p class="text-sm text-gray-500">${record.checkout_date || 'N/A'}</p>
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
                        <button onclick="viewDetails(${record.bookingID})" class="view-btn text-blue-600 hover:text-blue-800 font-medium text-sm">
                            <i class="fas fa-eye mr-1"></i> View
                        </button>
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
                <div class="history-card" onclick="viewDetails(${record.bookingID})">
                    <div class="card-header">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-base">${record.guest_name}</h3>
                            <p class="text-sm text-gray-500">${record.email}</p>
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
                            <span class="card-label">Units</span>
                            <span class="card-value">${record.units}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Total Price</span>
                            <span class="card-value font-semibold text-blue-600">₱${parseFloat(record.total_price).toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // View booking details in modal
        function viewDetails(bookingID) {
            const modal = document.getElementById('viewModal');
            const modalBody = document.getElementById('modalBody');
            
            modal.classList.add('active');
            modalBody.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>
                    <p class="mt-2 text-gray-600">Loading details...</p>
                </div>`;

            fetch(`/admin/history/data?booking_id=${bookingID}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const record = data.data[0];
                        displayModalContent(record);
                    } else {
                        modalBody.innerHTML = `<div class="text-center py-8 text-red-500">Error loading details</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = `<div class="text-center py-8 text-red-500">Error loading details</div>`;
                });
        }

        // Display modal content with enhanced design
        function displayModalContent(record) {
            const modalBody = document.getElementById('modalBody');
            
            modalBody.innerHTML = `
                <!-- Guest Information -->
                <div class="mb-6">
                    <div class="section-header">
                        <i class="fas fa-user"></i>
                        Guest Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div class="info-value">${record.guest_name}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">${record.email}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">${record.phone}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Guests</div>
                            <div class="info-value">${record.num_guests}</div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="mb-6">
                    <div class="section-header">
                        <i class="fas fa-calendar-check"></i>
                        Booking Details
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Booking ID</div>
                            <div class="info-value"><span class="badge-primary">#${record.bookingID}</span></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Type</div>
                            <div class="info-value">${record.booking_type}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge ${getStatusColor(record.booking_status)}">
                                    ${record.booking_status.toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Check-in</div>
                            <div class="info-value">${new Date(record.checkin_date).toLocaleDateString()}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Check-out</div>
                            <div class="info-value">${record.checkout_date ? new Date(record.checkout_date).toLocaleDateString() : 'N/A'}</div>
                        </div>
                        ${record.event_type !== 'normal-booking' ? `
                        <div class="info-item">
                            <div class="info-label">Event</div>
                            <div class="info-value">${record.event_type}</div>
                        </div>
                        ` : ''}
                        ${record.gcash_payment_intent_id ? `
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">GCash Payment Intent ID</div>
                            <div class="info-value"><span class="info-highlight">${record.gcash_payment_intent_id}</span></div>
                        </div>
                        ` : ''}
                        ${record.payment_reference ? `
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Payment Reference</div>
                            <div class="info-value"><span class="info-highlight">${record.payment_reference}</span></div>
                        </div>
                        ` : ''}
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Units</div>
                            <div class="info-value">${record.units}</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="mb-6">
                    <div class="section-header">
                        <i class="fas fa-money-bill-wave"></i>
                        Payment Summary
                    </div>
                    <div class="payment-summary">
                        <div class="payment-row">
                            <span class="payment-label">Total Price</span>
                            <span class="payment-value">₱${parseFloat(record.total_price).toFixed(2)}</span>
                        </div>
                        <div class="payment-row">
                            <span class="payment-label">Amount Paid</span>
                            <span class="payment-value">₱${parseFloat(record.total_paid || 0).toFixed(2)}</span>
                        </div>
                        <div class="payment-row">
                            <span class="payment-label">Amount Refunded</span>
                            <span class="payment-value">₱${parseFloat(record.total_refunded || 0).toFixed(2)}</span>
                        </div>
                        <div class="payment-row">
                            <span class="payment-label">Net Paid</span>
                            <span class="payment-value">₱${parseFloat(record.net_paid || 0).toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                ${record.cancellation_reason ? `
                <!-- Cancellation Details -->
                <div class="mb-6">
                    <div class="section-header">
                        <i class="fas fa-ban"></i>
                        Cancellation Details
                    </div>
                    <div class="cancellation-box">
                        <div class="info-item">
                            <div class="info-label">Reason</div>
                            <div class="info-value">${record.cancellation_reason}</div>
                        </div>
                        ${record.cancelled_at ? `
                        <div class="info-item" style="margin-top: 0.75rem;">
                            <div class="info-label">Cancelled On</div>
                            <div class="info-value">${new Date(record.cancelled_at).toLocaleString()}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                ` : ''}

                <!-- Record Info -->
                <div class="mb-4">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        Record Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Created</div>
                            <div class="info-value">${new Date(record.created_at).toLocaleString()}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="modal-actions">
                    <button onclick="printBookingDetails(${record.bookingID})" class="btn-modal btn-modal-print">
                        <i class="fas fa-print"></i>
                        Print Details
                    </button>
                    <button onclick="closeModal()" class="btn-modal btn-modal-close">
                        <i class="fas fa-times"></i>
                        Close
                    </button>
                </div>
            `;
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById('viewModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('viewModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Update pagination
        function updatePagination() {
            const totalPages = Math.ceil(totalRecords / perPage);
            const controls = document.getElementById('paginationControls');

            if (totalPages <= 1) {
                controls.innerHTML = '';
                return;
            }

            let html = '';

            // Previous button
            html += `
                <button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} 
                    onclick="goToPage(${currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            const maxVisible = window.innerWidth < 640 ? 3 : 5;
            let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(totalPages, start + maxVisible - 1);
            if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);

            // First page + ellipsis
            if (start > 1) {
                html += `<button class="pagination-btn" onclick="goToPage(1)">1</button>`;
                if (start > 2) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
            }

            // Page numbers
            for (let i = start; i <= end; i++) {
                html += `
                    <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                        onclick="goToPage(${i})">
                        ${i}
                    </button>
                `;
            }

            // Last page + ellipsis
            if (end < totalPages) {
                if (end < totalPages - 1) {
                    html += `<span class="pagination-ellipsis">...</span>`;
                }
                html += `<button class="pagination-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }

            // Next button
            html += `
                <button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} 
                    onclick="goToPage(${currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            controls.innerHTML = html;
        }

        // Go to specific page
        function goToPage(page) {
            currentPage = page;
            applyFilters();
        }

        // Update showing text
        function updateShowingText(showing) {
            const from = totalRecords === 0 ? 0 : ((currentPage - 1) * perPage) + 1;
             const to = totalRecords === 0 ? 0 : Math.min(from + showing - 1, totalRecords);
            document.getElementById('showingFrom').textContent = from;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('totalRecords').textContent = totalRecords;
        }

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
            if (filteredHistory.length === 0) { alert('No data to export'); return; }

            const headers = ['Guest Name','Email','Phone','Booking Type','Status','Check-in','Check-out','Guests','Units','Total Price','Amount Paid','Amount Refunded','Net Paid','Event Type','GCash Intent ID','Payment Reference','Cancellation Reason','Created Date','Cancelled Date'];
            const rows = filteredHistory.map(r => [
                `"${r.guest_name}"`,`"${r.email}"`,`"${r.phone}"`,`"${r.booking_type}"`,`"${r.booking_status}"`,
                `"${r.checkin_date}"`,`"${r.checkout_date || 'N/A'}"`,`"${r.num_guests}"`,`"${r.units}"`,
                `"₱${parseFloat(r.total_price).toFixed(2)}"`,
                `"₱${parseFloat(r.total_paid || 0).toFixed(2)}"`,
                `"₱${parseFloat(r.total_refunded || 0).toFixed(2)}"`,
                `"₱${parseFloat(r.net_paid || 0).toFixed(2)}"`,
                `"${r.event_type}"`,
                `"${r.gcash_payment_intent_id || 'N/A'}"`,
                `"${r.payment_reference || 'N/A'}"`,
                `"${r.cancellation_reason || 'N/A'}"`,
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
            if (filteredHistory.length === 0) { alert('No data to print'); return; }

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
                        ${filteredHistory.map(r => `<tr>
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
                    <div style="margin-top:20px;text-align:center;color:#6b7280;">Total Records: ${filteredHistory.length}</div>
                </body></html>`;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        }

        // Export to PDF
        function exportToPDF() {
            if (filteredHistory.length === 0) { alert('No data to export'); return; }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text('Booking History Report - Villa Elena', 14, 15);
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);

            const headers = ['Guest','Email','Phone','Type','Status','Check-in','Check-out','Guests','Units','Total','Paid','Refunded','Net'];
            const rows = filteredHistory.map(r => [
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

        // Print booking details
        function printBookingDetails(bookingID) {
            const record = allHistory.find(r => r.bookingID === bookingID);
            if (!record) {
                alert('Booking details not found');
                return;
            }

            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Booking Details - #${record.bookingID}</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { font-family: Arial, sans-serif; padding: 30px; color: #1f2937; }
                        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #667eea; }
                        .header h1 { font-size: 24px; margin-bottom: 5px; color: #667eea; }
                        .header p { color: #6b7280; font-size: 14px; }
                        .section { margin-bottom: 20px; }
                        .section-title { font-size: 14px; font-weight: 700; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #667eea; color: #1f2937; }
                        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
                        .info-item { padding: 10px; background: #f9fafb; border-radius: 6px; border-left: 3px solid #667eea; }
                        .info-label { font-size: 11px; color: #6b7280; margin-bottom: 3px; font-weight: 600; }
                        .info-value { font-size: 13px; color: #1f2937; font-weight: 600; }
                        .payment-box { background: #eff6ff; border: 2px solid #93c5fd; border-radius: 8px; padding: 15px; }
                        .payment-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #bfdbfe; }
                        .payment-row:last-child { border-bottom: none; border-top: 2px solid #3b82f6; margin-top: 8px; padding-top: 10px; font-weight: 700; }
                        .footer { margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px; }
                        .badge { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Villa Elena Resort</h1>
                        <p>Booking Details - Reference <span class="badge">#${record.bookingID}</span></p>
                        <p>Printed on ${new Date().toLocaleString()}</p>
                    </div>

                    <div class="section">
                        <div class="section-title">Guest Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Name</div>
                                <div class="info-value">${record.guest_name}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value">${record.email}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone</div>
                                <div class="info-value">${record.phone}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Guests</div>
                                <div class="info-value">${record.num_guests}</div>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Booking Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Type</div>
                                <div class="info-value">${record.booking_type}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">${record.booking_status.toUpperCase()}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Check-in</div>
                                <div class="info-value">${new Date(record.checkin_date).toLocaleDateString()}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Check-out</div>
                                <div class="info-value">${record.checkout_date ? new Date(record.checkout_date).toLocaleDateString() : 'N/A'}</div>
                            </div>
                        </div>
                        ${record.gcash_payment_intent_id ? `
                        <div style="margin-top: 10px;">
                            <div class="info-item">
                                <div class="info-label">GCash Payment Intent ID</div>
                                <div class="info-value">${record.gcash_payment_intent_id}</div>
                            </div>
                        </div>
                        ` : ''}
                        ${record.payment_reference ? `
                        <div style="margin-top: 10px;">
                            <div class="info-item">
                                <div class="info-label">Payment Reference</div>
                                <div class="info-value">${record.payment_reference}</div>
                            </div>
                        </div>
                        ` : ''}
                        <div style="margin-top: 10px;">
                            <div class="info-item">
                                <div class="info-label">Units</div>
                                <div class="info-value">${record.units}</div>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <div class="section-title">Payment Summary</div>
                        <div class="payment-box">
                            <div class="payment-row">
                                <span>Total Price:</span>
                                <span>₱${parseFloat(record.total_price).toFixed(2)}</span>
                            </div>
                            <div class="payment-row">
                                <span>Amount Paid:</span>
                                <span>₱${parseFloat(record.total_paid || 0).toFixed(2)}</span>
                            </div>
                            <div class="payment-row">
                                <span>Amount Refunded:</span>
                                <span>₱${parseFloat(record.total_refunded || 0).toFixed(2)}</span>
                            </div>
                            <div class="payment-row">
                                <span>Net Paid:</span>
                                <span>₱${parseFloat(record.net_paid || 0).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>

                    ${record.cancellation_reason ? `
                    <div class="section">
                        <div class="section-title">Cancellation Details</div>
                        <div class="info-item">
                            <div class="info-label">Reason</div>
                            <div class="info-value">${record.cancellation_reason}</div>
                        </div>
                    </div>
                    ` : ''}

                    <div class="footer">
                        <p><strong>Villa Elena Resort</strong></p>
                        <p>Thank you for choosing our resort!</p>
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        }

        // Filter and search listeners
        document.getElementById('statusFilter').addEventListener('change', function() {
            currentPage = 1;
            applyFilters();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                currentPage = 1;
                applyFilters();
            }, 500);
        });

        // Repaginate on resize
        window.addEventListener('resize', () => {
            if (totalRecords > 0) updatePagination();
        });

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== HISTORY PAGE LOADED ===');
            loadHistory();
        });
    </script>
</body>
</html>