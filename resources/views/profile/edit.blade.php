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
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F0F2F5;
            color: #050505;
            overflow-x: hidden;
        }
        
        .cursive-font { font-family: 'Dancing Script', cursive; }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F3F4F6; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow)); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(to bottom, var(--secondary-yellow), var(--yellow-dark)); }
        
        main.main-content {
            margin-top: 100px;
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
        
        main.main-content .settings-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0;
            display: flex;
            gap: 0;
            min-height: calc(100vh - 400px);
        }
        
        main.main-content .settings-sidebar {
            width: 380px;
            border-right: 1px solid #E4E6EB;
            padding: 32px 20px;
            position: sticky;
            top: 100px;
            height: fit-content;
            background-color: #FFFFFF;
            border-radius: 12px;
            margin-right: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        main.main-content .sidebar-nav { list-style: none; }
        main.main-content .nav-item { margin: 0; }
        
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
        
        main.main-content .settings-sidebar .nav-link.active::before { display: none; }
        
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
        
        main.main-content .settings-sidebar .nav-icon i { color: #000000 !important; }
        main.main-content .settings-sidebar .nav-link:hover .nav-icon { background: #E4E6EB; transform: scale(1.05); }
        main.main-content .settings-sidebar .nav-link.active .nav-icon { background: rgba(0, 0, 0, 0.15); }
        main.main-content .settings-sidebar .nav-text { flex: 1; font-weight: 500; color: #000000 !important; }
        main.main-content .settings-sidebar .nav-link.active .nav-text { font-weight: 600; }
        
        main.main-content .nav-badge {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 700;
        }
        
        main.main-content .header-content {
            padding: 0 0 24px 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #E4E6EB;
            max-width: 100%;
        }
        
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
        
        main.main-content .content-section.active { display: block; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        
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
        
        main.main-content .section-info { flex: 1; }
        
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
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        main.main-content .alert-warning { background: #FFF3CD; border-color: #FFE69C; }
        main.main-content .alert-success { background: #D1E7DD; border-color: #BADBCC; }
        main.main-content .alert-error   { background: #F8D7DA; border-color: #F5C2C7; }
        main.main-content .alert-info    { background: #D1ECF1; border-color: #BEE5EB; }
        main.main-content .alert-icon    { font-size: 1.25rem; flex-shrink: 0; }
        main.main-content .alert-content h4 { font-weight: 600; margin-bottom: 4px; font-size: 0.9375rem; }
        main.main-content .alert-content p  { font-size: 0.9375rem; line-height: 1.5; font-weight: 400; }
        
        main.main-content .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }
        
        main.main-content .form-group { display: flex; flex-direction: column; }
        
        main.main-content .form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 0.9375rem;
            color: #050505;
            margin-bottom: 8px;
        }
        
        main.main-content .form-label i { display: none; }
        
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

        /* ── Validation states ── */
        main.main-content .form-input.is-valid {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15) !important;
        }

        main.main-content .form-input.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }

        /* ── Field messages ── */
        .field-msg {
            display: none;
            font-size: 0.72rem;
            margin-top: 5px;
            align-items: center;
            gap: 4px;
            animation: slideDown 0.25s ease;
        }

        .field-msg.show    { display: flex; }
        .field-msg.error   { color: #ef4444; }
        .field-msg.success { color: #22c55e; }

        /* ── Password strength bar ── */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            transition: all 0.4s ease;
            width: 0%;
        }
        
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
        
        main.main-content .requirements-list { display: grid; gap: 10px; }
        
        main.main-content .requirement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #65676B;
            font-size: 0.9375rem;
            font-weight: 400;
            transition: all 0.3s ease;
        }
        
        main.main-content .requirement-item i {
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        main.main-content .requirement-item.met {
            color: #22c55e;
        }

        main.main-content .requirement-item.met i {
            color: #22c55e;
        }
        
        main.main-content .btn {
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
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
        
        main.main-content .btn::before { display: none; }
        main.main-content .btn i, main.main-content .btn span { position: relative; z-index: 1; }
        main.main-content .btn:hover { transform: translateY(-2px); filter: brightness(0.95); }
        main.main-content .btn:active { transform: translateY(0) scale(0.98); }
        
        main.main-content .btn-primary {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: #000000;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.2);
        }
        
        main.main-content .btn-primary:hover { box-shadow: 0 4px 16px rgba(255, 215, 0, 0.35); }
        
        main.main-content .btn-success {
            background: #42B72A;
            color: white;
            box-shadow: 0 2px 8px rgba(66, 183, 42, 0.2);
        }
        
        main.main-content .btn-success:hover { background: #36A420; box-shadow: 0 4px 16px rgba(66, 183, 42, 0.35); }
        
        main.main-content .btn-danger {
            background: #E4E6EB;
            color: #050505;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }
        
        main.main-content .btn-danger:hover { background: #D8DADF; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); }
        
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
        
        main.main-content .error-message {
            color: #DC3545;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        main.main-content .error-message i { font-size: 0.75rem; }
        
        main.main-content .form-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #E4E6EB;
        }
        
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
        
        main.main-content .form-group-full { grid-column: 1 / -1; }
        
        @media (max-width: 1024px) {
            main.main-content { margin-top: 80px; padding: 0 16px; }
            main.main-content .settings-container { flex-direction: column; }
            main.main-content .settings-sidebar {
                width: 100%; position: static; border-right: none; border-bottom: none;
                padding: 0; background: transparent; margin-right: 0; margin-bottom: 20px;
                border-radius: 0; box-shadow: none;
            }
            main.main-content .header-content {
                padding: 32px 32px 24px 32px; margin-bottom: 0; border-bottom: none;
                background: #FFFFFF; border-radius: 12px 12px 0 0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }
            main.main-content .sidebar-nav {
                display: grid; grid-template-columns: 1fr; gap: 0; padding: 0;
                background: #FFFFFF; border-radius: 0 0 12px 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); margin-bottom: 20px;
            }
            main.main-content .nav-item { flex-shrink: 1; }
            main.main-content .settings-sidebar .nav-link {
                padding: 16px 20px; border: none; border-bottom: 1.5px solid #E4E6EB;
                border-radius: 0; white-space: nowrap; margin: 0;
                background: #FFFFFF !important; color: #000000 !important;
                transform: none !important; -webkit-tap-highlight-color: transparent !important; outline: none !important;
            }
            main.main-content .settings-sidebar .nav-link:hover { background: #F7F8FA !important; transform: none !important; }
            main.main-content .settings-sidebar .nav-link.active {
                background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow)) !important;
                border-color: transparent; transform: none !important;
            }
            main.main-content .settings-sidebar .nav-icon { background: #F0F2F5 !important; }
            main.main-content .settings-sidebar .nav-link:hover .nav-icon { background: #E4E6EB !important; transform: none !important; }
            main.main-content .settings-sidebar .nav-link.active .nav-icon { background: rgba(0, 0, 0, 0.15) !important; }
            main.main-content .nav-item:last-child .nav-link { border-bottom: none; border-radius: 0 0 12px 12px; }
            main.main-content .settings-content { padding: 0 0 20px; }
            main.main-content .content-section { padding: 32px; border-radius: 12px; }
            main.main-content .page-title { font-size: 1.625rem; padding-top: 0; }
        }
        
        @media (max-width: 640px) {
            main.main-content { margin-top: 70px; padding: 0 12px; }
            main.main-content .form-grid { grid-template-columns: 1fr; gap: 20px; }
            main.main-content .page-title { font-size: 1.5rem; }
            main.main-content .page-subtitle { font-size: 0.875rem; margin-bottom: 20px; }
            main.main-content .section-title { font-size: 1.375rem; }
            main.main-content .section-icon { width: 52px; height: 52px; font-size: 1.375rem; }
            main.main-content .settings-sidebar .nav-text { font-size: 0.9375rem; font-weight: 500; }
            main.main-content .settings-sidebar .nav-link { padding: 14px 16px; }
            main.main-content .form-actions { flex-direction: column; align-items: stretch; gap: 12px; }
            main.main-content .btn { width: 100%; }
            main.main-content .success-message { margin-left: 0; margin-top: 12px; width: 100%; justify-content: center; }
            main.main-content .settings-content { padding: 0 0 20px; }
            main.main-content .content-section { padding: 20px; }
            main.main-content .section-header { gap: 14px; margin-bottom: 24px; padding-bottom: 20px; }
            main.main-content .form-input, main.main-content .password-wrapper { height: 48px; }
            main.main-content .form-input { line-height: 48px; font-size: 1rem; }
            main.main-content .header-content { padding: 24px 20px 20px 20px; }
        }
    </style>
</head>

<body>
    @include('customerFolder.partials.navbar')
    
    <main class="main-content">
        <div class="settings-container">
            <!-- Sidebar -->
            <aside class="settings-sidebar">
                <div class="header-content">
                    <h1 class="page-title">Profile Settings</h1>
                    <p class="page-subtitle">Manage your account information and preferences</p>
                </div>
                <nav>
                    <ul class="sidebar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-section="personal-info">
                                <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                                <span class="nav-text">Personal Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-section="password">
                                <span class="nav-icon"><i class="fas fa-lock"></i></span>
                                <span class="nav-text">Password & Security</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Content Area -->
            <div class="settings-content">

                <!-- ══════════════════════════════════════════
                     PERSONAL INFORMATION
                ══════════════════════════════════════════ -->
                <section id="personal-info" class="content-section active">
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

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-user-circle"></i></span>
                        <div class="section-info">
                            <h2 class="section-title">Personal Information</h2>
                            <p class="section-description">Update your personal details and contact information</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" id="profile-form">
                        @csrf
                        @method('patch')

                        <div class="form-grid">
                            <!-- Full Name -->
                            <div class="form-group">
                                <label class="form-label"><span>Full Name</span></label>
                                <div class="relative" style="position:relative;">
                                    <input id="p-name" name="name" type="text" class="form-input"
                                        value="{{ old('name', $user->name) }}"
                                        required autofocus autocomplete="name"
                                        placeholder="Enter your full name" />
                                    <span id="p-name-icon" class="hidden" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;font-size:0.85rem;"></span>
                                </div>
                                <p id="p-name-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="p-name-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->get('name')" />
                            </div>

                            <!-- Username -->
                            <div class="form-group">
                                <label class="form-label"><span>Username</span></label>
                                <div style="position:relative;">
                                    <input id="p-username" name="username" type="text" class="form-input"
                                        value="{{ old('username', $user->username) }}"
                                        required autocomplete="username"
                                        placeholder="Choose a username" />
                                    <span id="p-username-icon" class="hidden" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;font-size:0.85rem;"></span>
                                </div>
                                <p id="p-username-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="p-username-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->get('username')" />
                            </div>

                            <!-- Email (readonly) -->
                            <div class="form-group">
                                <label class="form-label"><span>Email Address</span></label>
                                <input id="p-email" name="email" type="email" class="form-input"
                                    value="{{ old('email', $user->email) }}"
                                    required autocomplete="email"
                                    placeholder="your.email@example1.com"/>
                                <x-input-error class="error-message" :messages="$errors->get('email')" />
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label class="form-label"><span>Phone Number</span></label>
                                <div style="position:relative;">
                                    <input id="p-phone" name="phoneNumber" type="tel" class="form-input"
                                        value="{{ old('phoneNumber', $user->phoneNumber) }}"
                                        placeholder="09XXXXXXXXX"
                                        autocomplete="tel" maxlength="11" />
                                    <span id="p-phone-icon" class="hidden" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;font-size:0.85rem;"></span>
                                </div>
                                <p id="p-phone-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="p-phone-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->get('phoneNumber')" />
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i><span>Save Changes</span>
                            </button>
                            @if (session('status') === 'profile-updated')
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i><span>Profile updated successfully!</span>
                            </div>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- ══════════════════════════════════════════
                     PASSWORD & SECURITY
                ══════════════════════════════════════════ -->
                <section id="password" class="content-section">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-lock"></i></span>
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
                            <!-- Current Password -->
                            <div class="form-group form-group-full">
                                <label class="form-label"><span>Current Password</span></label>
                                <div class="password-wrapper">
                                    <input id="current_password" name="current_password" type="password"
                                        class="form-input" autocomplete="current-password"
                                        placeholder="Enter your current password" />
                                    <i class="far fa-eye password-toggle" id="toggleCurrentPassword"></i>
                                </div>
                                <p id="cur-pw-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="cur-pw-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('current_password')" />
                            </div>

                            <!-- New Password -->
                            <div class="form-group">
                                <label class="form-label"><span>New Password</span></label>
                                <div class="password-wrapper">
                                    <input id="new_password" name="password" type="password"
                                        class="form-input" autocomplete="new-password"
                                        placeholder="Enter new password" />
                                    <i class="far fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                                <!-- Strength bar -->
                                <div style="width:100%;background:#E4E6EB;border-radius:4px;margin-top:6px;overflow:hidden;height:4px;">
                                    <div id="pw-strength-bar" class="strength-bar"></div>
                                </div>
                                <p id="new-pw-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="new-pw-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('password')" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label class="form-label"><span>Confirm Password</span></label>
                                <div class="password-wrapper">
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        class="form-input" autocomplete="new-password"
                                        placeholder="Confirm new password" />
                                    <i class="far fa-eye password-toggle" id="togglePasswordConfirmation"></i>
                                </div>
                                <p id="conf-pw-msg" class="field-msg"><i class="fas fa-circle-info" style="font-size:0.7rem;"></i><span id="conf-pw-msg-text"></span></p>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('password_confirmation')" />
                            </div>
                        </div>

                        <!-- Requirements checklist -->
                        <div class="requirements-box">
                            <h4 class="requirements-title">
                                <i class="fas fa-info-circle"></i> Password Requirements
                            </h4>
                            <div class="requirements-list">
                                <div class="requirement-item" id="req-length">
                                    <i class="fas fa-check-circle"></i><span>At least 8 characters long</span>
                                </div>
                                <div class="requirement-item" id="req-case">
                                    <i class="fas fa-check-circle"></i><span>Contains uppercase and lowercase letters</span>
                                </div>
                                <div class="requirement-item" id="req-number">
                                    <i class="fas fa-check-circle"></i><span>Contains at least one number (0-9)</span>
                                </div>
                                <div class="requirement-item" id="req-special">
                                    <i class="fas fa-check-circle"></i><span>Contains at least one special character (!@#$%^&*)</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i><span>Update Password</span>
                            </button>
                            @if (session('status') === 'password-updated')
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i><span>Password updated successfully!</span>
                            </div>
                            @endif
                        </div>
                    </form>
                </section>

            </div>
        </div>
    </main>

    @include('customerFolder.partials.footer')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =====================================================
           TAB NAVIGATION
        ===================================================== */
        const navLinks = document.querySelectorAll('main.main-content .settings-sidebar .nav-link');
        const sections = document.querySelectorAll('main.main-content .content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                sections.forEach(s => s.classList.remove('active'));
                document.getElementById(this.getAttribute('data-section')).classList.add('active');
            });
        });

        /* =====================================================
           GENERIC HELPERS
        ===================================================== */
        function setValid(input, iconEl, msgEl, msgText, msgTxEl) {
            input.classList.remove('is-invalid'); input.classList.add('is-valid');
            if (iconEl) { iconEl.innerHTML = '<i class="fas fa-check-circle" style="color:#22c55e;"></i>'; iconEl.classList.remove('hidden'); }
            if (msgEl && msgTxEl) {
                msgTxEl.textContent = msgText || '';
                msgEl.className = 'field-msg show success';
                msgEl.querySelector('i').className = 'fas fa-check-circle';
                msgEl.querySelector('i').style.fontSize = '0.7rem';
            }
        }

        function setInvalid(input, iconEl, msgEl, msgText, msgTxEl) {
            input.classList.remove('is-valid'); input.classList.add('is-invalid');
            if (iconEl) { iconEl.innerHTML = '<i class="fas fa-times-circle" style="color:#ef4444;"></i>'; iconEl.classList.remove('hidden'); }
            if (msgEl && msgTxEl) {
                msgTxEl.textContent = msgText || '';
                msgEl.className = 'field-msg show error';
                msgEl.querySelector('i').className = 'fas fa-exclamation-circle';
                msgEl.querySelector('i').style.fontSize = '0.7rem';
            }
        }

        function clearState(input, iconEl, msgEl) {
            input.classList.remove('is-valid', 'is-invalid');
            if (iconEl) iconEl.classList.add('hidden');
            if (msgEl) msgEl.className = 'field-msg';
        }

        /* =====================================================
           PERSONAL INFO - FULL NAME
        ===================================================== */
        const pName    = document.getElementById('p-name');
        const pNameIc  = document.getElementById('p-name-icon');
        const pNameMsg = document.getElementById('p-name-msg');
        const pNameTx  = document.getElementById('p-name-msg-text');

        pName.addEventListener('input', function () {
            const v = this.value.trim();
            if (!v) return clearState(this, pNameIc, pNameMsg);
            if (v.length < 2) return setInvalid(this, pNameIc, pNameMsg, 'Name must be at least 2 characters.', pNameTx);
            if (!/^[a-zA-Z\s\-'.]+$/.test(v)) return setInvalid(this, pNameIc, pNameMsg, 'Only letters, spaces, hyphens, and apostrophes.', pNameTx);
            setValid(this, pNameIc, pNameMsg, 'Looks good!', pNameTx);
        });
        pName.addEventListener('blur', function () {
            if (!this.value.trim()) setInvalid(this, pNameIc, pNameMsg, 'Full name is required.', pNameTx);
        });

        /* =====================================================
           PERSONAL INFO - USERNAME
        ===================================================== */
        const pUser    = document.getElementById('p-username');
        const pUserIc  = document.getElementById('p-username-icon');
        const pUserMsg = document.getElementById('p-username-msg');
        const pUserTx  = document.getElementById('p-username-msg-text');

        pUser.addEventListener('input', function () {
            const v = this.value.trim();
            if (!v) return clearState(this, pUserIc, pUserMsg);
            if (v.length < 3)  return setInvalid(this, pUserIc, pUserMsg, 'Username must be at least 3 characters.', pUserTx);
            if (!/^[a-zA-Z0-9_]+$/.test(v)) return setInvalid(this, pUserIc, pUserMsg, 'Only letters, numbers, and underscores allowed.', pUserTx);
            if (v.length > 25) return setInvalid(this, pUserIc, pUserMsg, 'Username must not exceed 25 characters.', pUserTx);
            setValid(this, pUserIc, pUserMsg, 'Username looks good!', pUserTx);
        });
        pUser.addEventListener('blur', function () {
            if (!this.value.trim()) setInvalid(this, pUserIc, pUserMsg, 'Username is required.', pUserTx);
        });

        /* =====================================================
           PERSONAL INFO - PHONE NUMBER
        ===================================================== */
        const pPhone    = document.getElementById('p-phone');
        const pPhoneIc  = document.getElementById('p-phone-icon');
        const pPhoneMsg = document.getElementById('p-phone-msg');
        const pPhoneTx  = document.getElementById('p-phone-msg-text');

        pPhone.addEventListener('input', function () {
            // Allow digits only
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
            const v = this.value;

            if (!v) return clearState(this, pPhoneIc, pPhoneMsg);
            if (!v.startsWith('09')) return setInvalid(this, pPhoneIc, pPhoneMsg, 'Phone number must start with 09.', pPhoneTx);
            if (v.length < 11) return setInvalid(this, pPhoneIc, pPhoneMsg, `${11 - v.length} more digit(s) needed.`, pPhoneTx);
            setValid(this, pPhoneIc, pPhoneMsg, 'Valid phone number.', pPhoneTx);
        });
        pPhone.addEventListener('blur', function () {
            const v = this.value;
            if (v && !/^09\d{9}$/.test(v)) setInvalid(this, pPhoneIc, pPhoneMsg, 'Must be 09XXXXXXXXX (11 digits).', pPhoneTx);
        });

        /* =====================================================
           PASSWORD - CURRENT PASSWORD
        ===================================================== */
        const curPw    = document.getElementById('current_password');
        const curMsg   = document.getElementById('cur-pw-msg');
        const curMsgTx = document.getElementById('cur-pw-msg-text');
        const dummyIc  = { classList: { add: () => {}, remove: () => {} }, innerHTML: '' };

        curPw.addEventListener('input', function () {
            if (!this.value) return clearState(this, dummyIc, curMsg);
            if (this.value.length < 1) return setInvalid(this, dummyIc, curMsg, 'Current password is required.', curMsgTx);
            setValid(this, dummyIc, curMsg, '', curMsgTx);
            curMsg.className = 'field-msg'; // silent success — no green text for current pw
            this.classList.remove('is-invalid'); this.classList.add('is-valid');
        });
        curPw.addEventListener('blur', function () {
            if (!this.value) setInvalid(this, dummyIc, curMsg, 'Current password is required.', curMsgTx);
        });

        /* =====================================================
           PASSWORD - NEW PASSWORD + STRENGTH
        ===================================================== */
        const newPw      = document.getElementById('new_password');
        const newMsg     = document.getElementById('new-pw-msg');
        const newMsgTx   = document.getElementById('new-pw-msg-text');
        const strengthBar = document.getElementById('pw-strength-bar');

        const levels = [
            { color: '#ef4444', width: '15%'  },
            { color: '#f97316', width: '30%'  },
            { color: '#eab308', width: '55%'  },
            { color: '#84cc16', width: '75%'  },
            { color: '#22c55e', width: '100%' },
        ];

        function getScore(v) {
            if (v.length < 6) return 0;
            let s = 1;
            if (v.length >= 8)          s++;
            if (/[A-Z]/.test(v))        s++;
            if (/[0-9]/.test(v))        s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            return Math.min(s, 4);
        }

        function updateReqs(v) {
            const map = {
                'req-length':  v.length >= 8,
                'req-case':    /[A-Z]/.test(v) && /[a-z]/.test(v),
                'req-number':  /[0-9]/.test(v),
                'req-special': /[!@#$%^&*(),.?":{}|<>]/.test(v),
            };
            Object.entries(map).forEach(([id, met]) => {
                const el = document.getElementById(id);
                if (met) { el.classList.add('met'); }
                else     { el.classList.remove('met'); }
            });
        }

        newPw.addEventListener('input', function () {
            const v = this.value;
            updateReqs(v);

            if (!v) {
                clearState(this, dummyIc, newMsg);
                strengthBar.style.width = '0%';
                return;
            }

            const lvl = levels[getScore(v)];
            strengthBar.style.width      = lvl.width;
            strengthBar.style.background = lvl.color;

            const errors = [];
            if (v.length < 8)                  errors.push('at least 8 characters');
            if (!/[A-Z]/.test(v))              errors.push('one uppercase letter');
            if (!/[a-z]/.test(v))              errors.push('one lowercase letter');
            if (!/[0-9]/.test(v))              errors.push('one number');
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(v)) errors.push('one special character');

            if (errors.length) {
                setInvalid(this, dummyIc, newMsg, 'Needs: ' + errors.join(', ') + '.', newMsgTx);
            } else {
                setValid(this, dummyIc, newMsg, 'Password meets all requirements!', newMsgTx);
            }

            // Re-validate confirm
            if (confPw.value) validateConfirm();
        });

        newPw.addEventListener('blur', function () {
            if (!this.value) setInvalid(this, dummyIc, newMsg, 'New password is required.', newMsgTx);
        });

        /* =====================================================
           PASSWORD - CONFIRM PASSWORD
        ===================================================== */
        const confPw    = document.getElementById('password_confirmation');
        const confMsg   = document.getElementById('conf-pw-msg');
        const confMsgTx = document.getElementById('conf-pw-msg-text');

        function validateConfirm() {
            const v = confPw.value;
            if (!v) return clearState(confPw, dummyIc, confMsg);
            if (v !== newPw.value) {
                setInvalid(confPw, dummyIc, confMsg, 'Passwords do not match.', confMsgTx);
                return false;
            }
            setValid(confPw, dummyIc, confMsg, 'Passwords match!', confMsgTx);
            return true;
        }

        confPw.addEventListener('input', validateConfirm);
        confPw.addEventListener('blur', function () {
            if (!this.value) setInvalid(this, dummyIc, confMsg, 'Please confirm your new password.', confMsgTx);
        });

        /* =====================================================
           PASSWORD VISIBILITY TOGGLES
        ===================================================== */
        function makeToggle(inputId, toggleId) {
            const input  = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            if (!input || !toggle) return;
            toggle.addEventListener('click', function () {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    toggle.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        }

        makeToggle('current_password',      'toggleCurrentPassword');
        makeToggle('new_password',           'togglePassword');
        makeToggle('password_confirmation',  'togglePasswordConfirmation');

        /* =====================================================
           PROFILE FORM SUBMIT
        ===================================================== */
        document.getElementById('profile-form').addEventListener('submit', function (e) {
            pName.dispatchEvent(new Event('blur'));
            pUser.dispatchEvent(new Event('blur'));
            if (pPhone.value) pPhone.dispatchEvent(new Event('blur'));

            if (this.querySelectorAll('.is-invalid').length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning', title: 'Check your inputs',
                    text: 'Please fix the highlighted fields before saving.',
                    confirmButtonText: 'OK', confirmButtonColor: '#000000',
                });
            }
        });

        /* =====================================================
           PASSWORD FORM SUBMIT
        ===================================================== */
        document.getElementById('password-form').addEventListener('submit', function (e) {
            curPw.dispatchEvent(new Event('blur'));
            newPw.dispatchEvent(new Event('blur'));
            confPw.dispatchEvent(new Event('blur'));

            if (this.querySelectorAll('.is-invalid').length > 0 ||
                !curPw.value || !newPw.value || !confPw.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning', title: 'Check your inputs',
                    text: 'Please fix the highlighted fields before submitting.',
                    confirmButtonText: 'OK', confirmButtonColor: '#000000',
                });
            }
        });

        /* =====================================================
           AUTO-HIDE SUCCESS MESSAGES
        ===================================================== */
        setTimeout(function () {
            document.querySelectorAll('main.main-content .success-message').forEach(function (msg) {
                msg.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-8px)';
                setTimeout(() => msg.remove(), 400);
            });
        }, 4000);
    });
    </script>
</body>
</html>