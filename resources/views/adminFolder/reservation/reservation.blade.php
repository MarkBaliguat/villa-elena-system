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
    <title>Reservations - Villa Elena</title>
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
                        <h1 class="text-3xl font-bold text-gray-800">Reservations</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage all bookings and reservations</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>

            {{-- Include Modals --}}
            @include('adminFolder.reservation.modals.add-booking-modal')
            @include('adminFolder.reservation.modals.edit-booking-modal')
            @include('adminFolder.reservation.modals.payment-refund-modal')

            {{-- Filters and Add Button --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Active Reservations</h2>
                    <div class="flex gap-3">
                        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i>
                            Add Booking
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
                                    placeholder="Search by guest name, email, or phone" 
                                    class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                                >
                            </div>
                            <select id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                            </select>
                            <button type="button" onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium">
                                Clear
                            </button>
                            {{-- Export Buttons --}}
                            <div class="flex gap-2">
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

    <script>
        // Setup CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Pagination variables
        let currentPage = 1;
        const perPage = 2;
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
            // Remove any non-digit characters
            const cleaned = phone.replace(/\D/g, '');
            
            // Check if it's exactly 11 digits and starts with 09
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
            
            // Format phone number as user types
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                // Limit to 11 digits
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                
                // Format with spaces for readability (optional)
                if (value.length > 0) {
                    value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
                }
                
                e.target.value = value;
                
                // Real-time validation
                validatePhoneField(phoneInput);
            });
            
            // Validate on blur
            phoneInput.addEventListener('blur', function() {
                validatePhoneField(phoneInput);
            });
        }

        // Validate phone field and show error
        function validatePhoneField(phoneInput) {
            const value = phoneInput.value.replace(/\D/g, '');
            const validation = validatePhoneNumber(value);
            
            // Remove existing error message
            const existingError = phoneInput.parentNode.querySelector('.phone-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Remove error styling
            phoneInput.classList.remove('border-red-500', 'border-green-500');
            
            if (value === '') {
                return true; // Empty is okay for optional fields
            }
            
            if (!validation.isValid) {
                phoneInput.classList.add('border-red-500');
                
                // Add error message
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

        // Load all bookings with pagination
        function loadBookings(status = 'all', search = '', page = 1) {
            const tbody = document.getElementById('bookingsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading bookings...</p>
                    </td>
                </tr>
            `;

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
                        updatePagination(data.total || data.data.length, page);
                        updateShowingText(data.data.length, page, data.total || data.data.length);
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-8 text-red-500">
                                    Error loading bookings
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
                                Error loading bookings
                            </td>
                        </tr>
                    `;
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
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = bookings.map(booking => `
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
                            <p class="text-sm"><span class="font-medium">Type:</span> ${booking.booking_type}</p>
                            <p class="text-sm"><span class="font-medium">Check-in:</span> ${booking.checkin_date}</p>
                            <p class="text-sm"><span class="font-medium">Check-out:</span> ${booking.checkout_date || 'N/A'}</p>
                            <p class="text-sm"><span class="font-medium">Guests:</span> ${booking.num_guests}</p>
                            <p class="text-sm"><span class="font-medium">Price:</span> ₱${parseFloat(booking.total_price).toFixed(2)}</p>
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
                    <td class="py-4 px-4">
                        <div class="flex gap-2">
                            <button onclick="editBooking(${booking.bookingID})" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openPaymentModal(${booking.bookingID})" class="text-green-600 hover:text-green-800" title="Payment Management">
                                <i class="fas fa-credit-card"></i>
                            </button>
                            <button onclick="deleteBooking(${booking.bookingID})" class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Update pagination controls - UPDATED DESIGN (same as units)
        function updatePagination(total, currentPage) {
            const totalPages = Math.ceil(total / perPage);
            const pagination = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            if (currentPage > 1) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage - 1})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                `;
            }

            // Page numbers - show limited pages with ellipsis
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if we're at the end
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // First page and ellipsis
            if (startPage > 1) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), 1)" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        1
                    </button>
                `;
                if (startPage > 2) {
                    paginationHTML += `
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    `;
                }
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    paginationHTML += `
                        <button class="px-3 py-2 text-sm border border-blue-500 bg-blue-500 text-white rounded-lg transition">
                            ${i}
                        </button>
                    `;
                } else {
                    paginationHTML += `
                        <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${i})" 
                                class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                            ${i}
                        </button>
                    `;
                }
            }

            // Last page and ellipsis
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHTML += `
                        <span class="px-3 py-2 text-sm text-gray-400">...</span>
                    `;
                }
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${totalPages})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        ${totalPages}
                    </button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `
                    <button onclick="loadBookings(getCurrentStatus(), getCurrentSearch(), ${currentPage + 1})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
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

        // Get status color - UPDATED: Only pending and confirmed
        function getStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'confirmed':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
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

        // Delete booking
        function deleteBooking(bookingId) {
            if (!confirm('Are you sure you want to delete this booking?')) return;

            fetch(`/admin/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking deleted successfully!');
                    loadBookings(getCurrentStatus(), getCurrentSearch(), currentPage);
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete booking'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting booking');
            });
        }

        // Export to CSV
        function exportToCSV() {
            const bookings = allBookings;
            if (bookings.length === 0) {
                alert('No data to export');
                return;
            }

            const headers = ['Guest Name', 'Email', 'Phone', 'Booking Type', 'Check-in', 'Check-out', 'Guests', 'Units', 'Total Price', 'Amount Paid', 'Amount Refunded', 'Net Paid', 'Remaining Balance', 'Status', 'Payment Status', 'Special Requirements'];
            const csvData = bookings.map(booking => [
                `"${booking.guest_name}"`,
                `"${booking.email}"`,
                `"${booking.phone}"`,
                `"${booking.booking_type}"`,
                `"${booking.checkin_date}"`,
                `"${booking.checkout_date || 'N/A'}"`,
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
            ]);

            const csvContent = [
                headers.join(','),
                ...csvData.map(row => row.join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `active_reservations_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print table - UPDATED: Only show pending and confirmed
        function printTable() {
            const printWindow = window.open('', '_blank');
            const bookings = allBookings;
            
            if (bookings.length === 0) {
                alert('No data to print');
                return;
            }

            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Active Reservations Report - Villa Elena</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2d3748; text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        .status-confirmed { background-color: #d1fae5; color: #065f46; }
                        .status-pending { background-color: #fef3c7; color: #92400e; }
                        .print-date { text-align: right; margin-bottom: 20px; color: #6b7280; }
                    </style>
                </head>
                <body>
                    <h1>Active Reservations Report - Villa Elena</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleString()}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Booking Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Guests</th>
                                <th>Units</th>
                                <th>Total Price</th>
                                <th>Amount Paid</th>
                                <th>Amount Refunded</th>
                                <th>Net Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bookings.map(booking => `
                                <tr>
                                    <td>${booking.guest_name}</td>
                                    <td>${booking.email}</td>
                                    <td>${booking.phone}</td>
                                    <td>${booking.booking_type}</td>
                                    <td>${booking.checkin_date}</td>
                                    <td>${booking.checkout_date || 'N/A'}</td>
                                    <td>${booking.num_guests}</td>
                                    <td>${booking.units}</td>
                                    <td>₱${parseFloat(booking.total_price).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.total_paid || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.total_refunded || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.net_paid || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}</td>
                                    <td><span class="status-${booking.booking_status}">${booking.booking_status.toUpperCase()}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <div style="margin-top: 20px; text-align: center; color: #6b7280;">
                        Total Records: ${bookings.length}
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        // Export to PDF - UPDATED: Only active reservations
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const bookings = allBookings;
            
            if (bookings.length === 0) {
                alert('No data to export');
                return;
            }

            // Add title
            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Active Reservations Report - Villa Elena', 14, 15);
            
            // Add date
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);

            // Prepare table data
            const tableData = bookings.map(booking => [
                booking.guest_name,
                booking.email,
                booking.phone,
                booking.booking_type,
                booking.checkin_date,
                booking.checkout_date || 'N/A',
                booking.num_guests,
                booking.units,
                `₱${parseFloat(booking.total_price).toFixed(2)}`,
                `₱${parseFloat(booking.total_paid || 0).toFixed(2)}`,
                `₱${parseFloat(booking.total_refunded || 0).toFixed(2)}`,
                `₱${parseFloat(booking.net_paid || 0).toFixed(2)}`,
                `₱${parseFloat(booking.remaining_balance || booking.total_price).toFixed(2)}`,
                booking.booking_status.toUpperCase()
            ]);

            // Table headers
            const headers = [
                'Guest Name',
                'Email', 
                'Phone',
                'Type',
                'Check-in',
                'Check-out',
                'Guests',
                'Units',
                'Total',
                'Paid',
                'Refunded',
                'Net Paid',
                'Balance',
                'Status'
            ];

            // AutoTable plugin
            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: 30,
                styles: { fontSize: 8, cellPadding: 3 },
                headStyles: { fillColor: [59, 130, 246] },
                alternateRowStyles: { fillColor: [249, 250, 251] }
            });

            // Save PDF
            doc.save(`active_reservations_${new Date().toISOString().split('T')[0]}.pdf`);
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
            console.log('=== PAGE LOADED ===');
            loadBookings('all', '', 1);
        });
    </script>
</body>
</html>