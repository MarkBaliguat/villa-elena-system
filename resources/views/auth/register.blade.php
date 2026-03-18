<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Register</title>
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
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-container { animation: fadeIn 0.6s ease-out; }

        .animate-form > * { animation: fadeInUp 0.5s ease-out backwards; }
        .animate-form > *:nth-child(1) { animation-delay: 0.1s; }
        .animate-form > *:nth-child(2) { animation-delay: 0.15s; }
        .animate-form > *:nth-child(3) { animation-delay: 0.2s; }
        .animate-form > *:nth-child(4) { animation-delay: 0.25s; }
        .animate-form > *:nth-child(5) { animation-delay: 0.3s; }
        .animate-form > *:nth-child(6) { animation-delay: 0.35s; }
        .animate-form > *:nth-child(7) { animation-delay: 0.4s; }
        .animate-form > *:nth-child(8) { animation-delay: 0.45s; }
        .animate-form > *:nth-child(9) { animation-delay: 0.5s; }

        /* ── Input base ── */
        .input-enhanced {
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-enhanced:focus {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* ── Validation states ── */
        .input-enhanced.is-valid {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
        }

        .input-enhanced.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
        }

        /* ── Error / success message ── */
        .field-msg {
            display: none;
            font-size: 0.72rem;
            margin-top: 4px;
            align-items: center;
            gap: 4px;
            animation: slideDown 0.25s ease;
        }

        .field-msg.show { display: flex; }
        .field-msg.error { color: #ef4444; }
        .field-msg.success { color: #22c55e; }

        /* ── Password strength bar ── */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            margin-top: 6px;
            transition: all 0.4s ease;
            width: 0%;
        }

        /* ── Button ── */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .btn-primary:active:not(:disabled) { transform: translateY(0); }
        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

        .image-overlay { animation: fadeIn 1s ease-out; }

        /* ── Password toggle wrapper fix ── */
        .pw-toggle-wrap {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 6px;
        }
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

        <!-- RIGHT SIDE FORM -->
        <div class="flex flex-col justify-center px-6 py-6 md:px-12 md:py-8 bg-white">
            <div class="animate-form">
                <div class="relative flex items-center mb-6 md:mb-8">
                    <a href="{{ route('home') }}"
                    class="absolute left-0 text-gray-400 hover:text-gray-700 transition-colors duration-200"
                    title="Back to Home">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-800 w-full text-center">Create Account</h2>
                </div>
                

                <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                    @csrf

                    <!-- ── Full Name ── -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Full Name</label>
                        <div class="relative">
                            <input 
                                id="name" 
                                type="text" 
                                name="name"
                                placeholder="Juan Dela Cruz"
                                value="{{ old('name') }}"
                                required autofocus
                                autocomplete="off"
                                class="w-full mt-1 px-3 py-2 pr-9 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('name') is-invalid @enderror"
                            />
                            <span id="name-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-sm pointer-events-none"></span>
                        </div>
                        <p id="name-msg" class="field-msg"><i class="fas fa-circle-info text-xs"></i><span id="name-msg-text"></span></p>
                        @error('name')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Username ── -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Username</label>
                        <div class="relative">
                            <input 
                                id="username" 
                                type="text" 
                                name="username"
                                placeholder="juandelacruz"
                                value="{{ old('username') }}"
                                maxlength="40"
                                required
                                autocomplete="off"
                                class="w-full mt-1 px-3 py-2 pr-9 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('username') is-invalid @enderror"
                            />
                            <span id="username-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-sm pointer-events-none"></span>
                        </div>
                        <p id="username-msg" class="field-msg"><i class="fas fa-circle-info text-xs"></i><span id="username-msg-text"></span></p>
                        @error('username')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Email ── -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <div class="relative">
                            <input 
                                id="email" 
                                type="email" 
                                name="email"
                                placeholder="juandelacruz@gmail.com"
                                value="{{ old('email') }}"
                                required
                                autocomplete="off"
                                class="w-full mt-1 px-3 py-2 pr-9 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('email') is-invalid @enderror"
                            />
<span id="email-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-sm pointer-events-none"></span>
                        </div>
                        <p id="email-msg" class="field-msg"><i class="fas fa-circle-info text-xs"></i><span id="email-msg-text"></span></p>
                        @error('email')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Password ── -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-3 py-2 pr-16 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('password') is-invalid @enderror"
                            />
                            <div class="pw-toggle-wrap">
                                <span id="strength-label" class="text-xs font-medium hidden"></span>
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 transition leading-none">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <!-- strength bar -->
                        <div class="w-full bg-gray-100 rounded-full mt-1.5 overflow-hidden" style="height:4px;">
                            <div id="strength-bar" class="strength-bar bg-gray-300"></div>
                        </div>
                        <p id="password-msg" class="field-msg"><i class="fas fa-circle-info text-xs"></i><span id="password-msg-text"></span></p>
                        @error('password')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Confirm Password ── -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-3 py-2 pr-9 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('password_confirmation') is-invalid @enderror"
                            />
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition leading-none">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <p id="confirm-msg" class="field-msg"><i class="fas fa-circle-info text-xs"></i><span id="confirm-msg-text"></span></p>
                        @error('password_confirmation')
                            <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ── Register Button ── -->
                    <button 
                        type="submit"
                        id="registerButton"
                        class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                        <span id="buttonText">Register</span>
                        <span id="buttonLoader" class="hidden">
                            <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>

                    <!-- Login link -->
                    <p class="text-center text-sm mt-4 text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-black font-semibold hover:underline transition">Log in</a>
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
            if (msgEl && msgTextEl) {
                msgTextEl.textContent = msgText || '';
                msgEl.className = 'field-msg show success';
                msgEl.querySelector('i').className = 'fas fa-check-circle text-xs';
            }
        }

        function setInvalid(input, iconEl, msgEl, msgText, msgTextEl) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            iconEl.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
            iconEl.classList.remove('hidden');
            if (msgEl && msgTextEl) {
                msgTextEl.textContent = msgText || '';
                msgEl.className = 'field-msg show error';
                msgEl.querySelector('i').className = 'fas fa-exclamation-circle text-xs';
            }
        }

        function clearState(input, iconEl, msgEl) {
            input.classList.remove('is-valid', 'is-invalid');
            iconEl.classList.add('hidden');
            if (msgEl) msgEl.className = 'field-msg';
        }

        /* =========================================================
           FULL NAME
        ========================================================= */
        const nameInput   = document.getElementById('name');
        const nameIcon    = document.getElementById('name-icon');
        const nameMsg     = document.getElementById('name-msg');
        const nameMsgText = document.getElementById('name-msg-text');

        nameInput.addEventListener('input', function () {
            const v = this.value.trim();
            if (v === '') {
                clearState(this, nameIcon, nameMsg);
            } else if (v.length < 2) {
                setInvalid(this, nameIcon, nameMsg, 'Name must be at least 2 characters.', nameMsgText);
            } else if (!/^[a-zA-Z\s\-'.]+$/.test(v)) {
                setInvalid(this, nameIcon, nameMsg, 'Only letters, spaces, hyphens, and apostrophes allowed.', nameMsgText);
            } else {
                setValid(this, nameIcon, nameMsg, 'Looks good!', nameMsgText);
            }
        });

        nameInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                setInvalid(this, nameIcon, nameMsg, 'Full name is required.', nameMsgText);
            }
        });

        /* =========================================================
           USERNAME
        ========================================================= */
        const usernameInput   = document.getElementById('username');
        const usernameIcon    = document.getElementById('username-icon');
        const usernameMsg     = document.getElementById('username-msg');
        const usernameMsgText = document.getElementById('username-msg-text');

        usernameInput.addEventListener('input', function () {
            const v = this.value.trim();
            if (v === '') {
                clearState(this, usernameIcon, usernameMsg);
            } else if (v.length < 3) {
                setInvalid(this, usernameIcon, usernameMsg, 'Username must be at least 3 characters.', usernameMsgText);
            } else if (!/^[a-zA-Z0-9_]+$/.test(v)) {
                setInvalid(this, usernameIcon, usernameMsg, 'Only letters, numbers, and underscores allowed.', usernameMsgText);
            } else if (v.length > 40) {
                setInvalid(this, usernameIcon, usernameMsg, 'Username must not exceed 40 characters.', usernameMsgText);
            } else {
                setValid(this, usernameIcon, usernameMsg, 'Username looks good!', usernameMsgText);
            }
        });

        usernameInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                setInvalid(this, usernameIcon, usernameMsg, 'Username is required.', usernameMsgText);
            }
        });

        /* =========================================================
           EMAIL — @gmail.com only
        ========================================================= */
        const emailInput   = document.getElementById('email');
        const emailIcon    = document.getElementById('email-icon');
        const emailMsg     = document.getElementById('email-msg');
        const emailMsgText = document.getElementById('email-msg-text');

        const emailRegex = /^[a-zA-Z0-9._%+\-]+@gmail\.com$/;

        emailInput.addEventListener('input', function () {
            const v = this.value.trim();
            if (v === '') {
                clearState(this, emailIcon, emailMsg);
            } else if (!emailRegex.test(v)) {
                setInvalid(this, emailIcon, emailMsg, 'Only @gmail.com email addresses are accepted.', emailMsgText);
            } else {
                setValid(this, emailIcon, emailMsg, 'Valid Gmail address.', emailMsgText);
            }
        });

        emailInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                setInvalid(this, emailIcon, emailMsg, 'Email address is required.', emailMsgText);
            }
        });

        /* =========================================================
           PASSWORD  +  STRENGTH METER
        ========================================================= */
        const passwordInput   = document.getElementById('password');
        const passwordMsg     = document.getElementById('password-msg');
        const passwordMsgText = document.getElementById('password-msg-text');
        const strengthBar     = document.getElementById('strength-bar');
        const strengthLabel   = document.getElementById('strength-label');

        const strengthLevels = [
            { label: 'Too short',  color: '#ef4444', width: '15%'  },
            { label: 'Weak',       color: '#f97316', width: '30%'  },
            { label: 'Fair',       color: '#eab308', width: '55%'  },
            { label: 'Good',       color: '#84cc16', width: '75%'  },
            { label: 'Strong',     color: '#22c55e', width: '100%' },
        ];

        function getStrengthScore(val) {
            if (val.length < 6) return 0;
            let score = 1;
            if (val.length >= 8)          score++;
            if (/[A-Z]/.test(val))        score++;
            if (/[0-9]/.test(val))        score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            return Math.min(score, 4);
        }

        const dummyPwIcon = { classList: { add: () => {}, remove: () => {} }, innerHTML: '' };

        passwordInput.addEventListener('input', function () {
            const v = this.value;

            if (v === '') {
                clearState(this, dummyPwIcon, passwordMsg);
                strengthBar.style.width = '0%';
                strengthLabel.classList.add('hidden');
                return;
            }

            const score = getStrengthScore(v);
            const level = strengthLevels[score];
            strengthBar.style.width      = level.width;
            strengthBar.style.background = level.color;
            strengthLabel.textContent    = level.label;
            strengthLabel.style.color    = level.color;
            strengthLabel.classList.remove('hidden');

            // ✅ FIX: number and special character are now separate checks
            const errors = [];
            if (v.length < 8)               errors.push('at least 8 characters');
            if (!/[A-Z]/.test(v))           errors.push('one uppercase letter');
            if (!/[a-z]/.test(v))           errors.push('one lowercase letter');
            if (!/[0-9]/.test(v))           errors.push('one number');
            if (!/[!@#$%^&*]/.test(v))      errors.push('one special character (!@#$%^&*)');

            if (errors.length) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                passwordMsgText.textContent = 'Needs: ' + errors.join(', ') + '.';
                passwordMsg.className = 'field-msg show error';
                passwordMsg.querySelector('i').className = 'fas fa-exclamation-circle text-xs';
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                passwordMsgText.textContent = 'Password meets all requirements!';
                passwordMsg.className = 'field-msg show success';
                passwordMsg.querySelector('i').className = 'fas fa-check-circle text-xs';
            }

            // Re-validate confirm field whenever password changes
            if (confirmInput.value !== '') validateConfirm();
        });

        passwordInput.addEventListener('blur', function () {
            if (this.value === '') {
                setInvalid(this, dummyPwIcon, passwordMsg, 'Password is required.', passwordMsgText);
                strengthBar.style.width = '0%';
                strengthLabel.classList.add('hidden');
            }
        });

        /* =========================================================
           CONFIRM PASSWORD
        ========================================================= */
        const confirmInput     = document.getElementById('password_confirmation');
        const confirmMsg       = document.getElementById('confirm-msg');
        const confirmMsgText   = document.getElementById('confirm-msg-text');
        const dummyConfirmIcon = { classList: { add: () => {}, remove: () => {} }, innerHTML: '' };

        function validateConfirm() {
            const pVal = passwordInput.value;
            const cVal = confirmInput.value;

            if (cVal === '') {
                clearState(confirmInput, dummyConfirmIcon, confirmMsg);
                return false;
            }

            if (cVal !== pVal) {
                setInvalid(confirmInput, dummyConfirmIcon, confirmMsg, 'Passwords do not match.', confirmMsgText);
                return false;
            } else {
                setValid(confirmInput, dummyConfirmIcon, confirmMsg, 'Passwords match!', confirmMsgText);
                return true;
            }
        }

        confirmInput.addEventListener('input', validateConfirm);
        confirmInput.addEventListener('blur', function () {
            if (this.value === '') {
                setInvalid(this, dummyConfirmIcon, confirmMsg, 'Please confirm your password.', confirmMsgText);
            }
        });

        /* =========================================================
           TOGGLE PASSWORD VISIBILITY
        ========================================================= */
        function makeToggle(btnId, inputId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const icon  = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        }

        makeToggle('togglePassword',        'password');
        makeToggle('toggleConfirmPassword', 'password_confirmation');

        /* =========================================================
           FORM SUBMIT
        ========================================================= */
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            ['name','username','email','password','password_confirmation'].forEach(id => {
                document.getElementById(id).dispatchEvent(new Event('blur'));
            });

            const hasInvalid = this.querySelectorAll('.is-invalid').length > 0;
            const hasEmpty   = ['name','username','email','password','password_confirmation']
                                .some(id => document.getElementById(id).value.trim() === '');
            const emailOk    = emailRegex.test(emailInput.value.trim());

            if (hasInvalid || hasEmpty || !emailOk) {
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
            const btn  = document.getElementById('registerButton');
            const text = document.getElementById('buttonText');
            const spin = document.getElementById('buttonLoader');
            btn.disabled = true;
            text.classList.add('hidden');
            spin.classList.remove('hidden');
        });

        // Reset loader on page load
        document.getElementById('registerButton').disabled = false;
        document.getElementById('buttonText').classList.remove('hidden');
        document.getElementById('buttonLoader').classList.add('hidden');
    });
    </script>

    <!-- SweetAlert: server-side errors -->
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            html: `<div class="text-left">@foreach ($errors->all() as $error)<p class="text-sm mb-1">• {{ $error }}</p>@endforeach</div>`,
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
            title: 'Account Created!',
            text: "{{ session('success') }}",
            confirmButtonText: 'Continue',
            confirmButtonColor: '#10b981',
        });
    </script>
    @endif
</body>
</html>