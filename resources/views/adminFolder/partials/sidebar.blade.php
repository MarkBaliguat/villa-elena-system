<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Cursive font style - same as main site */
        .cursive-font {
            font-family: 'Dancing Script', cursive;
            font-weight: 700;
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
        
        .logout-btn {
            position: relative;
            overflow: visible;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }
        
        .logout-icon {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .logout-btn:hover .logout-icon {
            transform: scale(0.9);
            color: white;
        }
        
        /* Tooltip Styles */
        .logout-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background-color: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 100;
        }
        
        .logout-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }
        
        .logout-btn:hover .logout-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-15px);
        }
        
        .pulse-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .logout-btn:hover .pulse-dot {
            opacity: 1;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.5);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Header - UPDATED WITH CURSIVE FONT -->
        <div class="p-6 border-b bg-gradient-to-r from-blue-600 to-blue-800 text-center">
            <h1 class="text-3xl cursive-font text-white mb-2">Villa Elena</h1>
            <p class="text-xs text-blue-100 tracking-wide">Family Resort & Agri-Tourism Farm</p>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 mt-6 space-y-2 px-3 overflow-y-auto">
            <!-- Main Section -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">Main</p>
                <a href="{{ route('admin.dashboard') }}" 
                    class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.dashboard') 
                        ? 'active-item' 
                        : 'text-gray-700 hover-item' }}">
                    <div class="icon-container">
                        <i class="fas fa-chart-pie text-gray-700"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Operations Section -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">Operations</p>
                
                <a href="{{ route('admin.reservation') }}" 
                    class="flex items-center justify-between px-3 py-3 rounded-lg transition-all duration-200 
                    {{ request()->routeIs('admin.reservation') 
                        ? 'active-item' 
                        : 'text-gray-700 hover-item' }} group">
                    <div class="flex items-center">
                        <div class="icon-container">
                            <i class="fas fa-calendar-alt text-gray-700"></i>
                        </div>
                        <span>Reservation</span>
                    </div>
                </a>

                <a href="{{ route('admin.rooms-cottages') }}" 
                    class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.rooms-cottages') 
                        ? 'active-item' 
                        : 'text-gray-700 hover-item' }}">
                    <div class="icon-container">
                        <i class="fas fa-home text-gray-700"></i>
                    </div>
                    <span>Rooms & Cottages</span>
                </a>

                <a href="{{ route('admin.special-events') }}" 
                    class="flex items-center justify-between px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.special-events') 
                        ? 'active-item' 
                        : 'text-gray-700 hover-item' }} group">
                    <div class="flex items-center">
                        <div class="icon-container">
                            <i class="fas fa-star text-gray-700"></i>
                        </div>
                        <span>Special Events</span>
                    </div>         
                </a>

                <a href="{{ route('admin.history') }}" 
                    class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                    {{ request()->routeIs('admin.history') 
                        ? 'active-item' 
                        : 'text-gray-700 hover-item' }}">
                    <div class="icon-container">
                        <i class="fas fa-history text-gray-700"></i>
                    </div>
                    <span>History</span>
                </a>
            </div>

            <!-- Management Section -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">Management</p>
                @if(auth()->user()->role === 'manager')
                <a href="{{ route('admin.staff.index') }}" 
                class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.staff.*') ? 'active-item' : 'text-gray-700 hover-item' }}">
                    <div class="icon-container">
                        <i class="fas fa-users text-gray-700"></i>
                    </div>
                    <span>Staff</span>
                </a>
                @endif

               
                <a href="{{ route('admin.pricing') }}" 
                    class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pricing') ? 'active-item' : 'text-gray-700 hover-item' }}">
                    <div class="icon-container">
                        <i class="fas fa-tags text-gray-700"></i>
                    </div>
                    <span>Pricing</span>
                </a>
                
            </div>
        </nav>

        <!-- Footer with User Info -->
        <div class="border-t p-4 bg-white">
            <!-- User Info with Logout Button on the right -->
            <div class="flex items-center justify-between">
                <!-- User Info -->
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <div class="flex items-center mt-1">
                            @if(auth()->user()->role === 'manager')
                                <span class="role-badge role-manager">Manager</span>
                            @elseif(auth()->user()->role === 'admin')
                                <span class="role-badge role-admin">Admin</span>
                            @else
                                <span class="role-badge role-staff">Staff</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modern Logout Button with Tooltip -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="logout-btn flex items-center justify-center group ml-3 relative">
                        <!-- Pulse Dot -->
                        <div class="pulse-dot"></div>
                        
                        <!-- Modern Power Icon -->
                        <div class="logout-icon flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        
                        <!-- Tooltip that appears on hover -->
                        <div class="logout-tooltip">
                            Logout
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>