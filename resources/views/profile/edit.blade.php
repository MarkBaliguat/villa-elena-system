<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-black: #000000;
            --primary-yellow: #FFD700;
            --secondary-yellow: #FFA500;
            --yellow-dark: #F59E0B;
            --light-bg: #FFFFFF;
            --card-bg: #FFFFFF;
            --text-dark: #1F2937;
            --text-medium: #6B7280;
            --text-light: #9CA3AF;
            --border-color: #E5E7EB;
            --sidebar-bg: #FAFAFA;
            --hover-bg: #FFF9E6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F0F2F5;
            color: #050505;
            overflow-x: hidden;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, var(--secondary-yellow), var(--yellow-dark));
        }
        
        /* FIXED: Main content spacing with proper navbar clearance */
        main.main-content {
            margin-top: 100px; /* Added space for navbar */
            margin-bottom: 80px;
            min-height: calc(100vh - 200px);
            background: #F0F2F5;
            padding: 0 20px;
        }

        main.main-content .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        main.main-content .page-subtitle {
            display: block;
            color: #65676B;
            font-size: 0.9375rem;
            font-weight: 400;
            margin-bottom: 24px;
        }
        
        /* Layout Container - SCOPED */
        main.main-content .settings-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0;
            display: flex;
            gap: 0;
            min-height: calc(100vh - 400px);
        }
        
        /* SCOPED SIDEBAR STYLING */
        main.main-content .settings-sidebar {
            width: 380px;
            border-right: 1px solid #E4E6EB;
            padding: 32px 20px;
            position: sticky;
            top: 100px; /* Adjusted for navbar */
            height: fit-content;
            background-color: #FFFFFF;
            border-radius: 12px;
            margin-right: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        main.main-content .sidebar-nav {
            list-style: none;
        }
        
        main.main-content .nav-item {
            margin: 0;
        }
        
        /* SCOPED NAV LINK - Won't affect navbar links */
        main.main-content .settings-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            margin: 6px 0;
            color: #000000 !important;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.25s ease;
            border-radius: 10px;
            position: relative;
            border: none;
            background: transparent;
        }
        
        main.main-content .settings-sidebar .nav-link:hover {
            background: #F7F8FA;
            color: #000000 !important;
            transform: translateX(4px);
        }
        
        main.main-content .settings-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: #000000 !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
            transform: translateX(0);
        }
        
        main.main-content .settings-sidebar .nav-link.active::before {
            display: none;
        }
        
        /* SCOPED NAV ICON */
        main.main-content .settings-sidebar .nav-icon {
            width: 40px;
            height: 40px;
            background: #F0F2F5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            flex-shrink: 0;
            color: #000000 !important;
            transition: all 0.25s ease;
        }
        
        main.main-content .settings-sidebar .nav-icon i {
            color: #000000 !important;
        }
        
        main.main-content .settings-sidebar .nav-link:hover .nav-icon {
            background: #E4E6EB;
            color: #000000 !important;
            transform: scale(1.05);
        }
        
        main.main-content .settings-sidebar .nav-link:hover .nav-icon i {
            color: #000000 !important;
        }
        
        main.main-content .settings-sidebar .nav-link.active .nav-icon {
            background: rgba(0, 0, 0, 0.15);
            color: #000000 !important;
        }
        
        main.main-content .settings-sidebar .nav-link.active .nav-icon i {
            color: #000000 !important;
        }
        
        main.main-content .settings-sidebar .nav-text {
            flex: 1;
            font-weight: 500;
            color: #000000 !important;
        }
        
        main.main-content .settings-sidebar .nav-link.active .nav-text {
            font-weight: 600;
            color: #000000 !important;
        }
        
        main.main-content .nav-badge {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 700;
        }
        
        /* SCOPED HEADER CONTENT IN SIDEBAR */
        main.main-content .header-content {
            padding: 0 0 24px 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #E4E6EB;
            max-width: 100%;
        }
        
        /* Content Area - Scoped */
        main.main-content .settings-content {
            flex: 1;
            padding: 32px 24px;
            background: transparent;
        }
        
        main.main-content .content-section {
            display: none;
            animation: fadeInUp 0.35s ease-out;
            background: #FFFFFF;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        main.main-content .content-section.active {
            display: block;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Section Header - Scoped */
        main.main-content .section-header {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #E4E6EB;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        
        main.main-content .section-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.25);
            flex-shrink: 0;
        }
        
        main.main-content .section-info {
            flex: 1;
        }
        
        main.main-content .section-title {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            line-height: 1.2;
        }
        
        main.main-content .section-description {
            color: var(--text-medium);
            font-size: 0.9375rem;
            font-weight: 400;
            line-height: 1.5;
        }
        
        /* Alert Boxes - Scoped */
        main.main-content .alert-box {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            border: 1px solid;
            animation: slideIn 0.25s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        main.main-content .alert-warning {
            background: #FFF3CD;
            border-color: #FFE69C;
        }
        
        main.main-content .alert-success {
            background: #D1E7DD;
            border-color: #BADBCC;
        }
        
        main.main-content .alert-error {
            background: #F8D7DA;
            border-color: #F5C2C7;
        }
        
        main.main-content .alert-info {
            background: #D1ECF1;
            border-color: #BEE5EB;
        }
        
        main.main-content .alert-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        main.main-content .alert-content h4 {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 0.9375rem;
        }
        
        main.main-content .alert-content p {
            font-size: 0.9375rem;
            line-height: 1.5;
            font-weight: 400;
        }
        
        /* SCOPED FORM ELEMENTS */
        main.main-content .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }
        
        main.main-content .form-group {
            display: flex;
            flex-direction: column;
        }
        
        main.main-content .form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 0.9375rem;
            color: #050505;
            margin-bottom: 8px;
            letter-spacing: 0;
            text-transform: none;
        }
        
        main.main-content .form-label i {
            display: none;
        }
        
        /* SCOPED INPUT */
        main.main-content .form-input {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border: 1.5px solid #CED0D4;
            border-radius: 8px;
            font-size: 0.9375rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            transition: all 0.2s ease;
            background: #FFFFFF;
            color: #050505;
            line-height: 46px;
        }
        
        main.main-content .form-input:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
            outline: none;
            background: #FFFFFF;
        }
        
        main.main-content .form-input:disabled,
        main.main-content .form-input[readonly] {
            background: #F0F2F5;
            cursor: not-allowed;
            opacity: 0.6;
            color: #65676B;
        }
        
        main.main-content .form-input::placeholder {
            color: #65676B;
            font-weight: 400;
            font-size: 0.9375rem;
        }
        
        /* Password Input Wrapper - Scoped */
        main.main-content .password-wrapper {
            position: relative;
            height: 46px;
        }
        
        main.main-content .password-wrapper .form-input {
            padding-right: 48px;
            height: 46px;
        }
        
        main.main-content .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-medium);
            transition: all 0.3s ease;
            font-size: 1.05rem;
            z-index: 10;
        }
        
        main.main-content .password-toggle:hover {
            color: var(--secondary-yellow);
            transform: translateY(-50%) scale(1.15);
        }
        
        /* Password Requirements Box - Scoped */
        main.main-content .requirements-box {
            background: #F7F8FA;
            border: 1.5px solid #E4E6EB;
            border-radius: 10px;
            padding: 18px;
            margin-top: 20px;
        }
        
        main.main-content .requirements-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #050505;
            margin-bottom: 14px;
            font-size: 0.9375rem;
        }
        
        main.main-content .requirements-list {
            display: grid;
            gap: 10px;
        }
        
        main.main-content .requirement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #65676B;
            font-size: 0.9375rem;
            font-weight: 400;
        }
        
        main.main-content .requirement-item i {
            color: #65676B;
            font-size: 0.8rem;
        }
        
        /* Buttons - Scoped */
        main.main-content .btn {
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            letter-spacing: 0;
            padding: 11px 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            height: 44px;
        }
        
        main.main-content .btn::before {
            display: none;
        }
        
        main.main-content .btn i,
        main.main-content .btn span {
            position: relative;
            z-index: 1;
        }
        
        main.main-content .btn:hover {
            transform: translateY(-2px);
            filter: brightness(0.95);
        }
        
        main.main-content .btn:active {
            transform: translateY(0) scale(0.98);
        }
        
        main.main-content .btn-primary {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: #000000;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.2);
        }
        
        main.main-content .btn-primary:hover {
            box-shadow: 0 4px 16px rgba(255, 215, 0, 0.35);
        }
        
        main.main-content .btn-success {
            background: #42B72A;
            color: white;
            box-shadow: 0 2px 8px rgba(66, 183, 42, 0.2);
        }
        
        main.main-content .btn-success:hover {
            background: #36A420;
            box-shadow: 0 4px 16px rgba(66, 183, 42, 0.35);
        }
        
        main.main-content .btn-danger {
            background: #E4E6EB;
            color: #050505;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }
        
        main.main-content .btn-danger:hover {
            background: #D8DADF;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }
        
        /* Success Feedback - Scoped */
        main.main-content .success-message {
            background: #D1E7DD;
            border: 1.5px solid #BADBCC;
            border-radius: 8px;
            padding: 10px 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: #0F5132;
            margin-left: 14px;
            animation: slideIn 0.25s ease-out;
            font-size: 0.9375rem;
        }
        
        main.main-content .success-message i {
            font-size: 1.05rem;
        }
        
        /* Error Messages - Scoped */
        main.main-content .error-message {
            color: #DC3545;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        main.main-content .error-message i {
            font-size: 0.75rem;
        }
        
        /* Form Actions - Scoped */
        main.main-content .form-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #E4E6EB;
        }
        
        /* Profile Avatar - Scoped */
        main.main-content .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 24px rgba(255, 215, 0, 0.25);
            margin-bottom: 20px;
        }
        
        /* Full Width Form Group - Scoped */
        main.main-content .form-group-full {
            grid-column: 1 / -1;
        }
        
        /* Responsive Design - Scoped */
        @media (max-width: 1024px) {
            main.main-content {
                margin-top: 80px; /* Adjusted for mobile navbar */
                padding: 0 16px;
            }
            
            main.main-content .settings-container {
                flex-direction: column;
            }
            
            main.main-content .settings-sidebar {
                width: 100%;
                position: static;
                border-right: none;
                border-bottom: none;
                padding: 0;
                background: transparent;
                margin-right: 0;
                margin-bottom: 20px;
                border-radius: 0;
                box-shadow: none;
            }
            
            main.main-content .header-content {
                padding: 32px 32px 24px 32px;
                margin-bottom: 0;
                border-bottom: none;
                background: #FFFFFF;
                border-radius: 12px 12px 0 0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }
            
            main.main-content .sidebar-nav {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0;
                padding: 0;
                background: #FFFFFF;
                border-radius: 0 0 12px 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                margin-bottom: 20px;
            }
            
            main.main-content .nav-item {
                flex-shrink: 1;
            }
            
            /* FIXED: Mobile navigation button styling - Remove ALL black effects */
            main.main-content .settings-sidebar .nav-link {
                padding: 16px 20px;
                border: none;
                border-bottom: 1.5px solid #E4E6EB;
                border-radius: 0;
                white-space: nowrap;
                margin: 0;
                background: #FFFFFF !important;
                color: #000000 !important;
                transform: none !important;
                -webkit-tap-highlight-color: transparent !important;
                outline: none !important;
            }
            
            main.main-content .settings-sidebar .nav-link:hover {
                background: #F7F8FA !important;
                color: #000000 !important;
                transform: none !important;
            }
            
            main.main-content .settings-sidebar .nav-link:active,
            main.main-content .settings-sidebar .nav-link:focus {
                background: #FFFFFF !important;
                outline: none !important;
                -webkit-tap-highlight-color: transparent !important;
            }
            
            main.main-content .settings-sidebar .nav-link.active {
                background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow)) !important;
                color: #000000 !important;
                border-color: transparent;
                position: relative;
                transform: none !important;
                -webkit-tap-highlight-color: transparent !important;
            }
            
            main.main-content .settings-sidebar .nav-link.active:active,
            main.main-content .settings-sidebar .nav-link.active:focus {
                background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow)) !important;
                outline: none !important;
            }
            
            /* FIXED: Remove black icon backgrounds on mobile */
            main.main-content .settings-sidebar .nav-icon {
                background: #F0F2F5 !important;
                color: #000000 !important;
            }
            
            main.main-content .settings-sidebar .nav-link:hover .nav-icon {
                background: #E4E6EB !important;
                color: #000000 !important;
                transform: none !important;
            }
            
            main.main-content .settings-sidebar .nav-link:active .nav-icon,
            main.main-content .settings-sidebar .nav-link:focus .nav-icon {
                background: #F0F2F5 !important;
                color: #000000 !important;
            }
            
            main.main-content .settings-sidebar .nav-link.active .nav-icon {
                background: rgba(0, 0, 0, 0.15) !important;
                color: #000000 !important;
            }
            
            main.main-content .settings-sidebar .nav-link.active:active .nav-icon,
            main.main-content .settings-sidebar .nav-link.active:focus .nav-icon {
                background: rgba(0, 0, 0, 0.15) !important;
                color: #000000 !important;
            }
            
            main.main-content .nav-item:last-child .nav-link {
                border-bottom: none;
                border-radius: 0 0 12px 12px;
            }
            
            main.main-content .settings-sidebar .nav-link.active::after {
                display: none; /* Removed black dot */
            }
            
            main.main-content .settings-sidebar .nav-link.active::before {
                display: none;
            }
            
            main.main-content .settings-content {
                padding: 0 0 20px;
            }
            
            main.main-content .content-section {
                padding: 32px;
                border-radius: 12px;
            }
            
            main.main-content .page-title {
                font-size: 1.625rem;
                padding-top: 0;
            }
            
            main.main-content .page-subtitle {
                display: block;
            }
        }
        
        @media (max-width: 640px) {
            main.main-content {
                margin-top: 70px;
                padding: 0 12px;
            }
            
            main.main-content .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            main.main-content .page-title {
                font-size: 1.5rem;
                padding-top: 0;
            }
            
            main.main-content .page-subtitle {
                font-size: 0.875rem;
                margin-bottom: 20px;
            }
            
            main.main-content .section-title {
                font-size: 1.375rem;
            }
            
            main.main-content .section-icon {
                width: 52px;
                height: 52px;
                font-size: 1.375rem;
            }
            
            main.main-content .settings-sidebar .nav-text {
                font-size: 0.9375rem;
                font-weight: 500;
            }
            
            main.main-content .settings-sidebar .nav-link {
                padding: 14px 16px;
            }
            
            main.main-content .form-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            
            main.main-content .btn {
                width: 100%;
            }
            
            main.main-content .success-message {
                margin-left: 0;
                margin-top: 12px;
                width: 100%;
                justify-content: center;
            }
            
            main.main-content .settings-content {
                padding: 0 0 20px;
            }
            
            main.main-content .content-section {
                padding: 20px;
            }
            
            main.main-content .section-header {
                gap: 14px;
                margin-bottom: 24px;
                padding-bottom: 20px;
            }
            
            main.main-content .form-input,
            main.main-content .password-wrapper {
                height: 48px;
            }
            
            main.main-content .form-input {
                line-height: 48px;
                font-size: 1rem;
            }
            
            main.main-content .header-content {
                padding: 24px 20px 20px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Import Navigation -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
    <main class="main-content">

        <!-- Settings Container -->
        <div class="settings-container">
            <!-- Left Sidebar Navigation -->
            <aside class="settings-sidebar">
                <div class="header-content">
                    <h1 class="page-title">Profile Settings</h1>
                    <p class="page-subtitle">Manage your account information and preferences</p>
                </div>
                <nav>
                    <ul class="sidebar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-section="personal-info">
                                <span class="nav-icon">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <span class="nav-text">Personal Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-section="password">
                                <span class="nav-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <span class="nav-text">Password & Security</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Content Area -->
            <div class="settings-content">
                <!-- Personal Information Section -->
                <section id="personal-info" class="content-section active">
                    <!-- Verification Alert -->
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert-box alert-warning">
                        <i class="fas fa-exclamation-triangle alert-icon" style="color: #F39C12;"></i>
                        <div class="alert-content">
                            <h4 style="color: #DC7633;">Email Verification Required</h4>
                            <p style="color: #A04000;">Your email address is unverified. Please verify to access all features.</p>
                            <button form="send-verification" style="margin-top: 10px; text-decoration: underline; background: none; border: none; color: #F39C12; cursor: pointer; font-weight: 600; font-size: 0.85rem;">
                                <i class="fas fa-paper-plane"></i> Resend Verification Email
                            </button>
                        </div>
                    </div>
                    @endif

                    @if (session('status') === 'verification-link-sent')
                    <div class="alert-box alert-success">
                        <i class="fas fa-check-circle alert-icon" style="color: #28A745;"></i>
                        <div class="alert-content">
                            <h4 style="color: #155724;">Verification Email Sent</h4>
                            <p style="color: #0F4C1E;">A new verification link has been sent to your email address.</p>
                        </div>
                    </div>
                    @endif

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <div class="section-header">
                        <span class="section-icon">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <div class="section-info">
                            <h2 class="section-title">Personal Information</h2>
                            <p class="section-description">Update your personal details and contact information</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" id="profile-form">
                        @csrf
                        @method('patch')

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    <span>Full Name</span>
                                </label>
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="form-input"
                                    value="{{ old('name', $user->name) }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('name')" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-at"></i>
                                    <span>Username</span>
                                </label>
                                <input 
                                    id="username" 
                                    name="username" 
                                    type="text" 
                                    class="form-input"
                                    value="{{ old('username', $user->username) }}" 
                                    required 
                                    autocomplete="username"
                                    placeholder="Choose a username"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('username')" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email Address</span>
                                </label>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    class="form-input"
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    autocomplete="email"
                                    placeholder="your.email@example.com"
                                    readonly
                                />
                                <x-input-error class="error-message" :messages="$errors->get('email')" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    <span>Phone Number</span>
                                </label>
                                <input 
                                    id="phoneNumber" 
                                    name="phoneNumber" 
                                    type="tel" 
                                    class="form-input"
                                    value="{{ old('phoneNumber', $user->phoneNumber) }}" 
                                    placeholder="09XXXXXXXXX"
                                    autocomplete="tel"
                                    maxlength="11"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('phoneNumber')" />
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <span>Save Changes</span>
                            </button>

                            @if (session('status') === 'profile-updated')
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i>
                                <span>Profile updated successfully!</span>
                            </div>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- Password & Security Section -->
                <section id="password" class="content-section">
                    <div class="section-header">
                        <span class="section-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <div class="section-info">
                            <h2 class="section-title">Password & Security</h2>
                            <p class="section-description">Update your password to keep your account secure</p>
                        </div>
                    </div>

                    <div class="alert-box alert-info">
                        <i class="fas fa-shield-alt alert-icon" style="color: #3B82F6;"></i>
                        <div class="alert-content">
                            <h4 style="color: #1E40AF;">Password Security</h4>
                            <p style="color: #1E3A8A;">Ensure your account is using a strong password to stay secure.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" id="password-form">
                        @csrf
                        @method('put')

                        <div class="form-grid">
                            <div class="form-group form-group-full">
                                <label class="form-label">
                                    <i class="fas fa-key"></i>
                                    <span>Current Password</span>
                                </label>
                                <div class="password-wrapper">
                                    <input 
                                        id="current_password" 
                                        name="current_password" 
                                        type="password" 
                                        class="form-input"
                                        autocomplete="current-password"
                                        placeholder="Enter your current password"
                                    />
                                    <i class="far fa-eye password-toggle" id="toggleCurrentPassword"></i>
                                </div>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('current_password')" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    <span>New Password</span>
                                </label>
                                <div class="password-wrapper">
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        class="form-input"
                                        autocomplete="new-password"
                                        placeholder="Enter new password"
                                    />
                                    <i class="far fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('password')" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Confirm Password</span>
                                </label>
                                <div class="password-wrapper">
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password" 
                                        class="form-input"
                                        autocomplete="new-password"
                                        placeholder="Confirm new password"
                                    />
                                    <i class="far fa-eye password-toggle" id="togglePasswordConfirmation"></i>
                                </div>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('password_confirmation')" />
                            </div>
                        </div>

                        <div class="requirements-box">
                            <h4 class="requirements-title">
                                <i class="fas fa-info-circle"></i>
                                Password Requirements
                            </h4>
                            <div class="requirements-list">
                                <div class="requirement-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>At least 8 characters long</span>
                                </div>
                                <div class="requirement-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Contains uppercase and lowercase letters</span>
                                </div>
                                <div class="requirement-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Contains at least one number (0-9)</span>
                                </div>
                                <div class="requirement-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Contains at least one special character (!@#$%^&*)</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i>
                                <span>Update Password</span>
                            </button>

                            @if (session('status') === 'password-updated')
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i>
                                <span>Password updated successfully!</span>
                            </div>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <!-- Import Footer -->
    @include('customerFolder.partials.footer')

    <script>
        // ========== TAB NAVIGATION ==========
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('main.main-content .settings-sidebar .nav-link');
            const sections = document.querySelectorAll('main.main-content .content-section');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    sections.forEach(s => s.classList.remove('active'));
                    
                    const targetSection = this.getAttribute('data-section');
                    document.getElementById(targetSection).classList.add('active');
                });
            });
        });

        // ========== PASSWORD VISIBILITY TOGGLE ==========
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
            if (!passwordInput || !toggleIcon) return;
            
            toggleIcon.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }

        togglePasswordVisibility('current_password', 'toggleCurrentPassword');
        togglePasswordVisibility('password', 'togglePassword');
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation');

        // ========== PHONE NUMBER FORMATTING ==========
        const phoneInput = document.getElementById('phoneNumber');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }
            });
        }

        // ========== FORM VALIDATION - PASSWORD UPDATE ==========
        document.getElementById('password-form').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (newPassword || confirmPassword) {
                if (!currentPassword) {
                    e.preventDefault();
                    alert('⚠️ Please enter your current password to update your password.');
                    document.getElementById('current_password').focus();
                    return false;
                }

                if (!newPassword) {
                    e.preventDefault();
                    alert('⚠️ Please enter a new password.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (!confirmPassword) {
                    e.preventDefault();
                    alert('⚠️ Please confirm your new password.');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('⚠️ New password and confirmation password do not match!');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }

                if (newPassword.length < 8) {
                    e.preventDefault();
                    alert('⚠️ Password must be at least 8 characters long.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (!/[a-z]/.test(newPassword)) {
                    e.preventDefault();
                    alert('⚠️ Password must contain at least one lowercase letter.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (!/[A-Z]/.test(newPassword)) {
                    e.preventDefault();
                    alert('⚠️ Password must contain at least one uppercase letter.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (!/[0-9]/.test(newPassword)) {
                    e.preventDefault();
                    alert('⚠️ Password must contain at least one number.');
                    document.getElementById('password').focus();
                    return false;
                }

                if (!/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
                    e.preventDefault();
                    alert('⚠️ Password must contain at least one special character (!@#$%^&*).');
                    document.getElementById('password').focus();
                    return false;
                }
            }
        });

        // ========== FORM VALIDATION - PROFILE UPDATE ==========
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phoneNumber').value.trim();

            if (!name) {
                e.preventDefault();
                alert('⚠️ Please enter your full name.');
                document.getElementById('name').focus();
                return false;
            }

            if (!username) {
                e.preventDefault();
                alert('⚠️ Please enter a username.');
                document.getElementById('username').focus();
                return false;
            }

            if (!email) {
                e.preventDefault();
                alert('⚠️ Please enter your email address.');
                document.getElementById('email').focus();
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('⚠️ Please enter a valid email address.');
                document.getElementById('email').focus();
                return false;
            }

            if (phone && phone.length > 0) {
                if (!/^09\d{9}$/.test(phone)) {
                    e.preventDefault();
                    alert('⚠️ Phone number must start with 09 and be exactly 11 digits.');
                    document.getElementById('phoneNumber').focus();
                    return false;
                }
            }
        });

        // ========== AUTO-HIDE SUCCESS MESSAGES ==========
        setTimeout(function() {
            const successMessages = document.querySelectorAll('main.main-content .success-message');
            successMessages.forEach(function(message) {
                message.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                message.style.opacity = '0';
                message.style.transform = 'translateY(-8px)';
                setTimeout(function() {
                    message.remove();
                }, 400);
            });
        }, 4000);
    </script>
</body>
</html>