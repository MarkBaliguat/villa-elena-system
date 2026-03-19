<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    {{-- jsPDF + AutoTable for PDF export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    {{-- SheetJS for Excel (.xlsx) export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Reservations - Villa Elena</title>
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

        #bookingsTable {
            width: 100%;
            table-layout: auto;
        }

        #bookingsTable th,
        #bookingsTable td {
            white-space: nowrap;
            transition: padding 0.3s ease, font-size 0.3s ease;
        }

        /* Compact when sidebar expanded */
        #mainContent.ml-64 #bookingsTable th,
        #mainContent.ml-64 #bookingsTable td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8125rem;
        }

        /* Spacious when sidebar collapsed */
        #mainContent.ml-24 #bookingsTable th,
        #mainContent.ml-24 #bookingsTable td {
            padding: 0.875rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Column sizing */
        #bookingsTable th:nth-child(1), #bookingsTable td:nth-child(1) { min-width: 160px; }
        #bookingsTable th:nth-child(2), #bookingsTable td:nth-child(2) { min-width: 200px; }
        #bookingsTable th:nth-child(3), #bookingsTable td:nth-child(3) { min-width: 160px; }
        #bookingsTable th:nth-child(4), #bookingsTable td:nth-child(4) { min-width: 140px; }
        #bookingsTable th:nth-child(5), #bookingsTable td:nth-child(5) { min-width: 100px; }
        #bookingsTable th:nth-child(6), #bookingsTable td:nth-child(6) { min-width: 130px; }

        /* Allow wrapping on booking details column */
        #bookingsTable td:nth-child(2) {
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
        .booking-card {
            display: none;
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #3b82f6;
        }

        .booking-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .booking-card .card-body {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .booking-card .card-item {
            display: flex;
            flex-direction: column;
        }

        .booking-card .card-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .booking-card .card-value {
            font-size: 0.875rem;
            color: #111827;
        }

        .booking-card .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e5e7eb;
        }

        .booking-card .card-actions button {
            flex: 1;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .booking-card .card-actions button:hover {
            opacity: 0.85;
        }

        .btn-card-edit    { background: #2563eb; color: white; }
        .btn-card-payment { background: #16a34a; color: white; }
        .btn-card-delete  { background: #dc2626; color: white; }

        .booking-card:hover {
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
            #bookingsTable {
                display: none;
            }

            .booking-card {
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

            .booking-card .card-body {
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

            .header-row .action-buttons {
                width: 100%;
            }

            .header-row .action-buttons button {
                width: 100%;
                justify-content: center;
            }
        }

        /* ===== EXTRA SMALL ===== */
        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }

            .booking-card {
                padding: 0.75rem;
            }

            .booking-card .card-actions {
                flex-direction: column;
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
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Reservations</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage all bookings and reservations</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs md:text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>

            {{-- Include Modals --}}
            @include('adminFolder.reservation.modals.add-booking-modal')
            @include('adminFolder.reservation.modals.edit-booking-modal')
            @include('adminFolder.reservation.modals.payment-refund-modal')

            {{-- Filters and Add Button --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

                {{-- Top Row: Title + Add Button --}}
                <div class="header-row">
                    <h2 class="text-xl font-semibold text-gray-800">Active Reservations</h2>
                    <div class="action-buttons">
                        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i>
                            Add Reservation
                        </button>
                    </div>
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
                        <select name="status" id="statusFilter" class="px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                        </select>
                        <button type="button" onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium">
                            Refresh
                        </button>
                        {{-- Export Buttons --}}
                        <div class="export-buttons">
                            {{-- FIXED: Changed from CSV to Excel using SheetJS --}}
                            <button type="button" onclick="exportToExcel()" class="export-btn bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                <i class="fas fa-file-excel"></i>
                                <span class="hidden sm:inline">Export Excel</span>
                                <span class="sm:hidden">Excel</span>
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
                    <table class="w-full" id="bookingsTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Guest Info</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Booking Details</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Payment</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Units</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Status</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                    <p>Loading bookings...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div id="bookingsCardsContainer">
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
            } else {
                mainContent.classList.remove('ml-24');
                mainContent.classList.add('ml-64');
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
        let totalBookings = 0;
        let allBookings = [];

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
            loadBookings('all', '', 1);
        }

        // Phone number validation
        function validatePhoneNumber(phone) {
            const cleaned = phone.replace(/\D/g, '');
            const isValid = /^09\d{9}$/.test(cleaned);
            return {
                isValid: isValid,
                formatted: isValid ? cleaned : phone,
                error: isValid ? null : 'Phone number must be exactly 11 digits and start with 09 (e.g., 09486036516)'
            };
        }

        function setupPhoneValidation(inputId) {
            const phoneInput = document.getElementById(inputId);
            if (!phoneInput) return;

            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                if (value.length > 0) value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                e.target.value = value;
                validatePhoneField(phoneInput);
            });

            phoneInput.addEventListener('blur', function() {
                validatePhoneField(phoneInput);
            });
        }

        function validatePhoneField(phoneInput) {
            const value = phoneInput.value.replace(/\D/g, '');
            const validation = validatePhoneNumber(value);

            const existingError = phoneInput.parentNode.querySelector('.phone-error');
            if (existingError) existingError.remove();

            phoneInput.classList.remove('border-red-500', 'border-green-500');

            if (value === '') return true;

            if (!validation.isValid) {
                phoneInput.classList.add('border-red-500');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'phone-error text-red-500 text-xs mt-1';
                errorDiv.textContent = validation.error;
                phoneInput.parentNode.appendChild(errorDiv);
                return false;
            } else {
                phoneInput.classList.add('border-green-500');
                return true;
            }
        }

        function validateFormPhoneNumbers() {
            const addPhoneInput = document.getElementById('phone');
            const editPhoneInput = document.getElementById('edit_phone');
            let isValid = true;

            if (addPhoneInput && addPhoneInput.value) {
                if (!validatePhoneField(addPhoneInput)) isValid = false;
            }
            if (editPhoneInput && editPhoneInput.value) {
                if (!validatePhoneField(editPhoneInput)) isValid = false;
            }
            return isValid;
        }

        // Load bookings
        function loadBookings(status = 'all', search = '', page = 1) {
            const tbody = document.getElementById('bookingsTableBody');
            const cardsContainer = document.getElementById('bookingsCardsContainer');

            const loadingRow = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading bookings...</p>
                    </td>
                </tr>`;

            tbody.innerHTML = loadingRow;
            cardsContainer.innerHTML = `<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-500"></i></div>`;

            let url = `/admin/bookings?`;
            if (status !== 'all') url += `status=${status}&`;
            if (search) url += `search=${search}&`;
            url += `page=${page}&per_page=${perPage}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allBookings = data.data;
                        totalBookings = data.total || data.data.length;
                        currentPage = page;

                        displayBookings(data.data);
                        displayBookingCards(data.data);
                        updatePagination(data.total || data.data.length, page);
                        updateShowingText(data.data.length, page, data.total || data.data.length);
                    } else {
                        const errorMsg = `<tr><td colspan="6" class="text-center py-8 text-red-500">Error loading bookings</td></tr>`;
                        tbody.innerHTML = errorMsg;
                        cardsContainer.innerHTML = `<div class="text-center py-8 text-red-500">Error loading bookings</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-red-500">Error loading bookings</td></tr>`;
                    cardsContainer.innerHTML = `<div class="text-center py-8 text-red-500">Error loading bookings</div>`;
                });
        }

        // Display bookings in table
        function displayBookings(bookings) {
            const tbody = document.getElementById('bookingsTableBody');

            if (bookings.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No bookings found</p>
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = bookings.map(booking => {
                const totalPrice        = parseFloat(booking.total_price)    || 0;
                const totalPaid         = parseFloat(booking.total_paid)     || 0;
                const totalRefunded     = parseFloat(booking.total_refunded) || 0;
                const netPaid           = totalPaid - totalRefunded;
                const calculatedBalance = Math.max(0, totalPrice - netPaid);

                return `
                <tr class="border-b border-gray-100 table-row-hover">
                    <td class="py-4 px-4">
                        <div>
                            <p class="font-medium text-gray-900">${booking.guest_name}</p>
                            <p class="text-sm text-gray-500">${booking.email}</p>
                            <p class="text-sm text-gray-500">${booking.phone}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div>
                            <p class="text-sm"><span class="font-medium">Type:</span> ${booking.booking_type}</p>
                            <p class="text-sm"><span class="font-medium">Check-in:</span> ${booking.checkin_date}</p>
                            <p class="text-sm"><span class="font-medium">Check-out:</span> ${booking.checkout_date || 'N/A'}</p>
                            <p class="text-sm"><span class="font-medium">Guests:</span> ${booking.num_guests}</p>
                            <p class="text-sm"><span class="font-medium">Price:</span> PHP ${totalPrice.toFixed(2)}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="space-y-1">
                            <p class="text-sm"><span class="font-medium">Total:</span> PHP ${totalPrice.toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Paid:</span> <span class="text-green-600 font-semibold">PHP ${totalPaid.toFixed(2)}</span></p>
                            <p class="text-sm"><span class="font-medium">Refunded:</span> <span class="text-orange-600">PHP ${totalRefunded.toFixed(2)}</span></p>
                            <p class="text-sm"><span class="font-medium">Balance:</span> <span class="${calculatedBalance === 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'}">PHP ${calculatedBalance.toFixed(2)}</span></p>
                            ${booking.payment_status ? `<p class="text-xs ${getPaymentStatusColor(booking.payment_status)}">${booking.payment_status.toUpperCase()}</p>` : ''}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm text-gray-700">${booking.units}</p>
                    </td>
                    <td class="py-4 px-4">
                        <span class="status-badge ${getStatusColor(booking.booking_status)}">
                            ${booking.booking_status.toUpperCase()}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex gap-2">
                            <button onclick="editBooking(${booking.bookingID})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openPaymentModal(${booking.bookingID})" class="text-green-600 hover:text-green-800" title="Payment Management">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            @if(auth()->user()->role === 'manager')
                                <button onclick="deleteBooking(${booking.bookingID})" class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                `;
            }).join('');
        }

        // Display bookings as cards (mobile)
        function displayBookingCards(bookings) {
            const container = document.getElementById('bookingsCardsContainer');

            if (bookings.length === 0) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = bookings.map(booking => {
                const totalPrice        = parseFloat(booking.total_price)    || 0;
                const totalPaid         = parseFloat(booking.total_paid)     || 0;
                const totalRefunded     = parseFloat(booking.total_refunded) || 0;
                const netPaid           = totalPaid - totalRefunded;
                const calculatedBalance = Math.max(0, totalPrice - netPaid);

                return `
                <div class="booking-card">
                    <div class="card-header">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">${booking.guest_name}</h3>
                            <p class="text-sm text-gray-500">${booking.email}</p>
                            <p class="text-sm text-gray-500">${booking.phone}</p>
                        </div>
                        <span class="status-badge ${getStatusColor(booking.booking_status)}">
                            ${booking.booking_status.toUpperCase()}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-item">
                            <span class="card-label">Booking Type</span>
                            <span class="card-value">${booking.booking_type}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Check-in</span>
                            <span class="card-value">${booking.checkin_date}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Check-out</span>
                            <span class="card-value">${booking.checkout_date || 'N/A'}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Guests</span>
                            <span class="card-value">${booking.num_guests}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Units</span>
                            <span class="card-value">${booking.units}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Total Price</span>
                            <span class="card-value font-semibold text-blue-600">PHP ${totalPrice.toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Amount Paid</span>
                            <span class="card-value text-green-600 font-semibold">PHP ${totalPaid.toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Refunded</span>
                            <span class="card-value text-orange-600">PHP ${totalRefunded.toFixed(2)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label">Balance</span>
                            <span class="card-value ${calculatedBalance === 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'}">PHP ${calculatedBalance.toFixed(2)}</span>
                        </div>
                        ${booking.payment_status ? `
                        <div class="card-item">
                            <span class="card-label">Payment Status</span>
                            <span class="card-value ${getPaymentStatusColor(booking.payment_status)} font-semibold">${booking.payment_status.toUpperCase()}</span>
                        </div>` : ''}
                    </div>
                    <div class="card-actions">
                        <button class="btn-card-edit" onclick="editBooking(${booking.bookingID})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-card-payment" onclick="openPaymentModal(${booking.bookingID})">
                            <i class="fas fa-credit-card"></i> Payment
                        </button>
                        <button class="btn-card-delete" onclick="deleteBooking(${booking.bookingID})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                `;
            }).join('');
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

            if (currentPage > 1) {
                html += `<button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage - 1})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-chevron-left"></i></button>`;
            }

            const maxVisible = window.innerWidth < 640 ? 3 : 5;
            let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let end = Math.min(totalPages, start + maxVisible - 1);
            if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);

            if (start > 1) {
                html += `<button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), 1)" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">1</button>`;
                if (start > 2) html += `<span class="px-3 py-2 text-sm text-gray-400">...</span>`;
            }

            for (let i = start; i <= end; i++) {
                if (i === currentPage) {
                    html += `<button class="px-3 py-2 text-sm border border-blue-500 bg-blue-500 text-white rounded-lg transition">${i}</button>`;
                } else {
                    html += `<button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${i})" 
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">${i}</button>`;
                }
            }

            if (end < totalPages) {
                if (end < totalPages - 1) html += `<span class="px-3 py-2 text-sm text-gray-400">...</span>`;
                html += `<button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${totalPages})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">${totalPages}</button>`;
            }

            if (currentPage < totalPages) {
                html += `<button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage + 1})" 
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
                case 'confirmed': return 'bg-green-100 text-green-800';
                case 'pending':   return 'bg-yellow-100 text-yellow-800';
                default:          return 'bg-gray-100 text-gray-800';
            }
        }

        function getPaymentStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'completed': return 'text-green-600';
                case 'pending':   return 'text-yellow-600';
                case 'failed':    return 'text-red-600';
                case 'refunded':  return 'text-blue-600';
                default:          return 'text-gray-600';
            }
        }

        // ============================================
        // DELETE BOOKING WITH SWEETALERT2
        // ============================================
        async function deleteBooking(bookingId) {
            const confirmResult = await Swal.fire({
                icon: 'warning',
                title: 'Delete Booking?',
                text: 'Are you sure you want to delete this booking? This action cannot be undone!',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            });

            if (!confirmResult.isConfirmed) return;

            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the booking',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch(`/admin/bookings/${bookingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Booking has been deleted successfully.',
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to delete booking',
                        confirmButtonColor: '#dc2626'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while deleting the booking',
                    confirmButtonColor: '#dc2626'
                });
            }
        }

        // ============================================
        // EXPORT TO EXCEL (.xlsx) — FIXED
        // Uses SheetJS; no ₱ symbol encoding issues.
        // Currency values stored as proper numbers.
        // ============================================
        function exportToExcel() {
            if (allBookings.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Data', text: 'No data available to export', confirmButtonColor: '#3b82f6' });
                return;
            }

            const rows = allBookings.map(b => {
                const totalPrice    = parseFloat(b.total_price)    || 0;
                const totalPaid     = parseFloat(b.total_paid)     || 0;
                const totalRefunded = parseFloat(b.total_refunded) || 0;
                const balance       = Math.max(0, totalPrice - (totalPaid - totalRefunded));

                return {
                    'Guest Name':            b.guest_name,
                    'Email':                 b.email,
                    'Phone':                 b.phone,
                    'Booking Type':          b.booking_type,
                    'Check-in':              b.checkin_date,
                    'Check-out':             b.checkout_date || 'N/A',
                    'No. of Guests':         b.num_guests,
                    'Units':                 b.units,
                    'Total Price (PHP)':     totalPrice,
                    'Amount Paid (PHP)':     totalPaid,
                    'Amount Refunded (PHP)': totalRefunded,
                    'Balance (PHP)':         balance,
                    'Booking Status':        b.booking_status,
                    'Payment Status':        b.payment_status || 'No Payment',
                    'Special Requirements':  b.special_requirements || 'N/A'
                };
            });

            const ws = XLSX.utils.json_to_sheet(rows);

            // Style header row (blue bg, white bold text)
            const range = XLSX.utils.decode_range(ws['!ref']);
            for (let C = range.s.c; C <= range.e.c; C++) {
                const cellRef = XLSX.utils.encode_cell({ r: 0, c: C });
                if (!ws[cellRef]) continue;
                ws[cellRef].s = {
                    font:      { bold: true, color: { rgb: 'FFFFFF' } },
                    fill:      { patternType: 'solid', fgColor: { rgb: '3B82F6' } },
                    alignment: { horizontal: 'center', wrapText: true }
                };
            }

            // Format currency columns as numbers
            const phpCols = [8, 9, 10, 11]; // Total, Paid, Refunded, Balance
            for (let R = 1; R <= range.e.r; R++) {
                phpCols.forEach(C => {
                    const cellRef = XLSX.utils.encode_cell({ r: R, c: C });
                    if (ws[cellRef]) {
                        ws[cellRef].t = 'n';
                        ws[cellRef].z = '#,##0.00';
                    }
                });
            }

            // Auto column widths
            const colWidths = Object.keys(rows[0]).map(key => ({
                wch: Math.max(key.length, ...rows.map(r => String(r[key] ?? '').length)) + 3
            }));
            ws['!cols'] = colWidths;

            // Freeze header row
            ws['!freeze'] = { xSplit: 0, ySplit: 1 };

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Reservations');

            XLSX.writeFile(wb, `active_reservations_${new Date().toISOString().split('T')[0]}.xlsx`, { cellStyles: true });
        }

        // ============================================
        // PRINT TABLE — FIXED
        // Uses "PHP" prefix; renders cleanly in all browsers.
        // ============================================
        function printTable() {
            if (allBookings.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Data', text: 'No data available to print', confirmButtonColor: '#3b82f6' });
                return;
            }

            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html><html><head>
                    <title>Active Reservations Report - Villa Elena</title>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2d3748; text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                        th { background-color: #3b82f6; color: white; font-weight: bold; }
                        tr:nth-child(even) { background-color: #f9fafb; }
                        .status-confirmed { background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 999px; }
                        .status-pending   { background-color: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 999px; }
                        .print-date { text-align: right; margin-bottom: 20px; color: #6b7280; font-size: 11px; }
                        .summary { margin-top: 20px; text-align: right; color: #374151; font-size: 12px; }
                    </style>
                </head><body>
                    <h1>Active Reservations Report - Villa Elena</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleString()}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Guests</th>
                                <th>Units</th>
                                <th>Total (PHP)</th>
                                <th>Paid (PHP)</th>
                                <th>Refunded (PHP)</th>
                                <th>Balance (PHP)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${allBookings.map(b => {
                                const totalPrice    = parseFloat(b.total_price)    || 0;
                                const totalPaid     = parseFloat(b.total_paid)     || 0;
                                const totalRefunded = parseFloat(b.total_refunded) || 0;
                                const calcBalance   = Math.max(0, totalPrice - (totalPaid - totalRefunded));
                                return `<tr>
                                    <td>${b.guest_name}</td>
                                    <td>${b.email}</td>
                                    <td>${b.phone}</td>
                                    <td>${b.booking_type}</td>
                                    <td>${b.checkin_date}</td>
                                    <td>${b.checkout_date || 'N/A'}</td>
                                    <td>${b.num_guests}</td>
                                    <td>${b.units}</td>
                                    <td>${totalPrice.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                                    <td>${totalPaid.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                                    <td>${totalRefunded.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                                    <td>${calcBalance.toLocaleString('en-PH', {minimumFractionDigits:2})}</td>
                                    <td><span class="status-${b.booking_status.toLowerCase()}">${b.booking_status.toUpperCase()}</span></td>
                                </tr>`;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="summary">Total Records: <strong>${allBookings.length}</strong></div>
                </body></html>`;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        }

        // ============================================
        // EXPORT TO PDF — FIXED
        // Removed ₱ symbol; uses "PHP" in headers.
        // Landscape orientation for more columns.
        // ============================================
        function exportToPDF() {
            if (allBookings.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Data', text: 'No data available to export', confirmButtonColor: '#3b82f6' });
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

            // Title
            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Active Reservations Report - Villa Elena', 14, 15);

            // Generated date
            doc.setFontSize(9);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString('en-PH')}`, 14, 22);

            // Headers — use "(PHP)" not "₱" to avoid encoding issues
            const headers = [
                'Guest Name',
                'Email',
                'Phone',
                'Type',
                'Check-in',
                'Check-out',
                'Guests',
                'Units',
                'Total\n(PHP)',
                'Paid\n(PHP)',
                'Refunded\n(PHP)',
                'Balance\n(PHP)',
                'Status'
            ];

            const rows = allBookings.map(b => {
                const totalPrice    = parseFloat(b.total_price)    || 0;
                const totalPaid     = parseFloat(b.total_paid)     || 0;
                const totalRefunded = parseFloat(b.total_refunded) || 0;
                const calcBalance   = Math.max(0, totalPrice - (totalPaid - totalRefunded));

                return [
                    b.guest_name,
                    b.email,
                    b.phone,
                    b.booking_type,
                    b.checkin_date,
                    b.checkout_date || 'N/A',
                    String(b.num_guests),
                    b.units,
                    totalPrice.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    totalPaid.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    totalRefunded.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    calcBalance.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    b.booking_status.toUpperCase()
                ];
            });

            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 28,
                styles: {
                    fontSize: 7,
                    cellPadding: 2,
                    font: 'helvetica',
                    overflow: 'linebreak',
                    valign: 'middle'
                },
                headStyles: {
                    fillColor: [59, 130, 246],
                    textColor: 255,
                    fontStyle: 'bold',
                    halign: 'center',
                    valign: 'middle',
                    fontSize: 7
                },
                alternateRowStyles: {
                    fillColor: [249, 250, 251]
                },
                didParseCell: function(data) {
                    // Color-code status column
                    if (data.section === 'body' && data.column.index === 12) {
                        const val = (data.cell.raw || '').toLowerCase();
                        if (val === 'confirmed') {
                            data.cell.styles.textColor = [5, 150, 105];
                            data.cell.styles.fontStyle = 'bold';
                        } else if (val === 'pending') {
                            data.cell.styles.textColor = [180, 130, 0];
                            data.cell.styles.fontStyle = 'bold';
                        }
                    }
                    // Right-align currency columns
                    if (data.section === 'body' && [8, 9, 10, 11].includes(data.column.index)) {
                        data.cell.styles.halign = 'right';
                    }
                },
                columnStyles: {
                    0:  { cellWidth: 28 },  // Guest Name
                    1:  { cellWidth: 38 },  // Email
                    2:  { cellWidth: 24 },  // Phone
                    3:  { cellWidth: 22 },  // Type
                    4:  { cellWidth: 20 },  // Check-in
                    5:  { cellWidth: 20 },  // Check-out
                    6:  { cellWidth: 12 },  // Guests
                    7:  { cellWidth: 18 },  // Units
                    8:  { cellWidth: 20 },  // Total
                    9:  { cellWidth: 20 },  // Paid
                    10: { cellWidth: 20 },  // Refunded
                    11: { cellWidth: 20 },  // Balance
                    12: { cellWidth: 20 }   // Status
                },
                margin: { top: 28, left: 8, right: 8 },
                tableWidth: 'auto'
            });

            // Footer with page count
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(150, 150, 150);
                doc.text(
                    `Page ${i} of ${pageCount}  |  Villa Elena Reservations`,
                    doc.internal.pageSize.getWidth() / 2,
                    doc.internal.pageSize.getHeight() - 6,
                    { align: 'center' }
                );
            }

            doc.save(`active_reservations_${new Date().toISOString().split('T')[0]}.pdf`);
        }

        // Filter and search listeners
        document.getElementById('statusFilter').addEventListener('change', function() {
            loadBookings(this.value, document.getElementById('searchInput').value, 1);
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                loadBookings(getCurrentStatus(), this.value, 1);
            }, 500);
        });

        // Repaginate on resize
        window.addEventListener('resize', () => {
            if (totalBookings > 0) updatePagination(totalBookings, currentPage);
        });

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== RESERVATIONS PAGE LOADED ===');
            loadBookings('all', '', 1);
        });
    </script>
</body>
</html>