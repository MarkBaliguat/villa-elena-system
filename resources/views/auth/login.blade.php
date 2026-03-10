<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #ffffff 0%, #fffdf5 60%, rgb(255, 249, 230) 100%);
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        @media (max-width: 768px) {
            .mobile-image { height: 200px; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-container { animation: fadeIn 0.6s ease-out; }

        .animate-form > * { animation: fadeInUp 0.5s ease-out backwards; }
        .animate-form > *:nth-child(1) { animation-delay: 0.1s; }
        .animate-form > *:nth-child(2) { animation-delay: 0.2s; }
        .animate-form > *:nth-child(3) { animation-delay: 0.3s; }
        .animate-form > *:nth-child(4) { animation-delay: 0.4s; }
        .animate-form > *:nth-child(5) { animation-delay: 0.5s; }
        .animate-form > *:nth-child(6) { animation-delay: 0.6s; }

        /* ── Input base ── */
        .input-enhanced {
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-enhanced:focus {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* ── Validation states ── */
        .input-enhanced.is-valid {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }

        .input-enhanced.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        /* ── Field messages ── */
        .field-msg {
            display: none;
            font-size: 0.72rem;
            margin-top: 4px;
            align-items: center;
            gap: 4px;
            animation: slideDown 0.25s ease;
        }

        .field-msg.show    { display: flex; }
        .field-msg.error   { color: #ef4444; }
        .field-msg.success { color: #22c55e; }

        /* ── Button ── */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:active:not(:disabled) { transform: translateY(0); }
        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

        .image-overlay { animation: fadeIn 1s ease-out; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-container">
        
        <!-- LEFT SIDE IMAGE (desktop) -->
        <div class="hidden md:block relative bg-cover bg-center"
             style="background-image: url('/images/contact.jpg');">
            <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6 image-overlay">
                <p class="text-white text-sm mb-1 opacity-90">Welcome to</p>
                <h1 class="text-5xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                <p class="text-white font-semibold text-sm opacity-90">Family Resort & Agri-Tourism Farm</p>
            </div>
        </div>

        <!-- MOBILE IMAGE -->
        <div class="md:hidden mobile-image relative bg-cover bg-center"
             style="background-image: url('/images/contact.jpg');">
            <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                <p class="text-white text-sm mb-1">Welcome to</p>
                <h1 class="text-3xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                <p class="text-white font-semibold text-xs">Family Resort & Agri-Tourism Farm</p>
            </div>
        </div>

        <!-- RIGHT SIDE LOGIN FORM -->
        <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12 bg-white">
            <div class="animate-form">
                <h2 class="text-2xl font-bold mb-6 md:mb-8 text-center text-gray-800">Log back in</h2>

                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    <!-- ── Email ── -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <div class="relative">
                            <input 
                                id="email" 
                                type="email" 
                                name="email"
                                placeholder="juandelacruz@gmail.com"
                                value="{{ old('email') }}"
                                required autofocus
                                autocomplete="off"
                                class="w-full mt-1 px-4 py-3 pr-11 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('email') is-invalid @enderror"
                            />
                            <span id="email-icon" class="absolute right-4 top-1/2 -translate-y-1/2 mt-0.5 hidden text-sm pointer-events-none"></span>
                        </div>
                        <p id="email-msg" class="field-msg">
                            <i class="fas fa-circle-info text-xs"></i>
                            <span id="email-msg-text"></span>
                        </p>
                        @error('email')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Password ── -->
                    <div class="mb-2">
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('password') is-invalid @enderror"
                            />
                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 mt-0.5 text-gray-400 hover:text-gray-600 transition">
                                <i class="far fa-eye text-lg"></i>
                            </button>
                        </div>
                        <p id="password-msg" class="field-msg">
                            <i class="fas fa-circle-info text-xs"></i>
                            <span id="password-msg-text"></span>
                        </p>
                        @error('password')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex justify-between items-center mb-6 mt-3">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember"
                                class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded cursor-pointer"
                            />
                            <label for="remember_me" class="ml-2 text-sm text-gray-600 cursor-pointer">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-black transition font-medium">Forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit"
                        id="loginButton"
                        class="w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                        <span id="buttonText">Log In</span>
                        <span id="buttonLoader" class="hidden">
                            <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>

                    <!-- Sign Up Link -->
                    <p class="text-center text-sm mt-6 text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-black font-semibold hover:underline transition">Create an account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =========================================================
           HELPERS
        ========================================================= */
        function setValid(input, iconEl, msgEl, msgText, msgTextEl) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            iconEl.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
            iconEl.classList.remove('hidden');
            msgTextEl.textContent = msgText || '';
            msgEl.className = 'field-msg show success';
            msgEl.querySelector('i').className = 'fas fa-check-circle text-xs';
        }

        function setInvalid(input, iconEl, msgEl, msgText, msgTextEl) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            iconEl.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
            iconEl.classList.remove('hidden');
            msgTextEl.textContent = msgText || '';
            msgEl.className = 'field-msg show error';
            msgEl.querySelector('i').className = 'fas fa-exclamation-circle text-xs';
        }

        function clearState(input, iconEl, msgEl) {
            input.classList.remove('is-valid', 'is-invalid');
            iconEl.classList.add('hidden');
            msgEl.className = 'field-msg';
        }

        /* =========================================================
           EMAIL
        ========================================================= */
        const emailInput   = document.getElementById('email');
        const emailIcon    = document.getElementById('email-icon');
        const emailMsg     = document.getElementById('email-msg');
        const emailMsgText = document.getElementById('email-msg-text');
        const emailRegex   = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        emailInput.addEventListener('input', function () {
            const v = this.value.trim();
            if (v === '') {
                clearState(this, emailIcon, emailMsg);
            } else if (!emailRegex.test(v)) {
                setInvalid(this, emailIcon, emailMsg, 'Please enter a valid email address.', emailMsgText);
            } else {
                setValid(this, emailIcon, emailMsg, 'Valid email address.', emailMsgText);
            }
        });

        emailInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                setInvalid(this, emailIcon, emailMsg, 'Email address is required.', emailMsgText);
            }
        });

        /* =========================================================
           PASSWORD
        ========================================================= */
        const passwordInput   = document.getElementById('password');
        const passwordMsg     = document.getElementById('password-msg');
        const passwordMsgText = document.getElementById('password-msg-text');
        // Password field has a toggle button in the right slot, so we use a dummy icon object
        const dummyIcon = { classList: { add: () => {}, remove: () => {} }, innerHTML: '' };

        passwordInput.addEventListener('input', function () {
            const v = this.value;
            if (v === '') {
                clearState(this, dummyIcon, passwordMsg);
            } else if (v.length < 8) {
                setInvalid(this, dummyIcon, passwordMsg, 'Password must be at least 8 characters.', passwordMsgText);
            } else {
                setValid(this, dummyIcon, passwordMsg, 'Password looks good.', passwordMsgText);
            }
        });

        passwordInput.addEventListener('blur', function () {
            if (this.value === '') {
                setInvalid(this, dummyIcon, passwordMsg, 'Password is required.', passwordMsgText);
            }
        });

        /* =========================================================
           TOGGLE PASSWORD VISIBILITY
        ========================================================= */
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = this.querySelector('i');
            this.style.transform = 'scale(0.9) translateY(-50%)';
            setTimeout(() => this.style.transform = 'scale(1) translateY(-50%)', 100);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        /* =========================================================
           FORM SUBMIT
        ========================================================= */
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            // Trigger blur on all fields to show any missed errors
            emailInput.dispatchEvent(new Event('blur'));
            passwordInput.dispatchEvent(new Event('blur'));

            const hasInvalid = this.querySelectorAll('.is-invalid').length > 0;
            const hasEmpty   = [emailInput, passwordInput].some(i => i.value.trim() === '');

            if (hasInvalid || hasEmpty) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Check your inputs',
                    text: 'Please fix the highlighted fields before submitting.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#000000',
                });
                return;
            }

            // Show loading state
            const btn  = document.getElementById('loginButton');
            const text = document.getElementById('buttonText');
            const spin = document.getElementById('buttonLoader');
            btn.disabled = true;
            text.classList.add('hidden');
            spin.classList.remove('hidden');
        });

        // Reset loader state on page load (handles back-nav / error redirects)
        document.getElementById('loginButton').disabled = false;
        document.getElementById('buttonText').classList.remove('hidden');
        document.getElementById('buttonLoader').classList.add('hidden');
    });
    </script>

    <!-- SweetAlert: server-side errors -->
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: '{{ $errors->first() }}',
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#000000',
        });
    </script>
    @endif

    @if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('status') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
        });
    </script>
    @endif

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
        });
    </script>
    @endif
</body>
</html>