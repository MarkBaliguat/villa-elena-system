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
    <title>Special Events - Villa Elena</title>
    <style>
        /* Custom styles for printing */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background-color: white !important;
                color: black !important;
                font-size: 12pt;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
            }
            
            th, td {
                border: 1px solid #000;
                padding: 8px;
            }
            
            th {
                background-color: #f0f0f0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex">
        {{-- Sidebar --}}
        @include('adminFolder.partials.sidebar')
        
        {{-- Main Content --}}
        <div class="ml-64 flex-1 p-8">
            {{-- Header Section --}}
            <div class="mb-6">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Events Reservations</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage all Special Events</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>

            {{-- Include Modals --}}
            @include('adminFolder.special-events.modals.add-event-modal')
            @include('adminFolder.special-events.modals.edit-event-modal')
            @include('adminFolder.special-events.modals.payment-refund-modal')

            {{-- Filters and Add Button --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Active Event Reservations</h2>
                    <div class="flex gap-3">
                        <button onclick="openModal()" class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i>
                            Add Special Event
                        </button>
                    </div>
                </div>

                {{-- Search and Filter --}}
                <form method="GET" action="" id="searchForm">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex gap-4">
                            <div class="relative">
                                <i class="fas fa-search search-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text" 
                                    name="search"
                                    id="searchInput"
                                    placeholder="Search by guest name, email, phone, or event name" 
                                    class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                                >
                            </div>
                            <select id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                            <button type="button" onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium no-print">
                                Clear
                            </button>
                            {{-- Export Buttons --}}
                            <div class="flex gap-2 no-print">
                                <button type="button" onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                    <i class="fas fa-file-csv"></i>
                                    Export CSV
                                </button>
                                <button type="button" onclick="printTable()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                    <i class="fas fa-print"></i>
                                    Print
                                </button>
                                <button type="button" onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center gap-2 transition text-sm">
                                    <i class="fas fa-file-pdf"></i>
                                    Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Reservations Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full" id="bookingsTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Guest Info</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Event Details</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Payment</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Venue</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Status</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                    <p>Loading Special Events bookings...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                    <div class="text-sm text-gray-600">
                        Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> results
                    </div>
                    <div class="flex gap-1" id="pagination">
                        <!-- Pagination buttons will be generated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
  <script>
        // Setup CSRF token for all AJAX requests
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

        // Clear filters function
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            loadBookings('all', '', 1);
        }

        // Phone number validation function
        function validatePhoneNumber(phone) {
            const cleaned = phone.replace(/\D/g, '');
            const isValid = /^09\d{9}$/.test(cleaned);
            
            return {
                isValid: isValid,
                formatted: isValid ? cleaned : phone,
                error: isValid ? null : 'Phone number must be exactly 11 digits and start with 09 (e.g., 09486036516)'
            };
        }

        // Phone number input handler
        function setupPhoneValidation(inputId) {
            const phoneInput = document.getElementById(inputId);
            
            if (!phoneInput) return;
            
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                
                if (value.length > 0) {
                    value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                }
                
                e.target.value = value;
                validatePhoneField(phoneInput);
            });
            
            phoneInput.addEventListener('blur', function() {
                validatePhoneField(phoneInput);
            });
        }

        // Validate phone field and show error
        function validatePhoneField(phoneInput) {
            const value = phoneInput.value.replace(/\D/g, '');
            const validation = validatePhoneNumber(value);
            
            const existingError = phoneInput.parentNode.querySelector('.phone-error');
            if (existingError) {
                existingError.remove();
            }
            
            phoneInput.classList.remove('border-red-500', 'border-green-500');
            
            if (value === '') {
                return true;
            }
            
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

        // Validate phone number before form submission
        function validateFormPhoneNumbers() {
            const addPhoneInput = document.getElementById('phone');
            const editPhoneInput = document.getElementById('edit_phone');
            
            let isValid = true;
            
            if (addPhoneInput && addPhoneInput.value) {
                if (!validatePhoneField(addPhoneInput)) {
                    isValid = false;
                }
            }
            
            if (editPhoneInput && editPhoneInput.value) {
                if (!validatePhoneField(editPhoneInput)) {
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Load all special events bookings with pagination
        function loadBookings(status = 'all', search = '', page = 1) {
            const tbody = document.getElementById('bookingsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading special events...</p>
                    </td>
                </tr>
            `;

            let url = `/admin/special-events?`;
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
                        updatePagination(data.total || data.data.length, page);
                        updateShowingText(data.data.length, page, data.total || data.data.length);
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-8 text-red-500">
                                    Error loading special events: ${data.message || 'Unknown error'}
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-8 text-red-500">
                                Error loading special events: ${error.message}
                            </td>
                        </tr>
                    `;
                });
        }

        // Display special events in table
        function displayBookings(bookings) {
            const tbody = document.getElementById('bookingsTableBody');
            
            if (bookings.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No special events found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = bookings.map(booking => {
                // ✅ STANDARDIZE DATE FORMAT - Ensure date is in YYYY-MM-DD format
                const eventDate = booking.checkin_date ? booking.checkin_date : 'N/A';
                
                return `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-4 px-4">
                        <div>
                            <p class="font-medium text-gray-900">${booking.guest_name}</p>
                            <p class="text-sm text-gray-500">${booking.email}</p>
                            <p class="text-sm text-gray-500">${booking.phone}</p>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div>
                            <p class="font-medium text-violet-700">${booking.event_name || 'Special Event'}</p>
                            <p class="text-sm"><span class="font-medium">Event Date:</span> ${eventDate}</p>
                            <p class="text-sm"><span class="font-medium">Time:</span> ${booking.event_start_time || '08:00'} - ${booking.event_end_time || '17:00'}</p>
                            <p class="text-sm"><span class="font-medium">Guests:</span> ${booking.num_guests}</p>
                            <p class="text-sm"><span class="font-medium">Price:</span> ₱${parseFloat(booking.total_price).toFixed(2)}</p>
                            ${booking.special_requirements ? `<p class="text-sm mt-1"><span class="font-medium">Notes:</span> ${booking.special_requirements}</p>` : ''}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="space-y-1">
                            <p class="text-sm"><span class="font-medium">Total:</span> ₱${parseFloat(booking.total_price).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Paid:</span> ₱${parseFloat(booking.total_paid || 0).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Refunded:</span> ₱${parseFloat(booking.total_refunded || 0).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Net Paid:</span> ₱${parseFloat(booking.net_paid || 0).toFixed(2)}</p>
                            <p class="text-sm"><span class="font-medium">Balance:</span> ₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}</p>
                            ${booking.payment_status ? `<p class="text-xs ${getPaymentStatusColor(booking.payment_status)}">${booking.payment_status.toUpperCase()}</p>` : ''}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm text-gray-700">${booking.units}</p>
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(booking.booking_status)}">
                            ${booking.booking_status.toUpperCase()}
                        </span>
                    </td>
                    <td class="py-4 px-4 no-print">
                        <div class="flex gap-2">
                            <button onclick="editEvent(${booking.bookingID})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openSpecialEventPaymentModal(${booking.bookingID})" class="text-green-600 hover:text-green-800" title="Payment Management">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            <button onclick="deleteEvent(${booking.bookingID})" class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                `;
            }).join('');
        }

        // Update pagination controls
        function updatePagination(total, currentPage) {
            const totalPages = Math.ceil(total / perPage);
            const pagination = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            if (currentPage > 1) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage - 1})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition no-print">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                `;
            }

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (startPage > 1) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), 1)" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition no-print">
                        1
                    </button>
                `;
                if (startPage > 2) {
                    paginationHTML += `
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    `;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    paginationHTML += `
                        <button class="px-3 py-2 text-sm border border-violet-500 bg-violet-500 text-white rounded-lg transition no-print">
                            ${i}
                        </button>
                    `;
                } else {
                    paginationHTML += `
                        <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${i})" 
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition no-print">
                            ${i}
                        </button>
                    `;
                }
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHTML += `
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    `;
                }
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${totalPages})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition no-print">
                        ${totalPages}
                    </button>
                `;
            }

            if (currentPage < totalPages) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage + 1})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition no-print">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                `;
            }

            pagination.innerHTML = paginationHTML;
        }

        // Update showing text
        function updateShowingText(showing, currentPage, total) {
            const from = ((currentPage - 1) * perPage) + 1;
            const to = Math.min(from + showing - 1, total);
            
            document.getElementById('showingFrom').textContent = from;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('totalRecords').textContent = total;
        }

        // Get current filter values
        function getCurrentStatus() {
            return document.getElementById('statusFilter').value;
        }

        function getCurrentSearch() {
            return document.getElementById('searchInput').value;
        }

        // Get status color
        function getStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'confirmed':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                case 'completed':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // Get payment status color
        function getPaymentStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'completed':
                    return 'text-green-600';
                case 'pending':
                    return 'text-yellow-600';
                case 'failed':
                    return 'text-red-600';
                case 'refunded':
                    return 'text-blue-600';
                default:
                    return 'text-gray-600';
            }
        }

        // Delete special event
        function deleteEvent(bookingId) {
            if (!confirm('Are you sure you want to delete this special event?')) return;

            fetch(`/admin/special-events/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Special event deleted successfully!');
                    loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete special event'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting special event');
            });
        }

        // Export to CSV
        function exportToCSV() {
            const bookings = allBookings;
            if (bookings.length === 0) {
                alert('No data to export');
                return;
            }

            const headers = ['Guest Name', 'Email', 'Phone', 'Event Name', 'Event Date', 'Start Time', 'End Time', 'Guests', 'Venue', 'Total Price', 'Amount Paid', 'Amount Refunded', 'Net Paid', 'Remaining Balance', 'Status', 'Payment Status', 'Special Requirements'];
            const csvData = bookings.map(booking => {
                // ✅ STANDARDIZE DATE FORMAT IN CSV
                const eventDate = booking.checkin_date ? booking.checkin_date : 'N/A';
                
                return [
                    `"${booking.guest_name}"`,
                    `"${booking.email}"`,
                    `"${booking.phone}"`,
                    `"${booking.event_name || 'Special Event'}"`,
                    `"${eventDate}"`,
                    `"${booking.event_start_time || '08:00'}"`,
                    `"${booking.event_end_time || '17:00'}"`,
                    `"${booking.num_guests}"`,
                    `"${booking.units}"`,
                    `"₱${parseFloat(booking.total_price).toFixed(2)}"`,
                    `"₱${parseFloat(booking.total_paid || 0).toFixed(2)}"`,
                    `"₱${parseFloat(booking.total_refunded || 0).toFixed(2)}"`,
                    `"₱${parseFloat(booking.net_paid || 0).toFixed(2)}"`,
                    `"₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}"`,
                    `"${booking.booking_status}"`,
                    `"${booking.payment_status || 'No Payment'}"`,
                    `"${booking.special_requirements || 'N/A'}"`
                ];
            });

            const csvContent = [
                headers.join(','),
                ...csvData.map(row => row.join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `special_events_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print table
        function printTable() {
            const bookings = allBookings;
            
            if (bookings.length === 0) {
                alert('No data to print');
                return;
            }

            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Special Events Report - Villa Elena</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #7c3aed; text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        .status-confirmed { background-color: #d1fae5; color: #065f46; }
                        .status-pending { background-color: #fef3c7; color: #92400e; }
                        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
                        .status-completed { background-color: #dbeafe; color: #1e40af; }
                        .print-date { text-align: right; margin-bottom: 20px; color: #6b7280; }
                        .event-name { color: #7c3aed; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <h1>Special Events Report - Villa Elena</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleString()}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Time</th>
                                <th>Guests</th>
                                <th>Venue</th>
                                <th>Total Price</th>
                                <th>Amount Paid</th>
                                <th>Amount Refunded</th>
                                <th>Net Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bookings.map(booking => {
                                // ✅ STANDARDIZE DATE FORMAT IN PRINT
                                const eventDate = booking.checkin_date ? booking.checkin_date : 'N/A';
                                
                                return `
                                <tr>
                                    <td>${booking.guest_name}</td>
                                    <td>${booking.email}</td>
                                    <td>${booking.phone}</td>
                                    <td class="event-name">${booking.event_name || 'Special Event'}</td>
                                    <td>${eventDate}</td>
                                    <td>${booking.event_start_time || '08:00'} - ${booking.event_end_time || '17:00'}</td>
                                    <td>${booking.num_guests}</td>
                                    <td>${booking.units}</td>
                                    <td>₱${parseFloat(booking.total_price).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.total_paid || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.total_refunded || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.net_paid || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}</td>
                                    <td><span class="status-${booking.booking_status}">${booking.booking_status.toUpperCase()}</span></td>
                                </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div style="margin-top: 20px; text-align: center; color: #6b7280;">
                        Total Records: ${bookings.length}
                    </div>
                </body>
                </html>
            `;

            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load then print
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const bookings = allBookings;
            
            if (bookings.length === 0) {
                alert('No data to export');
                return;
            }

            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Special Events Report - Villa Elena', 14, 15);
            
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);

            const tableData = bookings.map(booking => {
                // ✅ STANDARDIZE DATE FORMAT IN PDF
                const eventDate = booking.checkin_date ? booking.checkin_date : 'N/A';
                
                return [
                    booking.guest_name,
                    booking.email,
                    booking.phone,
                    booking.event_name || 'Special Event',
                    eventDate,
                    `${booking.event_start_time || '08:00'} - ${booking.event_end_time || '17:00'}`,
                    booking.num_guests,
                    booking.units,
                    `₱${parseFloat(booking.total_price).toFixed(2)}`,
                    `₱${parseFloat(booking.total_paid || 0).toFixed(2)}`,
                    `₱${parseFloat(booking.total_refunded || 0).toFixed(2)}`,
                    `₱${parseFloat(booking.net_paid || 0).toFixed(2)}`,
                    `₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}`,
                    booking.booking_status.toUpperCase()
                ];
            });

            const headers = [
                'Guest Name',
                'Email', 
                'Phone',
                'Event Name',
                'Event Date',
                'Time',
                'Guests',
                'Venue',
                'Total',
                'Paid',
                'Refunded',
                'Net Paid',
                'Balance',
                'Status'
            ];

            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: 30,
                styles: { fontSize: 8, cellPadding: 3 },
                headStyles: { fillColor: [124, 58, 237] },
                alternateRowStyles: { fillColor: [249, 250, 251] },
                margin: { top: 30 }
            });

            doc.save(`special_events_${new Date().toISOString().split('T')[0]}.pdf`);
        }

        // Filter and search
        document.getElementById('statusFilter').addEventListener('change', function() {
            const search = document.getElementById('searchInput').value;
            loadBookings(this.value, search, 1);
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            const status = document.getElementById('statusFilter').value;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                loadBookings(status, this.value, 1);
            }, 500);
        });

        // Load bookings on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== SPECIAL EVENTS PAGE LOADED ===');
            loadBookings('all', '', 1);
        });

        // ============================================
        // MODAL FUNCTIONS
        // ============================================

        // Add Modal functions
        function openModal() {
            document.getElementById('addBookingModal').classList.remove('hidden');
            document.getElementById('addBookingModal').classList.add('flex');
            
            // Setup phone validation
            setupPhoneValidation('phone');
            
            // Add event listener for outside click
            setTimeout(() => {
                document.addEventListener('click', handleOutsideClick);
            }, 100);
        }

        function closeModal() {
            document.getElementById('addBookingModal').classList.add('hidden');
            document.getElementById('addBookingModal').classList.remove('flex');
            
            // Clear phone validation styling
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.classList.remove('border-red-500', 'border-green-500');
                const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
                if (errorDiv) errorDiv.remove();
            }
            
            // Remove event listener
            document.removeEventListener('click', handleOutsideClick);
        }

        // Handle outside click for add modal
        function handleOutsideClick(event) {
            const modal = document.getElementById('addBookingModal');
            const modalContent = modal.querySelector('.bg-white');
            if (!modalContent.contains(event.target)) closeModal();
        }

        // Edit Modal functions
        function openEditModal() {
            document.getElementById('editBookingModal').classList.remove('hidden');
            document.getElementById('editBookingModal').classList.add('flex');
            
            // Setup phone validation for edit form
            setupPhoneValidation('edit_phone');
            
            // Add event listener for outside click
            setTimeout(() => {
                document.addEventListener('click', handleEditOutsideClick);
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editBookingModal').classList.add('hidden');
            document.getElementById('editBookingModal').classList.remove('flex');
            
            // Clear phone validation styling
            const phoneInput = document.getElementById('edit_phone');
            if (phoneInput) {
                phoneInput.classList.remove('border-red-500', 'border-green-500');
                const errorDiv = phoneInput.parentNode.querySelector('.phone-error');
                if (errorDiv) errorDiv.remove();
            }
            
            // Remove event listener
            document.removeEventListener('click', handleEditOutsideClick);
        }

        // Handle outside click for edit modal
        function handleEditOutsideClick(event) {
            const modal = document.getElementById('editBookingModal');
            const modalContent = modal.querySelector('.bg-white');
            if (!modalContent.contains(event.target)) closeEditModal();
        }

        // SPECIAL FUNCTION FOR PAYMENT MODAL - ITO ANG BAGONG FUNCTION
        function openSpecialEventPaymentModal(bookingId) {
            console.log('Opening payment modal for special event:', bookingId);
            
            // Check if the payment modal exists
            const paymentModal = document.getElementById('paymentModal');
            if (!paymentModal) {
                console.error('Payment modal not found in DOM');
                alert('Payment modal is not available. Please check if the payment modal is properly included.');
                return;
            }
            
            // Show the modal
            paymentModal.classList.remove('hidden');
            paymentModal.classList.add('flex');
            
            // Set the booking ID
            document.getElementById('payment_booking_id').value = bookingId;
            
            // Reset forms first
            document.getElementById('paymentForm').reset();
            document.getElementById('refundForm').reset();
            
            // Load booking details for payment
            loadSpecialEventForPayment(bookingId);
            
            // Add event listener for outside click
            setTimeout(() => {
                document.addEventListener('click', handlePaymentOutsideClick);
            }, 100);
        }

        function closePaymentModal() {
            const paymentModal = document.getElementById('paymentModal');
            if (paymentModal) {
                paymentModal.classList.add('hidden');
                paymentModal.classList.remove('flex');
                document.getElementById('paymentForm').reset();
                document.getElementById('refundForm').reset();
                
                // Remove event listener
                document.removeEventListener('click', handlePaymentOutsideClick);
            }
        }

        // Handle outside click for payment modal
        function handlePaymentOutsideClick(event) {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                const modalContent = modal.querySelector('.bg-white');
                if (!modalContent.contains(event.target)) closePaymentModal();
            }
        }

        // Format date for HTML date input (YYYY-MM-DD)
        function formatDateForInput(dateString) {
            if (!dateString) return '';
            
            try {
                // If date is already in YYYY-MM-DD format
                if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    return dateString;
                }
                
                // Try to parse the date
                const date = new Date(dateString);
                
                if (isNaN(date.getTime())) {
                    console.error('Invalid date:', dateString);
                    return '';
                }
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                
                return `${year}-${month}-${day}`;
            } catch (error) {
                console.error('Error formatting date:', error);
                return '';
            }
        }

        // Format time for HTML time input (HH:MM)
        function formatTimeForInput(timeString) {
            if (!timeString) return '';
            
            // If time is already in HH:MM format
            if (timeString.match(/^\d{2}:\d{2}$/)) {
                return timeString;
            }
            
            // If time is in HH:MM:SS format
            if (timeString.match(/^\d{2}:\d{2}:\d{2}$/)) {
                return timeString.substring(0, 5);
            }
            
            // If time includes AM/PM
            if (timeString.toLowerCase().includes('am') || timeString.toLowerCase().includes('pm')) {
                const timeParts = timeString.match(/(\d{1,2}):(\d{2})/);
                if (timeParts) {
                    return `${timeParts[1].padStart(2, '0')}:${timeParts[2]}`;
                }
            }
            
            return timeString;
        }



        // Edit special event function
        function editEvent(bookingId) {
            console.log('Editing special event ID:', bookingId);
            
            fetch(`/admin/special-events/${bookingId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Edit special event response:', data);
                    
                    if (data.success) {
                        const booking = data.data;
                        
                        // Set form values
                        document.getElementById('edit_booking_id').value = booking.bookingID;
                        document.getElementById('edit_guest_name').value = booking.guest_name;
                        document.getElementById('edit_email').value = booking.email;
                        document.getElementById('edit_phone').value = booking.phone;
                        document.getElementById('edit_event_name').value = booking.event_name || '';
                        document.getElementById('edit_booking_status').value = booking.booking_status;
                        document.getElementById('edit_num_guests').value = booking.num_guests;
                        document.getElementById('edit_total_price').value = parseFloat(booking.total_price).toFixed(2);
                        document.getElementById('edit_special_requirements').value = booking.special_requirements || '';
                        
                        // Format date properly for date input
                        const eventDate = formatDateForInput(booking.checkin_date);
                        document.getElementById('edit_checkin_date').value = eventDate;
                        
                        // Format times properly for time inputs
                        const startTime = formatTimeForInput(booking.event_start_time);
                        const endTime = formatTimeForInput(booking.event_end_time);
                        document.getElementById('edit_event_start_time').value = startTime;
                        document.getElementById('edit_event_end_time').value = endTime;
                        
                        console.log('Formatted event details:', {
                            date: eventDate,
                            startTime: startTime,
                            endTime: endTime
                        });
                        
                        openEditModal();
                    } else {
                        alert('Error loading special event: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading special event:', error);
                    alert('Error loading special event details');
                });
        }
    </script>