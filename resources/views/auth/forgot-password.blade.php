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
            <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12 bg-white">
                <div class="animate-form">
                    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Reset Password</h2>
                    
                    <div class="mb-6 text-sm text-gray-600 text-center leading-relaxed">
                        Forgot your password? No problem. Just enter your email address and we'll send you a password reset link.
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" id="resetPasswordForm" novalidate>
                        @csrf

                        <!-- ── Email Address ── -->
                        <div class="mb-6">
                            <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                            <div class="relative">
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus
                                    autocomplete="off"
                                    placeholder="juandelacruz@gmail.com"
                                    class="w-full mt-1 px-3 py-2 pr-10 border-2 border-gray-200 rounded-xl focus:outline-none transition input-enhanced @error('email') is-invalid @enderror"
                                />
                                <span id="email-icon" class="absolute right-3 top-1/2 -translate-y-1/2 mt-0.5 hidden text-sm pointer-events-none"></span>
                            </div>
                            <p id="email-msg" class="field-msg">
                                <i class="fas fa-circle-info text-xs"></i>
                                <span id="email-msg-text"></span>
                            </p>
                            @error('email')
                                <p class="field-msg show error"><i class="fas fa-exclamation-circle text-xs"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="resetButton"
                            class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                            <span id="buttonText">Send Password Reset Link</span>
                            <span id="buttonLoader" class="hidden">
                                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center mt-6">
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const emailInput   = document.getElementById('email');
        const emailIcon    = document.getElementById('email-icon');
        const emailMsg     = document.getElementById('email-msg');
        const emailMsgText = document.getElementById('email-msg-text');
        const emailRegex   = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const btn          = document.getElementById('resetButton');
        const btnText      = document.getElementById('buttonText');
        const btnLoader    = document.getElementById('buttonLoader');

        /* =========================================================
           HELPERS
        ========================================================= */
        function setValid(msg, msgText, icon, input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            icon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
            icon.classList.remove('hidden');
            msgText.textContent = 'Valid email address.';
            msg.className = 'field-msg show success';
            msg.querySelector('i').className = 'fas fa-check-circle text-xs';
        }

        function setInvalid(msg, msgText, icon, input, text) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            icon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
            icon.classList.remove('hidden');
            msgText.textContent = text;
            msg.className = 'field-msg show error';
            msg.querySelector('i').className = 'fas fa-exclamation-circle text-xs';
        }

        function clearState() {
            emailInput.classList.remove('is-valid', 'is-invalid');
            emailIcon.classList.add('hidden');
            emailMsg.className = 'field-msg';
        }

        /* =========================================================
           EMAIL REAL-TIME VALIDATION
        ========================================================= */
        emailInput.addEventListener('input', function () {
            const v = this.value.trim();
            if (v === '') {
                clearState();
            } else if (!emailRegex.test(v)) {
                setInvalid(emailMsg, emailMsgText, emailIcon, this, 'Please enter a valid email address.');
            } else {
                setValid(emailMsg, emailMsgText, emailIcon, this);
            }
        });

        emailInput.addEventListener('blur', function () {
            if (this.value.trim() === '') {
                setInvalid(emailMsg, emailMsgText, emailIcon, this, 'Email address is required.');
            }
        });

        /* =========================================================
           FORM SUBMIT
        ========================================================= */
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            emailInput.dispatchEvent(new Event('blur'));

            const hasInvalid = this.querySelectorAll('.is-invalid').length > 0;
            const isEmpty    = emailInput.value.trim() === '';

            if (hasInvalid || isEmpty) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Check your input',
                    text: 'Please enter a valid email address.',
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
    </script>

    @if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Email Sent!',
            text: "{{ session('status') }}",
            confirmButtonColor: '#000000',
            confirmButtonText: 'OK'
        });
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

        .btn-primary { position: relative; overflow: hidden; transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .btn-primary:active { transform: translateY(0); }

        .image-overlay { animation: fadeIn 1s ease-out; }
        .swal2-popup { font-family: 'Poppins', sans-serif !important; }

        @media (max-width: 768px) { .mobile-image { height: 200px; } }
    </style>
</x-guest-layout>