<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: #ffffff;">
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
            <div class="md:hidden relative bg-cover bg-center"
                 style="background-image: url('/images/contact.jpg'); height: 200px;">
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                    <p class="text-white text-sm mb-1">Welcome to</p>
                    <h1 class="text-3xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-xs">Family Resort & Agri-Tourism Farm</p>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="flex flex-col justify-center px-6 py-6 md:px-12 md:py-8 bg-white">
                <div class="animate-form">
                    <h2 class="text-2xl font-bold mb-3 text-center text-gray-800">Create New Password</h2>
                    
                    <div class="mb-4 text-sm text-gray-600 text-center leading-relaxed">
                        Please enter your new password below. Make sure it's strong and secure.
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm" class="space-y-4" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <input id="email" type="hidden" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" />

                        <!-- ── New Password ── -->
                        <div>
                            <label for="password" class="text-sm font-medium text-gray-700">New Password</label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Enter your new password"
                                    class="w-full mt-1 px-3 py-2 pr-10 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('password') is-invalid @enderror"
                                />
                                <button type="button" onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                    <i id="password-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            <!-- Strength bar -->
                            <div class="w-full bg-gray-100 rounded-full mt-1.5 overflow-hidden" style="height:4px;">
                                <div id="strength-bar" class="strength-bar"></div>
                            </div>
                            <p id="password-msg" class="field-msg">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span id="password-msg-text"></span>
                            </p>
                            @error('password')
                                <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ── Confirm Password ── -->
                        <div>
                            <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm New Password</label>
                            <div class="relative">
                                <input 
                                    id="password_confirmation" 
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Re-enter your new password"
                                    class="w-full mt-1 px-3 py-2 pr-10 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('password_confirmation') is-invalid @enderror"
                                />
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                    <i id="password_confirmation-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            <p id="confirm-msg" class="field-msg">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span id="confirm-msg-text"></span>
                            </p>
                            @error('password_confirmation')
                                <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ── Password Requirements (live) ── -->
                        <div class="bg-gray-50 rounded-lg p-3 text-xs">
                            <p class="font-semibold mb-2 text-gray-700">Password must contain:</p>
                            <ul class="space-y-1.5">
                                <li id="req-length" class="flex items-center text-gray-400 transition-all duration-300">
                                    <svg id="req-length-icon" class="w-4 h-4 mr-2 text-gray-300 transition-colors duration-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    At least 8 characters
                                </li>
                                <li id="req-case" class="flex items-center text-gray-400 transition-all duration-300">
                                    <svg id="req-case-icon" class="w-4 h-4 mr-2 text-gray-300 transition-colors duration-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One uppercase & lowercase letter
                                </li>
                                <li id="req-num" class="flex items-center text-gray-400 transition-all duration-300">
                                    <svg id="req-num-icon" class="w-4 h-4 mr-2 text-gray-300 transition-colors duration-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One number or special character
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="resetButton"
                            class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                            <span id="buttonText">Reset Password</span>
                            <span id="buttonLoader" class="hidden">
                                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center pt-2">
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-black transition font-medium inline-flex items-center text-sm group">
                                <svg class="w-4 h-4 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput = document.getElementById('password');
        const confirmInput  = document.getElementById('password_confirmation');
        const passwordMsg   = document.getElementById('password-msg');
        const passwordMsgTx = document.getElementById('password-msg-text');
        const confirmMsg    = document.getElementById('confirm-msg');
        const confirmMsgTx  = document.getElementById('confirm-msg-text');
        const strengthBar   = document.getElementById('strength-bar');
        const btn           = document.getElementById('resetButton');
        const btnText       = document.getElementById('buttonText');
        const btnLoader     = document.getElementById('buttonLoader');

        /* =========================================================
           HELPERS
        ========================================================= */
        function setValid(input, msgEl, msgText, msgTxEl) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            msgTxEl.textContent = msgText;
            msgEl.className = 'field-msg show success';
            msgEl.querySelector('i').className = 'fas fa-check-circle text-xs';
        }

        function setInvalid(input, msgEl, msgText, msgTxEl) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            msgTxEl.textContent = msgText;
            msgEl.className = 'field-msg show error';
            msgEl.querySelector('i').className = 'fas fa-exclamation-circle text-xs';
        }

        function clearField(input, msgEl) {
            input.classList.remove('is-valid', 'is-invalid');
            msgEl.className = 'field-msg';
        }

        /* =========================================================
           REQUIREMENTS CHECKER
        ========================================================= */
        const reqs = {
            length : { el: document.getElementById('req-length'), icon: document.getElementById('req-length-icon'), test: v => v.length >= 8 },
            case   : { el: document.getElementById('req-case'),   icon: document.getElementById('req-case-icon'),   test: v => /[A-Z]/.test(v) && /[a-z]/.test(v) },
            num    : { el: document.getElementById('req-num'),    icon: document.getElementById('req-num-icon'),    test: v => /[0-9!@#$%^&*]/.test(v) },
        };

        function updateReqs(val) {
            Object.values(reqs).forEach(({ el, icon, test }) => {
                if (test(val)) {
                    icon.classList.remove('text-gray-300'); icon.classList.add('text-green-500');
                    el.classList.remove('text-gray-400');   el.classList.add('text-gray-700');
                } else {
                    icon.classList.remove('text-green-500'); icon.classList.add('text-gray-300');
                    el.classList.remove('text-gray-700');    el.classList.add('text-gray-400');
                }
            });
        }

        /* =========================================================
           STRENGTH METER
        ========================================================= */
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

        /* =========================================================
           PASSWORD FIELD
        ========================================================= */
        passwordInput.addEventListener('input', function () {
            const v = this.value;
            updateReqs(v);

            if (v === '') {
                clearField(this, passwordMsg);
                strengthBar.style.width = '0%';
                return;
            }

            const lvl = levels[getScore(v)];
            strengthBar.style.width      = lvl.width;
            strengthBar.style.background = lvl.color;

            const errors = [];
            if (v.length < 8)             errors.push('at least 8 characters');
            if (!/[A-Z]/.test(v))         errors.push('one uppercase letter');
            if (!/[a-z]/.test(v))         errors.push('one lowercase letter');
            if (!/[0-9!@#$%^&*]/.test(v)) errors.push('one number or special character');

            if (errors.length) {
                setInvalid(this, passwordMsg, 'Needs: ' + errors.join(', ') + '.', passwordMsgTx);
            } else {
                setValid(this, passwordMsg, 'Password meets all requirements!', passwordMsgTx);
            }

            if (confirmInput.value !== '') validateConfirm();
        });

        passwordInput.addEventListener('blur', function () {
            if (this.value === '') setInvalid(this, passwordMsg, 'New password is required.', passwordMsgTx);
        });

        /* =========================================================
           CONFIRM PASSWORD FIELD
        ========================================================= */
        function validateConfirm() {
            const v = confirmInput.value;
            if (v === '') { clearField(confirmInput, confirmMsg); return false; }
            if (v !== passwordInput.value) {
                setInvalid(confirmInput, confirmMsg, 'Passwords do not match.', confirmMsgTx);
                return false;
            }
            setValid(confirmInput, confirmMsg, 'Passwords match!', confirmMsgTx);
            return true;
        }

        confirmInput.addEventListener('input', validateConfirm);
        confirmInput.addEventListener('blur', function () {
            if (this.value === '') setInvalid(this, confirmMsg, 'Please confirm your new password.', confirmMsgTx);
        });

        /* =========================================================
           FORM SUBMIT
        ========================================================= */
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            passwordInput.dispatchEvent(new Event('blur'));
            confirmInput.dispatchEvent(new Event('blur'));

            const hasInvalid = this.querySelectorAll('.is-invalid').length > 0;
            const hasEmpty   = [passwordInput, confirmInput].some(i => i.value.trim() === '');

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

            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
        });

        // Reset loader on page load
        btn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
    });

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(fieldId + '-icon');
        icon.parentElement.style.transform = 'scale(0.9) translateY(-50%)';
        setTimeout(() => icon.parentElement.style.transform = 'scale(1) translateY(-50%)', 100);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>

    @if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Password Reset Successful!',
            text: 'Your password has been reset. You can now login with your new password.',
            confirmButtonColor: '#000000',
            confirmButtonText: 'Go to Login'
        }).then(r => { if (r.isConfirmed) window.location.href = "{{ route('login') }}"; });
    </script>
    @endif

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `<ul style="text-align:left;padding-left:20px;list-style:disc;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
            confirmButtonColor: '#000000',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');

        body { font-family: 'Poppins', sans-serif; }
        .cursive-font { font-family: 'Dancing Script', cursive; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; } to { opacity: 1; }
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

        .input-enhanced { transition: all 0.3s ease; background: #fafafa; }
        .input-enhanced:focus {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            outline: none;
        }

        .input-enhanced.is-valid {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
        }
        .input-enhanced.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
        }

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

        .strength-bar { height: 4px; border-radius: 4px; transition: all 0.4s ease; width: 0%; }

        .btn-primary { position: relative; overflow: hidden; transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .btn-primary:active { transform: translateY(0); }

        .image-overlay { animation: fadeIn 1s ease-out; }
        .swal2-popup { font-family: 'Poppins', sans-serif !important; }

        @media (max-width: 768px) { .mobile-image { height: 200px; } }
    </style>
</x-guest-layout>