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
                        <h1 class="text-3xl font-bold text-gray-800">Booking History</h1>
                        <p class="text-gray-500 text-sm mt-1">View completed and cancelled bookings</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600" id="currentDate"></span>
                    </div>
                </div>
            </div>

            {{-- Filters and Stats --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Historical Records</h2>
                </div>

                {{-- Search and Filter --}}
                <div class="flex justify-between items-center mb-6">
                    <div class="flex gap-4">
                        <div class="relative">
                            <i class="fas fa-search search-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Search by guest name, email, or phone" 
                                class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
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

                {{-- History Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full" id="historyTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Guest Info</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Booking Details</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 uppercase">Payment Summary</th>
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

        // Clear filters function
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            loadHistory('all', '', 1);
        }

        // Load history data
        function loadHistory(status = 'all', search = '', page = 1) {
            const tbody = document.getElementById('historyTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading history...</p>
                    </td>
                </tr>
            `;

            let url = `/admin/history/data?`;
            if (status !== 'all') url += `status=${status}&`;
            if (search) url += `search=${search}&`;
            url += `page=${page}&per_page=${perPage}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allHistory = data.data;
                        totalRecords = data.total;
                        currentPage = page;
                        
                        displayHistory(data.data);
                        updatePagination(data.total, page);
                        updateShowingText(data.data.length, page, data.total);
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-8 text-red-500">
                                    Error loading history
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
                                Error loading history
                            </td>
                        </tr>
                    `;
                });
        }

        // Display history in table
        function displayHistory(history) {
            const tbody = document.getElementById('historyTableBody');
            
            if (history.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No historical records found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = history.map(record => `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
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
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(record.booking_status)}">
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

        // Update pagination controls
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
                    <button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${currentPage - 1})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                `;
            }

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // First page and ellipsis
            if (startPage > 1) {
                paginationHTML += `
                    <button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), 1)" 
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
                        <button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${i})" 
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
                    <button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${totalPages})" 
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                        ${totalPages}
                    </button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `
                    <button onclick="loadHistory(getCurrentStatus(), getCurrentSearch(), ${currentPage + 1})" 
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

        // Get status color
        function getStatusColor(status) {
            switch(status.toLowerCase()) {
                case 'completed':
                    return 'bg-green-100 text-green-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // Export to CSV
        function exportToCSV() {
            const history = allHistory;
            if (history.length === 0) {
                alert('No data to export');
                return;
            }

            const headers = ['Guest Name', 'Email', 'Phone', 'Booking Type', 'Status', 'Check-in', 'Check-out', 'Guests', 'Units', 'Total Price', 'Amount Paid', 'Amount Refunded', 'Net Paid', 'Event Type', 'Cancellation Reason', 'Created Date', 'Cancelled Date'];
            const csvData = history.map(record => [
                `"${record.guest_name}"`,
                `"${record.email}"`,
                `"${record.phone}"`,
                `"${record.booking_type}"`,
                `"${record.booking_status}"`,
                `"${record.checkin_date}"`,
                `"${record.checkout_date || 'N/A'}"`,
                `"${record.num_guests}"`,
                `"${record.units}"`,
                `"₱${parseFloat(record.total_price).toFixed(2)}"`,
                `"₱${parseFloat(record.total_paid || 0).toFixed(2)}"`,
                `"₱${parseFloat(record.total_refunded || 0).toFixed(2)}"`,
                `"₱${parseFloat(record.net_paid || 0).toFixed(2)}"`,
                `"${record.event_type}"`,
                `"${record.cancellation_reason || 'N/A'}"`,
                `"${new Date(record.created_at).toLocaleDateString()}"`,
                `"${record.cancelled_at ? new Date(record.cancelled_at).toLocaleDateString() : 'N/A'}"`
            ]);

            const csvContent = [
                headers.join(','),
                ...csvData.map(row => row.join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `booking_history_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print table
        function printTable() {
            const printWindow = window.open('', '_blank');
            const history = allHistory;
            
            if (history.length === 0) {
                alert('No data to print');
                return;
            }

            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Booking History Report - Villa Elena</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2d3748; text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        .status-completed { background-color: #d1fae5; color: #065f46; }
                        .status-cancelled { background-color: #fecaca; color: #991b1b; }
                        .print-date { text-align: right; margin-bottom: 20px; color: #6b7280; }
                    </style>
                </head>
                <body>
                    <h1>Booking History Report - Villa Elena</h1>
                    <div class="print-date">Printed on: ${new Date().toLocaleString()}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Guests</th>
                                <th>Units</th>
                                <th>Total Price</th>
                                <th>Paid</th>
                                <th>Refunded</th>
                                <th>Net Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${history.map(record => `
                                <tr>
                                    <td>${record.guest_name}</td>
                                    <td>${record.email}</td>
                                    <td>${record.phone}</td>
                                    <td>${record.booking_type}</td>
                                    <td><span class="status-${record.booking_status}">${record.booking_status.toUpperCase()}</span></td>
                                    <td>${record.checkin_date}</td>
                                    <td>${record.checkout_date || 'N/A'}</td>
                                    <td>${record.num_guests}</td>
                                    <td>${record.units}</td>
                                    <td>₱${parseFloat(record.total_price).toFixed(2)}</td>
                                    <td>₱${parseFloat(record.total_paid || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(record.total_refunded || 0).toFixed(2)}</td>
                                    <td>₱${parseFloat(record.net_paid || 0).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <div style="margin-top: 20px; text-align: center; color: #6b7280;">
                        Total Records: ${history.length}
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

        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const history = allHistory;
            
            if (history.length === 0) {
                alert('No data to export');
                return;
            }

            // Add title
            doc.setFontSize(16);
            doc.setTextColor(40, 40, 40);
            doc.text('Booking History Report - Villa Elena', 14, 15);
            
            // Add date
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 22);

            // Prepare table data
            const tableData = history.map(record => [
                record.guest_name,
                record.email,
                record.phone,
                record.booking_type,
                record.booking_status.toUpperCase(),
                record.checkin_date,
                record.checkout_date || 'N/A',
                record.num_guests,
                record.units,
                `₱${parseFloat(record.total_price).toFixed(2)}`,
                `₱${parseFloat(record.total_paid || 0).toFixed(2)}`,
                `₱${parseFloat(record.total_refunded || 0).toFixed(2)}`,
                `₱${parseFloat(record.net_paid || 0).toFixed(2)}`
            ]);

            // Table headers
            const headers = [
                'Guest Name',
                'Email', 
                'Phone',
                'Type',
                'Status',
                'Check-in',
                'Check-out',
                'Guests',
                'Units',
                'Total',
                'Paid',
                'Refunded',
                'Net Paid'
            ];

            // AutoTable plugin
            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: 30,
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [59, 130, 246] },
                alternateRowStyles: { fillColor: [249, 250, 251] }
            });

            // Save PDF
            doc.save(`booking_history_${new Date().toISOString().split('T')[0]}.pdf`);
        }

        // Filter and search
        document.getElementById('statusFilter').addEventListener('change', function() {
            const search = document.getElementById('searchInput').value;
            loadHistory(this.value, search, 1);
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            const status = document.getElementById('statusFilter').value;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                loadHistory(status, this.value, 1);
            }, 500);
        });

        // Load history on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== HISTORY PAGE LOADED ===');
            loadHistory('all', '', 1);
        });
    </script>
</body>
</html>