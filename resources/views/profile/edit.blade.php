<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-black: #000000;
            --primary-yellow: #FFD700;
            --secondary-yellow: #FFA500;
            --light-bg: #FFFBF0;
            --card-bg: #FFFFFF;
            --text-dark: #1F2937;
            --text-medium: #6B7280;
            --text-light: #9CA3AF;
            --border-color: #E5E7EB;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 10px;
            border: 2px solid #f8f9fa;
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Glass morphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradients */
        .gradient-sunflower {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10B981, #059669);
        }
        
        .gradient-error {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
        }
        
        /* Button styling */
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
        }
        
        .btn-modern::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-modern:hover::after {
            left: 100%;
        }
        
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        /* Input styling */
        .input-modern {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }
        
        .input-modern:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
            outline: none;
            transform: translateY(-2px);
        }
        
        .input-modern:disabled {
            background: #f7fafc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Password input with icon */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6B7280;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-yellow);
        }
        
        /* Alert styling */
        .alert-modern {
            padding: 18px 22px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #FEF5E7, #FAE5D3);
            border-left: 4px solid #F39C12;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #D4EDDA, #C3E6CB);
            border-left: 4px solid #28A745;
        }

        .alert-error {
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            border-left: 4px solid #DC2626;
        }
        
        /* Main content spacing */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 300px);
        }
        
        /* Profile avatar */
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.3);
            position: relative;
        }
        
        .profile-avatar::before {
            content: '';
            position: absolute;
            inset: -5px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 50%;
            z-index: -1;
            opacity: 0.3;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.5; }
        }
        
        .profile-avatar i {
            font-size: 3rem;
            color: white;
        }
        
        /* Form sections */
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        /* Error messages */
        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        /* Save feedback */
        .save-feedback {
            color: #48bb78;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.3s ease-out;
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            padding: 12px 20px;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }

        /* Password requirements */
        .password-requirements {
            background: linear-gradient(135deg, #F0F9FF, #E0F2FE);
            border-left: 4px solid #3B82F6;
            padding: 16px;
            border-radius: 12px;
            margin-top: 12px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #1E40AF;
            margin: 6px 0;
        }

        .requirement-item i {
            font-size: 0.75rem;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <!-- Import Navigation -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
    <main class="main-content flex-grow">
        <div class="container mx-auto px-4 py-8 max-w-5xl">
            <!-- Page Header -->
            <div class="text-center mb-12 slide-up">
                <div class="profile-avatar mb-6">
                    <i class="fas fa-user"></i>
                </div>
                <h1 class="text-5xl font-bold mb-4 cursive-font">Profile Settings</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Manage your personal information and account preferences</p>
                <div class="w-24 h-1 gradient-sunflower mx-auto mt-6 rounded-full"></div>
            </div>

            <!-- Profile Information Card -->
            <div class="glass-card rounded-2xl p-8 mb-8 fade-in">
                <!-- Verification Alert -->
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert-modern alert-warning">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-yellow-800 mb-1">Email Verification Required</h4>
                        <p class="text-yellow-700 text-sm mb-3">Your email address is unverified. Please verify to access all features.</p>
                        <button form="send-verification" class="text-yellow-800 underline hover:no-underline font-semibold text-sm">
                            <i class="fas fa-paper-plane mr-2"></i>Resend Verification Email
                        </button>
                    </div>
                </div>
                @endif

                @if (session('status') === 'verification-link-sent')
                <div class="alert-modern alert-success">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-green-800 mb-1">Verification Email Sent</h4>
                        <p class="text-green-700 text-sm">A new verification link has been sent to your email address.</p>
                    </div>
                </div>
                @endif

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" id="profile-form">
                    @csrf
                    @method('patch')

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h2 class="section-title">Personal Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-yellow-500"></i>Full Name
                                </label>
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="input-modern"
                                    value="{{ old('name', $user->name) }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-at mr-2 text-yellow-500"></i>Username
                                </label>
                                <input 
                                    id="username" 
                                    name="username" 
                                    type="text" 
                                    class="input-modern"
                                    value="{{ old('username', $user->username) }}" 
                                    required 
                                    autocomplete="username"
                                    placeholder="Choose a username"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('username')" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-yellow-500"></i>Email Address
                                </label>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    class="input-modern"
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    autocomplete="email"
                                    placeholder="your.email@example.com"                                  
                                    readonly
                                />
                                <x-input-error class="error-message" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-yellow-500"></i>Phone Number
                                </label>
                                <input 
                                    id="phoneNumber" 
                                    name="phoneNumber" 
                                    type="tel" 
                                    class="input-modern"
                                    value="{{ old('phoneNumber', $user->phoneNumber) }}" 
                                    placeholder="+63 912 345 6789"
                                    autocomplete="tel"
                                />
                                <x-input-error class="error-message" :messages="$errors->get('phoneNumber')" />
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" class="btn-modern gradient-sunflower text-black w-full sm:w-auto">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                        <div class="save-feedback">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span>Profile updated successfully!</span>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Password Update Card -->
            <div class="glass-card rounded-2xl p-8 mb-8 fade-in">
                <form method="post" action="{{ route('password.update') }}" id="password-form">
                    @csrf
                    @method('put')

                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h2 class="section-title">Update Password</h2>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-xl mb-6 border-l-4 border-blue-500">
                            <div class="flex items-start">
                                <i class="fas fa-shield-alt text-blue-600 text-xl mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-blue-800 mb-1">Password Security</h4>
                                    <p class="text-blue-700 text-sm">Ensure your account is using a long, random password to stay secure.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-key mr-2 text-yellow-500"></i>Current Password
                                </label>
                                <div class="password-wrapper">
                                    <input 
                                        id="current_password" 
                                        name="current_password" 
                                        type="password" 
                                        class="input-modern"
                                        autocomplete="current-password"
                                        placeholder="Enter current password"
                                    />
                                    <i class="far fa-eye password-toggle" id="toggleCurrentPassword"></i>
                                </div>
                                <x-input-error class="error-message" :messages="$errors->updatePassword->get('current_password')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- New Password -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-yellow-500"></i>New Password
                                    </label>
                                    <div class="password-wrapper">
                                        <input 
                                            id="password" 
                                            name="password" 
                                            type="password" 
                                            class="input-modern"
                                            autocomplete="new-password"
                                            placeholder="Enter new password"
                                        />
                                        <i class="far fa-eye password-toggle" id="togglePassword"></i>
                                    </div>
                                    <x-input-error class="error-message" :messages="$errors->updatePassword->get('password')" />
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-check-circle mr-2 text-yellow-500"></i>Confirm Password
                                    </label>
                                    <div class="password-wrapper">
                                        <input 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            type="password" 
                                            class="input-modern"
                                            autocomplete="new-password"
                                            placeholder="Confirm new password"
                                        />
                                        <i class="far fa-eye password-toggle" id="togglePasswordConfirmation"></i>
                                    </div>
                                    <x-input-error class="error-message" :messages="$errors->updatePassword->get('password_confirmation')" />
                                </div>
                            </div>

                            <!-- Password Requirements Info -->
                            <div class="password-requirements">
                                <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i>
                                    Password Requirements
                                </h4>
                                <div class="space-y-2">
                                    <div class="requirement-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>At least 8 characters long</span>
                                    </div>
                                    <div class="requirement-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Contains both uppercase and lowercase letters</span>
                                    </div>
                                    <div class="requirement-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Contains at least one number</span>
                                    </div>
                                    <div class="requirement-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Contains at least one special character (!@#$%^&*)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" class="btn-modern gradient-success text-white w-full sm:w-auto">
                            <i class="fas fa-sync-alt"></i>
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                        <div class="save-feedback">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span>Password updated successfully!</span>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Delete Account Card (Commented Out)
            <div class="glass-card rounded-2xl p-8 fade-in border-l-4 border-red-500">
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon" style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h2 class="section-title text-red-600">Danger Zone</h2>
                    </div>

                    <div class="alert-modern" style="background: linear-gradient(135deg, #FEE2E2, #FECACA); border-left-color: #DC2626;">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-2xl text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-800 mb-1">Permanent Account Deletion</h4>
                            <p class="text-red-700 text-sm">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you absolutely sure you want to permanently delete your account?\n\nAll your data, bookings, and information will be lost forever.\n\nClick OK only if you are certain.');">
                        @csrf
                        @method('delete')

                        <button type="submit" class="btn-modern gradient-error text-white">
                            <i class="fas fa-trash-alt"></i>
                            Delete Account Permanently
                        </button>
                    </form>
                </div>
            </div> -->
        </div>
    </main>

    <!-- Import Footer -->
    @include('customerFolder.partials.footer')

    <script>
        // Password visibility toggle functionality
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
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

        // Initialize password toggles
        togglePasswordVisibility('current_password', 'toggleCurrentPassword');
        togglePasswordVisibility('password', 'togglePassword');
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation');

        // Form validation for password update
        document.getElementById('password-form').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            // Check if trying to update password
            if (newPassword || confirmPassword) {
                // Validate current password is provided
                if (!currentPassword) {
                    e.preventDefault();
                    alert('⚠️ Please enter your current password to update your password.');
                    document.getElementById('current_password').focus();
                    return false;
                }

                // Validate new password is provided
                if (!newPassword) {
                    e.preventDefault();
                    alert('⚠️ Please enter a new password.');
                    document.getElementById('password').focus();
                    return false;
                }

                // Validate password confirmation
                if (!confirmPassword) {
                    e.preventDefault();
                    alert('⚠️ Please confirm your new password.');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }

                // Check if passwords match
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('⚠️ New password and confirmation password do not match!');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }

                // Validate password strength
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

        // Form validation for profile update
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();

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

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('⚠️ Please enter a valid email address.');
                document.getElementById('email').focus();
                return false;
            }
        });

        // Auto-hide success messages after 5 seconds
        setTimeout(function() {
            const successMessages = document.querySelectorAll('.save-feedback');
            successMessages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>