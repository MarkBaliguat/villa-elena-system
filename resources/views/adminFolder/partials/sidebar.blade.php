<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena Admin</title>
<<<<<<< HEAD
=======
    
    <!-- CRITICAL: Set sidebar state BEFORE page renders to prevent animation -->
    <script>
        // This runs immediately before the page renders
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isMobile = window.innerWidth <= 768;
            
            if (!isMobile) {
                const savedState = localStorage.getItem('sidebarState');
                
                // Apply the saved state WITHOUT animation
                sidebar.style.transition = 'none';
                
                if (savedState === 'collapsed') {
                    sidebar.classList.remove('expanded');
                    sidebar.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.add('expanded');
                }
                
                // Force a reflow to ensure the changes are applied
                void sidebar.offsetWidth;
                
                // Re-enable transitions after state is set
                setTimeout(() => {
                    sidebar.style.transition = '';
                }, 10);
            }
        });
    </script>
    
>>>>>>> 0c222f4 (VT changed image pic)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Cursive font style - same as main site */
        .cursive-font {
            font-family: 'Dancing Script', cursive;
            font-weight: 700;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
        }
        
        /* Expanded and Collapsed States */
        .sidebar.expanded {
            width: 16rem;
        }
        
        .sidebar.collapsed {
            width: 5.5rem;
        }
        
        .icon-container {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            transition: margin-right 0.3s ease;
        }
        
        .sidebar.expanded .icon-container {
            margin-right: 12px;
        }
        
        .sidebar.collapsed .icon-container {
            margin-right: 0;
        }
        
        .active-item {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-right: 2px solid #2563eb;
            font-weight: 600;
        }
        
        .hover-item:hover {
            background-color: #f3f4f6;
            color: #2563eb;
            transform: translateX(2px);
        }
        
        /* IMPROVED: Better hover effect when collapsed */
        .sidebar.collapsed .hover-item:hover {
            background-color: #eff6ff;
            color: #2563eb;
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
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
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        /* Smaller logout button when collapsed to save space */
        .sidebar.collapsed .logout-btn {
            width: 32px;
            height: 32px;
            min-width: 32px;
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
        
        /* ===== HEADER STYLES - TOGGLE BETWEEN LOGO AND TEXT ===== */
        
        /* Full header */
        .sidebar-header {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        /* EXPANDED header - with blue gradient, TEXT ONLY (NO LOGO) */
        .sidebar.expanded .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* COLLAPSED header - NO BORDER, LOGO ONLY */
        .sidebar.collapsed .sidebar-header {
            padding: 1rem 0.5rem;
            background: white;
            border-bottom: none;
        }
        
        /* Logo container */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .logo-img {
            transition: all 0.3s ease;
            object-fit: contain;
        }
        
        /* EXPANDED STATE - HIDE LOGO COMPLETELY */
        .sidebar.expanded .logo-img {
            opacity: 0;
            width: 0;
            height: 0;
            margin-bottom: 0;
            transform: scale(0);
            visibility: hidden;
        }
        
        /* COLLAPSED STATE - SHOW LOGO */
        .sidebar.collapsed .logo-img {
            opacity: 1;
            width: 72px;
            height: 72px;
            margin-bottom: 0;
            transform: scale(1);
            visibility: visible;
        }
        
        /* EXPANDED STATE - HIDE LOGO CONTAINER */
        .sidebar.expanded .logo-container {
            display: none;
        }
        
        /* COLLAPSED STATE - SHOW LOGO CONTAINER */
        .sidebar.collapsed .logo-container {
            display: flex;
        }
        
        /* Brand text container */
        .brand-text {
            transition: all 0.3s ease;
            text-align: center;
        }
        
        /* EXPANDED STATE - SHOW FULL BRAND TEXT */
        .sidebar.expanded .brand-text {
            opacity: 1;
            max-height: 120px;
            visibility: visible;
            display: block;
        }
        
        /* COLLAPSED STATE - HIDE BRAND TEXT COMPLETELY */
        .sidebar.collapsed .brand-text {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            visibility: hidden;
            display: none;
        }
        
        .brand-title {
            transition: all 0.3s ease;
        }
        
        .sidebar.expanded .brand-title {
            font-size: 1.875rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .brand-subtitle {
            transition: all 0.3s ease;
        }
        
        .sidebar.expanded .brand-subtitle {
            font-size: 0.75rem;
            color: #bfdbfe;
            letter-spacing: 0.05em;
        }
        
        /* Toggle button */
        .toggle-btn {
            position: absolute;
            right: -18px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
            z-index: 60;
            border: 3px solid white;
        }
        
        .toggle-btn:hover {
            background: #1d4ed8;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.6);
        }
        
        .toggle-btn i {
            color: white;
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }
        
        .sidebar.collapsed .toggle-btn i {
            transform: rotate(180deg);
        }
        
        /* Hide text when collapsed */
        .nav-text,
        .section-title,
        .user-info-text {
            transition: opacity 0.3s ease, width 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .section-title,
        .sidebar.collapsed .user-info-text {
            opacity: 0;
            width: 0;
        }
        
        /* Navigation items alignment - CENTER WHEN COLLAPSED */
        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        /* IMPORTANT: Center icons when collapsed */
        .sidebar.collapsed .nav-item-link {
            justify-content: center;
            padding: 0.75rem 0.625rem;
            margin: 0 auto;
            width: fit-content;
        }
        
        /* Navigation items container - center all items when collapsed */
        .sidebar.collapsed nav > .nav-section > div {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        
        /* Individual nav item wrapper - center when collapsed */
        .sidebar.collapsed .nav-item-wrapper {
            width: fit-content;
            margin: 0 auto;
        }
        
        /* ===== FIXED TOOLTIP STYLES - USING FIXED POSITIONING ===== */
        .nav-item-wrapper {
            position: relative;
        }
        
        .nav-tooltip {
            position: fixed;
            padding: 0.5rem 0.875rem;
            background: #1f2937;
            color: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 9999 !important;
            pointer-events: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            transform: translateX(-10px);
        }
        
        /* Arrow for tooltip */
        .nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-width: 7px;
            border-style: solid;
            border-color: transparent #1f2937 transparent transparent;
        }
        
        /* Show tooltip on hover when collapsed */
        .sidebar.collapsed .nav-item-wrapper:hover .nav-tooltip {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateX(0);
        }
        
        /* Section indicator tooltips */
        .section-indicator {
            position: relative;
            display: none;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.5rem 0;
            margin-bottom: 0.25rem;
        }
        
        .sidebar.collapsed .section-indicator {
            display: flex;
        }
        
        .section-dot {
            width: 4px;
            height: 4px;
            background: #9ca3af;
            border-radius: 50%;
        }
        
        .section-label-tooltip {
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            padding: 0.375rem 0.625rem;
            background: #2563eb;
            color: white;
            border-radius: 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 100;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .section-label-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent #2563eb transparent transparent;
        }
        
        .section-indicator:hover .section-label-tooltip {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 16px);
        }
        
        /* User section - CENTER EVERYTHING WHEN COLLAPSED */
        .user-section-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* IMPORTANT: Center logout button when collapsed */
        .sidebar.collapsed .user-section-container {
            justify-content: center;
            width: 100%;
        }
        
        /* Hide user info when collapsed */
        .sidebar.collapsed .user-section-container > .flex {
            display: none;
        }
        
        /* Show only logout button centered when collapsed */
        .sidebar.collapsed .user-section-container form {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .user-avatar {
            width: 2rem;
            height: 2rem;
            min-width: 2rem;
            transition: opacity 0.3s ease, transform 0.3s ease, margin-right 0.3s ease;
        }
        
        .sidebar.expanded .user-avatar {
            margin-right: 0.5rem;
        }
        
        .sidebar.collapsed .user-avatar {
            opacity: 0;
            transform: scale(0);
            width: 0;
            min-width: 0;
            height: 0;
            margin-right: 0;
        }
        
        .sidebar.collapsed .logout-btn {
            margin: 0 auto;
        }
        
        /* User info text container */
        .user-info-container {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .sidebar.collapsed .user-info-container {
            display: none;
        }
        
        /* User name text */
        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Footer padding */
        .sidebar-footer {
            padding: 1rem;
        }
        
        .sidebar.collapsed .sidebar-footer {
            padding: 0.75rem;
            display: flex;
            justify-content: center;
        }
        
        /* Navigation scroll */
        nav {
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            flex: 1;
        }
        
        nav::-webkit-scrollbar {
            width: 6px;
        }
        
        nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        nav::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        
        nav::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Navigation sections */
        .nav-section {
            margin-bottom: 0.5rem;
        }
        
        /* ===== RESPONSIVE BREAKPOINTS ===== */
        
        /* Tablet adjustments (medium screens) */
        @media (max-width: 1024px) {
            .sidebar.expanded {
                width: 14rem; /* Slightly narrower on tablets */
            }
        }
        
        /* Mobile - ALWAYS COLLAPSED, SMALLER SIZE */
        @media (max-width: 768px) {
            /* Force collapsed state and smaller width */
            .sidebar {
                width: 4.5rem !important;
            }
            
            .sidebar.expanded {
                width: 4.5rem !important;
            }
            
            .sidebar.collapsed {
                width: 4.5rem !important;
            }
            
            /* Hide toggle button on mobile */
            .toggle-btn {
                display: none !important;
            }
            
            /* Force all text hidden */
            .nav-text,
            .section-title,
            .user-info-text {
                opacity: 0 !important;
                width: 0 !important;
                display: none !important;
            }
            
            /* Center all nav items */
            .nav-item-link {
                justify-content: center !important;
                padding: 0.75rem 0.5rem !important;
                margin: 0 auto !important;
                width: fit-content !important;
            }
            
            nav > .nav-section > div {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }
            
            /* User section - center logout */
            .user-section-container {
                justify-content: center !important;
                width: 100% !important;
            }
            
            .user-section-container > .flex {
                display: none !important;
            }
            
            .user-section-container form {
                display: flex;
                justify-content: center;
                width: 100%;
            }
            
            .user-info-container {
                display: none !important;
            }
            
            .user-avatar {
                opacity: 0 !important;
                transform: scale(0) !important;
                width: 0 !important;
                min-width: 0 !important;
                height: 0 !important;
            }
            
            .logout-btn {
                margin: 0 auto;
                width: 32px;
                height: 32px;
            }
            
            .icon-container {
                margin-right: 0 !important;
            }
            
            /* Show section indicators */
            .section-indicator {
                display: flex !important;
            }
            
            /* Mobile header - smaller logo */
            .sidebar-header {
                padding: 0.75rem 0.5rem !important;
                background: white !important;
                border-bottom: none !important;
            }
            
            .logo-container {
                display: flex !important;
            }
            
            .logo-img {
                opacity: 1 !important;
                width: 56px !important;
                height: 56px !important;
                margin-bottom: 0 !important;
                transform: scale(1) !important;
                visibility: visible !important;
            }
            
            .brand-text {
                opacity: 0 !important;
                max-height: 0 !important;
                overflow: hidden !important;
                visibility: hidden !important;
                display: none !important;
            }
            
            /* Mobile tooltips still work */
            .nav-item-wrapper:hover .nav-tooltip {
                opacity: 1;
                visibility: visible;
            }
        }
        
        /* Extra small mobile devices */
        @media (max-width: 480px) {
            .sidebar {
                width: 4rem !important;
            }
            
            .sidebar.expanded,
            .sidebar.collapsed {
                width: 4rem !important;
            }
            
            .logo-img {
                width: 48px !important;
                height: 48px !important;
            }
            
            .icon-container {
                width: 20px !important;
                min-width: 20px !important;
            }
            
            .nav-item-link {
                padding: 0.625rem 0.375rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
<<<<<<< HEAD
    <!-- Sidebar - DEFAULT STATE: COLLAPSED (Logo Only) -->
    <div class="sidebar collapsed" id="sidebar">
=======
    <!-- Sidebar - DEFAULT STATE: EXPANDED (Text Visible) -->
    <div class="sidebar expanded" id="sidebar">
>>>>>>> 0c222f4 (VT changed image pic)
        <!-- Toggle Button (Desktop only) -->
        <div class="toggle-btn" id="toggleBtn">
            <i class="fas fa-chevron-left"></i>
        </div>

        <!-- Header - Dynamic: COLLAPSED = Logo Only | EXPANDED = Text Only -->
        <div class="sidebar-header">
            <!-- Logo (SHOWN WHEN COLLAPSED, HIDDEN WHEN EXPANDED) -->
            <div class="logo-container">
               <img src="{{ asset('images/VillaElenaLogo.png') }}" alt="Villa Elena Logo" class="logo-img">
            </div>
            
            <!-- Brand Text (HIDDEN WHEN COLLAPSED, SHOWN WHEN EXPANDED) -->
            <div class="brand-text">
                <h1 class="cursive-font brand-title">Villa Elena</h1>
                <p class="brand-subtitle">Family Resort & Agri-Tourism Farm</p>
            </div>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 px-3 pb-2">
            <!-- Main Section -->
            <div class="nav-section mb-1 pt-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2 section-title">Main</p>
                <div class="space-y-0.5">
                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.dashboard') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="nav-tooltip">Dashboard</div>
                    </div>
                </div>
            </div>

            <!-- Operations Section -->
            <div class="nav-section mb-1">
                <!-- Section Indicator (shown when collapsed) -->
                <div class="section-indicator">
                    <div class="section-dot"></div>
                    <div class="section-label-tooltip">Operations</div>
                </div>
                
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2 section-title">Operations</p>
                
                <div class="space-y-0.5">
                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.reservation') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.reservation') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span class="nav-text">Reservation</span>
                        </a>
                        <div class="nav-tooltip">Reservation</div>
                    </div>

                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.rooms-cottages') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.rooms-cottages') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-home"></i>
                            </div>
                            <span class="nav-text">Rooms & Cottages</span>
                        </a>
                        <div class="nav-tooltip">Rooms & Cottages</div>
                    </div>

                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.special-events') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.special-events') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="nav-text">Special Events</span>
                        </a>
                        <div class="nav-tooltip">Special Events</div>
                    </div>

                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.history') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.history') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-history"></i>
                            </div>
                            <span class="nav-text">History</span>
                        </a>
                        <div class="nav-tooltip">History</div>
                    </div>
                </div>
            </div>

            <!-- Management Section -->
            <div class="nav-section mb-1">
                <!-- Section Indicator (shown when collapsed) -->
                <div class="section-indicator">
                    <div class="section-dot"></div>
                    <div class="section-label-tooltip">Management</div>
                </div>
                
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2 section-title">Management</p>
                
                <div class="space-y-0.5">
                    @if(auth()->user()->role === 'manager')
                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.staff.index') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.staff.*') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-text">Staff</span>
                        </a>
                        <div class="nav-tooltip">Staff</div>
                    </div>
                    @endif

                    <div class="nav-item-wrapper">
                        <a href="{{ route('admin.pricing') }}" 
                            class="nav-item-link {{ request()->routeIs('admin.pricing') ? 'active-item' : 'text-gray-700 hover-item' }}">
                            <div class="icon-container">
                                <i class="fas fa-tags"></i>
                            </div>
                            <span class="nav-text">Pricing</span>
                        </a>
                        <div class="nav-tooltip">Pricing</div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Footer with User Info -->
        <div class="border-t bg-white sidebar-footer">
            <div class="user-section-container">
                <!-- User Info (HIDDEN WHEN COLLAPSED) -->
                <div class="flex items-center flex-1 min-w-0">
                    <!-- USER AVATAR - WILL BE HIDDEN WHEN COLLAPSED -->
                    <div class="user-avatar bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <!-- User info text container -->
                    <div class="user-info-container">
                        <p class="user-name">{{ auth()->user()->name }}</p>
                        <div class="flex items-center">
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
                        class="logout-btn flex items-center justify-center group relative">
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

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');

        // Check if we're on mobile
        function isMobile() {
            return window.innerWidth <= 768;
        }
<<<<<<< HEAD

        // Desktop toggle only - mobile stays collapsed
        if (!isMobile()) {
            // DEFAULT STATE: Check if there's a saved state, otherwise stay collapsed
            const savedState = localStorage.getItem('sidebarState');
            // Only expand if explicitly saved as expanded
            if (savedState === 'expanded') {
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('expanded');
            }
=======
>>>>>>> 0c222f4 (VT changed image pic)

        // Desktop toggle functionality
        if (!isMobile()) {
            // Toggle and save state
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('expanded');
                sidebar.classList.toggle('collapsed');
                
                // Save state
                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
                
                // Broadcast event to dashboard
                window.dispatchEvent(new CustomEvent('sidebarToggled', { 
                    detail: { collapsed: sidebar.classList.contains('collapsed') }
                }));
            });
        }

        // Handle window resize - force collapse on mobile
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (isMobile()) {
                    // Force collapsed on mobile
                    sidebar.classList.remove('expanded');
                    sidebar.classList.add('collapsed');
                } else {
<<<<<<< HEAD
                    // Restore saved state on desktop
                    const savedState = localStorage.getItem('sidebarState');
                    if (savedState === 'expanded') {
=======
                    // Restore saved state on desktop (default to expanded if no saved state)
                    const savedState = localStorage.getItem('sidebarState');
                    if (savedState === 'collapsed') {
                        sidebar.classList.remove('expanded');
                        sidebar.classList.add('collapsed');
                    } else {
>>>>>>> 0c222f4 (VT changed image pic)
                        sidebar.classList.remove('collapsed');
                        sidebar.classList.add('expanded');
                    }
                }
                positionTooltips();
            }, 250);
        });

        // Tooltip positioning with fixed position
        function positionTooltips() {
            const navWrappers = document.querySelectorAll('.nav-item-wrapper');
            
            navWrappers.forEach(wrapper => {
                const tooltip = wrapper.querySelector('.nav-tooltip');
                const link = wrapper.querySelector('.nav-item-link');
                
                if (tooltip && link) {
                    // Position tooltip on mouseenter
                    wrapper.addEventListener('mouseenter', () => {
                        const rect = link.getBoundingClientRect();
                        const sidebarWidth = sidebar.offsetWidth;
                        
                        // Position tooltip to the right of the sidebar
                        tooltip.style.left = `${sidebarWidth + 12}px`;
                        tooltip.style.top = `${rect.top + (rect.height / 2)}px`;
                        tooltip.style.transform = 'translateY(-50%)';
                    });
                }
            });
        }

        // Initialize tooltip positioning
        positionTooltips();

        // Reposition on window resize
        window.addEventListener('resize', positionTooltips);
        
        // Reposition when sidebar toggles
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                setTimeout(positionTooltips, 300); // Wait for transition
            });
        }
    </script>
</body>
</html>