<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Villa Elena Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }
        }

        @media (max-width: 640px) {
            #mainContent { padding: 0.75rem !important; }
        }

        /* ===== ROLE BADGES ===== */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid;
        }

        .role-manager {
            background-color: rgba(245, 158, 11, 0.1);
            color: #b45309;
            border-color: #fbbf24;
        }

        .role-staff {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
            border-color: #93c5fd;
        }

        /* ===== FILTER BAR ===== */
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
            outline: none;
            transition: border-color 0.2s;
        }

        .search-wrapper input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .search-wrapper i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .role-filter {
            min-width: 150px;
            padding: 0.625rem 2.5rem 0.625rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .role-filter:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        /* ===== RESPONSIVE TABLE ===== */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-track { background: #f1f5f9; }
        .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        #staffTable {
            width: 100%;
            min-width: 700px;
        }

        #staffTable th,
        #staffTable td {
            white-space: nowrap;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
        }

        #staffTable th {
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-row { transition: background-color 0.2s; }
        .table-row:hover { background-color: #f9fafb !important; }

        /* ===== MOBILE CARD VIEW ===== */
        .staff-card-item {
            display: none;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 3px solid #3b82f6;
            transition: all 0.2s ease;
        }

        .staff-card-item .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .staff-card-item .card-body {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .staff-card-item .card-item {
            display: flex;
            flex-direction: column;
        }

        .staff-card-item .card-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .staff-card-item .card-value {
            font-size: 0.875rem;
            color: #111827;
        }

        .staff-card-item .card-actions {
            display: flex;
            gap: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
        }

        /* ===== PAGINATION ===== */
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

        /* ===== STATS CARDS ===== */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        @media (max-width: 1024px) {
            .table-container { display: none; }
            .staff-card-item { display: block; }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                width: 100%;
            }

            .role-filter,
            .clear-btn {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .staff-card-item .card-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }

            .pagination-info { font-size: 0.75rem; }
        }

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
            <div class="mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Staff Management</h1>
                        <p class="text-gray-500 text-sm mt-1">Manage your staff accounts and permissions</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs md:text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Card --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

                {{-- Top Row --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Staff Records</h2>
                    <a href="{{ route('admin.staff.create') }}"
                       class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm">
                        <i class="fas fa-plus"></i>
                        Add New Staff
                    </a>
                </div>

                {{-- Filter Bar --}}
                <div class="filter-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search by name, email, or phone..."
                        >
                    </div>
                    <select id="roleFilter" class="role-filter">
                        <option value="all">All Roles</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <button type="button" onclick="clearFilters()"
                            class="clear-btn bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-medium text-sm transition-colors">
                        Clear
                    </button>
                </div>

                {{-- Desktop Table --}}
                <div class="table-container">
                    <table class="w-full" id="staffTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff Member</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                                    <p>Loading staff...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards Container --}}
                <div id="staffCardsContainer"></div>

                {{-- Pagination --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-6 pt-6 border-t border-gray-200 gap-4">
                    <div class="pagination-info">
                        <span>Showing <strong id="showingFrom">0</strong> to <strong id="showingTo">0</strong> of <strong id="totalRecords">0</strong> staff member(s)</span>
                    </div>
                    <div class="pagination-controls" id="paginationControls"></div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Staff</p>
                            <p class="text-2xl font-bold text-gray-900" id="statTotal">—</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-lg bg-amber-50 text-amber-600">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Managers</p>
                            <p class="text-2xl font-bold text-gray-900" id="statManagers">—</p>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Staff Members</p>
                            <p class="text-2xl font-bold text-gray-900" id="statStaff">—</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Success / Error Alerts --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", confirmButtonColor: '#3b82f6' });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('error') }}", confirmButtonColor: '#3b82f6' });
        });
    </script>
    @endif

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
            if (window.innerWidth > 768) {
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                }
            } else {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
        });

        window.addEventListener('resize', () => {
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarState');
            if (window.innerWidth > 768) {
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                } else {
                    mainContent.classList.remove('ml-24');
                    mainContent.classList.add('ml-64');
                }
            } else {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
            if (allStaff.length > 0) updatePagination();
        });

        // ============================================
        // DATA & PAGINATION STATE
        // ============================================
        const csrfToken = '{{ csrf_token() }}';
        const currentUserId = '{{ auth()->id() }}';

        let allStaff = [];
        let filteredStaff = [];
        let currentPage = 1;
        const perPage = 8;

        // ============================================
        // BOOTSTRAP — seed from server-rendered data
        // ============================================
        // We embed the PHP collection as JSON so no extra AJAX call is needed.
        // If you prefer AJAX, replace this with a fetch().
        const staffData = @json($staff);

        document.addEventListener('DOMContentLoaded', () => {
            allStaff = staffData.map(m => ({
                id:          m.userID,
                name:        m.name,
                username:    m.username,
                email:       m.email,
                phone:       m.phoneNumber ?? 'N/A',
                role:        m.role,
                created_at:  m.created_at,
            }));

            updateStats();
            applyFilters();
        });

        // ============================================
        // FILTERS & SEARCH
        // ============================================
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value  = 'all';
            currentPage = 1;
            applyFilters();
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase().trim();
            const role   = document.getElementById('roleFilter').value;

            filteredStaff = allStaff.filter(m => {
                const matchRole   = role === 'all' || m.role === role;
                const matchSearch = !search ||
                    m.name.toLowerCase().includes(search)     ||
                    m.email.toLowerCase().includes(search)    ||
                    m.phone.toLowerCase().includes(search)    ||
                    m.username.toLowerCase().includes(search);
                return matchRole && matchSearch;
            });

            const totalPages = Math.ceil(filteredStaff.length / perPage);
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
            else if (totalPages === 0) currentPage = 1;

            const start       = (currentPage - 1) * perPage;
            const paginated   = filteredStaff.slice(start, start + perPage);

            renderTable(paginated);
            renderCards(paginated);
            updatePagination();
            updateShowingText(paginated.length);
        }

        // ============================================
        // RENDER — Desktop Table
        // ============================================
        function renderTable(data) {
            const tbody = document.getElementById('staffTableBody');

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-base font-medium">No staff members found</p>
                            <p class="text-sm mt-1">Try adjusting your search or filter.</p>
                        </td>
                    </tr>`;
                return;
            }

            tbody.innerHTML = data.map(m => `
                <tr class="border-b border-gray-100 table-row">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                ${m.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">${escHtml(m.name)}</p>
                                <p class="text-sm text-gray-500">@${escHtml(m.username)}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <p class="text-sm text-gray-900">${escHtml(m.email)}</p>
                        <p class="text-sm text-gray-500">${escHtml(m.phone)}</p>
                    </td>
                    <td class="py-4 px-4">
                        <span class="role-badge role-${m.role}">
                            ${m.role.charAt(0).toUpperCase() + m.role.slice(1)}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-sm text-gray-500">
                        ${formatDate(m.created_at)}
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-1">
                            <a href="/admin/staff/${m.id}/edit"
                               class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            ${m.id != currentUserId ? `
                            <button onclick="confirmDelete('${m.id}', '${escJs(m.name)}')"
                                    class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>` : ''}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // ============================================
        // RENDER — Mobile Cards
        // ============================================
        function renderCards(data) {
            const container = document.getElementById('staffCardsContainer');

            if (data.length === 0) {
                container.innerHTML = '';
                return;
            }

            container.innerHTML = data.map(m => `
                <div class="staff-card-item">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold flex-shrink-0">
                                ${m.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">${escHtml(m.name)}</p>
                                <p class="text-sm text-gray-500">@${escHtml(m.username)}</p>
                            </div>
                        </div>
                        <span class="role-badge role-${m.role}">
                            ${m.role.charAt(0).toUpperCase() + m.role.slice(1)}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-item">
                            <span class="card-label"><i class="fas fa-envelope mr-1"></i>Email</span>
                            <span class="card-value">${escHtml(m.email)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label"><i class="fas fa-phone mr-1"></i>Phone</span>
                            <span class="card-value">${escHtml(m.phone)}</span>
                        </div>
                        <div class="card-item">
                            <span class="card-label"><i class="fas fa-calendar mr-1"></i>Joined</span>
                            <span class="card-value">${formatDate(m.created_at)}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="/admin/staff/${m.id}/edit"
                           class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        ${m.id != currentUserId ? `
                        <button onclick="confirmDelete('${m.id}', '${escJs(m.name)}')"
                                class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>` : ''}
                    </div>
                </div>
            `).join('');
        }

        // ============================================
        // PAGINATION
        // ============================================
        function updatePagination() {
            const total      = filteredStaff.length;
            const totalPages = Math.ceil(total / perPage);
            const controls   = document.getElementById('paginationControls');

            if (totalPages <= 1) { controls.innerHTML = ''; return; }

            let html = '';

            html += `<button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">
                        <i class="fas fa-chevron-left"></i>
                     </button>`;

            const maxVisible = window.innerWidth < 640 ? 3 : 5;
            let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let end   = Math.min(totalPages, start + maxVisible - 1);
            if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);

            if (start > 1) {
                html += `<button class="pagination-btn" onclick="goToPage(1)">1</button>`;
                if (start > 2) html += `<span class="pagination-ellipsis">...</span>`;
            }

            for (let i = start; i <= end; i++) {
                html += `<button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            if (end < totalPages) {
                if (end < totalPages - 1) html += `<span class="pagination-ellipsis">...</span>`;
                html += `<button class="pagination-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }

            html += `<button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="goToPage(${currentPage + 1})">
                        <i class="fas fa-chevron-right"></i>
                     </button>`;

            controls.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            applyFilters();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateShowingText(showing) {
            const total = filteredStaff.length;
            const from  = total === 0 ? 0 : ((currentPage - 1) * perPage) + 1;
            const to    = Math.min(from + showing - 1, total);
            document.getElementById('showingFrom').textContent  = from;
            document.getElementById('showingTo').textContent    = to;
            document.getElementById('totalRecords').textContent = total;
        }

        // ============================================
        // STATS
        // ============================================
        function updateStats() {
            document.getElementById('statTotal').textContent    = allStaff.length;
            document.getElementById('statManagers').textContent = allStaff.filter(m => m.role === 'manager').length;
            document.getElementById('statStaff').textContent    = allStaff.filter(m => m.role === 'staff').length;
        }

        // ============================================
        // DELETE CONFIRMATION
        // ============================================
        async function confirmDelete(userId, userName) {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Delete Staff Member?',
                html: `Are you sure you want to delete <strong>${userName}</strong>?<br><span class="text-sm text-gray-500">This action cannot be undone.</span>`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                const form        = document.createElement('form');
                form.method       = 'POST';
                form.action       = `/admin/staff/${userId}`;

                const csrfInput   = document.createElement('input');
                csrfInput.type    = 'hidden';
                csrfInput.name    = '_token';
                csrfInput.value   = csrfToken;

                const methodInput  = document.createElement('input');
                methodInput.type   = 'hidden';
                methodInput.name   = '_method';
                methodInput.value  = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // ============================================
        // LISTENERS
        // ============================================
        document.getElementById('roleFilter').addEventListener('change', () => {
            currentPage = 1;
            applyFilters();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(this._debounce);
            this._debounce = setTimeout(() => {
                currentPage = 1;
                applyFilters();
            }, 350);
        });

        // ============================================
        // HELPERS
        // ============================================
        function formatDate(dateStr) {
            if (!dateStr) return 'N/A';
            return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function escJs(str) {
            return String(str).replace(/'/g, "\\'");
        }
    </script>
</body>
</html>