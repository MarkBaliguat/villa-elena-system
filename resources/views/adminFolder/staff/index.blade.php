<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Villa Elena Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Main Content Responsive Layout */
        #mainContent {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 16rem; /* Initial margin for expanded sidebar */
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

        /* Responsive adjustments for mobile/tablet */
        @media (max-width: 768px) {
            #mainContent {
                margin-left: 5.5rem !important;
                width: calc(100% - 5.5rem) !important;
                padding: 1rem !important;
            }
        }

        @media (max-width: 640px) {
            #mainContent {
                padding: 0.75rem !important;
            }
        }

        /* Table responsive container */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Mobile card view - hidden by default, shown on mobile */
        .staff-card {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-table {
                display: none;
            }
            
            .staff-card {
                display: block;
            }
        }

        .sidebar {
            width: 16rem;
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }
        
        .icon-container {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .active-item {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-right: 2px solid #2563eb;
            font-weight: 600;
        }
        
        .hover-item:hover {
            background-color: #f9fafb;
            color: #2563eb;
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid;
        }
        
        .role-manager {
            background-color: rgba(245, 158, 11, 0.1);
            color: #b45309;
            border-color: #fbbf24;
        }
        
        .role-admin {
            background-color: rgba(244, 63, 94, 0.1);
            color: #be123c;
            border-color: #fda4af;
        }
        
        .role-staff {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
            border-color: #93c5fd;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('adminFolder.partials.sidebar')
    

    <!-- Main Content -->
    <div class="ml-64 p-8" id="mainContent">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Staff Management</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1 md:mt-2">Manage your staff accounts and permissions</p>
            </div>
            <div class="text-left md:text-right">
                <div class="text-xs md:text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm md:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm md:text-base">
                {{ session('error') }}
            </div>
        @endif

        <!-- Add New Staff Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="text-sm text-gray-600">
                Showing {{ $staff->count() }} staff member(s)
            </div>
            <a href="{{ route('admin.staff.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm md:text-base">
                <i class="fas fa-plus"></i>
                Add New Staff
            </a>
        </div>

        <!-- Desktop Table View -->
        <div class="desktop-table bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($staff as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->phoneNumber ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->role === 'manager')
                                        <span class="role-badge role-manager">Manager</span>
                                    @else
                                        <span class="role-badge role-staff">Staff</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.staff.edit', $member->userID) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($member->userID != auth()->id())
                                            <form action="{{ route('admin.staff.destroy', $member->userID) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-lg font-medium">No staff members found</p>
                                        <p class="mt-1">Get started by adding your first staff member.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="staff-card space-y-4">
            @forelse($staff as $member)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold">
                                {{ substr($member->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500">{{ $member->username }}</div>
                            </div>
                        </div>
                        @if($member->role === 'manager')
                            <span class="role-badge role-manager">Manager</span>
                        @else
                            <span class="role-badge role-staff">Staff</span>
                        @endif
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-envelope text-gray-400 w-4"></i>
                            <span class="text-gray-700">{{ $member->email }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-phone text-gray-400 w-4"></i>
                            <span class="text-gray-700">{{ $member->phoneNumber ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fas fa-calendar text-gray-400 w-4"></i>
                            <span class="text-gray-500">Joined {{ $member->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.staff.edit', $member->userID) }}" 
                           class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        @if($member->userID != auth()->id())
                            <form action="{{ route('admin.staff.destroy', $member->userID) }}" 
                                  method="POST" 
                                  class="flex-1"
                                  onsubmit="return confirm('Are you sure you want to delete this staff member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg font-medium flex items-center justify-center gap-2 transition-colors text-sm">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-700">No staff members found</p>
                    <p class="text-gray-500 mt-1">Get started by adding your first staff member.</p>
                </div>
            @endforelse
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-6 md:mt-8">
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fas fa-users text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $staff->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-50 text-green-600">
                        <i class="fas fa-user-shield text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Managers</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $staff->where('role', 'manager')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-50 text-purple-600">
                        <i class="fas fa-user-check text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs md:text-sm font-medium text-gray-600">Staff Members</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $staff->where('role', 'staff')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== SIDEBAR RESPONSIVE SCRIPT =====
        // Listen for sidebar toggle events
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

        // Check initial sidebar state on load AND handle responsive behavior
        document.addEventListener('DOMContentLoaded', () => {
            const savedState = localStorage.getItem('sidebarState');
            const mainContent = document.getElementById('mainContent');
            
            // Apply saved state only on desktop
            if (window.innerWidth > 768) {
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                }
            } else {
                // On mobile/tablet, always use collapsed spacing
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
        });

        // Handle window resize - adjust spacing based on screen size
        window.addEventListener('resize', () => {
            const mainContent = document.getElementById('mainContent');
            const savedState = localStorage.getItem('sidebarState');
            
            if (window.innerWidth > 768) {
                // Desktop: respect saved state
                if (savedState === 'collapsed') {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-24');
                } else {
                    mainContent.classList.remove('ml-24');
                    mainContent.classList.add('ml-64');
                }
            } else {
                // Mobile/tablet: always collapsed spacing
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-24');
            }
        });
        // ===== END SIDEBAR RESPONSIVE SCRIPT =====
    </script>
</body>
</html>