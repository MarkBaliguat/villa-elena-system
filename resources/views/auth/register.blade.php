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
            background: #ffffff;
            background-attachment: fixed;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        @media (max-width: 768px) {
            .mobile-image {
                height: 200px;
            }
        }

        /* Smooth animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-container {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-form > * {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        .animate-form > *:nth-child(1) { animation-delay: 0.1s; }
        .animate-form > *:nth-child(2) { animation-delay: 0.15s; }
        .animate-form > *:nth-child(3) { animation-delay: 0.2s; }
        .animate-form > *:nth-child(4) { animation-delay: 0.25s; }
        .animate-form > *:nth-child(5) { animation-delay: 0.3s; }
        .animate-form > *:nth-child(6) { animation-delay: 0.35s; }
        .animate-form > *:nth-child(7) { animation-delay: 0.4s; }
        .animate-form > *:nth-child(8) { animation-delay: 0.45s; }
        .animate-form > *:nth-child(9) { animation-delay: 0.5s; }

        /* Enhanced input styles */
        .input-enhanced {
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-enhanced:focus {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Button hover effect */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Image overlay animation */
        .image-overlay {
            animation: fadeIn 1s ease-out;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-container">
        
        <!-- LEFT SIDE IMAGE - Hidden on mobile, shown on tablet and desktop -->
        <div 
            class="hidden md:block relative bg-cover bg-center"
            style="background-image: url('/images/contact.jpg');">
            
            <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6 image-overlay">
                <p class="text-white text-sm mb-1 opacity-90">Welcome to</p>
                <h1 class="text-5xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                <p class="text-white font-semibold text-sm opacity-90">
                    Family Resort & Agri-Tourism Farm
                </p>
            </div>
        </div>

        <!-- MOBILE IMAGE - Only shown on mobile devices -->
        <div 
            class="md:hidden mobile-image relative bg-cover bg-center"
            style="background-image: url('/images/contact.jpg');">
            
            <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                <p class="text-white text-sm mb-1">Welcome to</p>
                <h1 class="text-3xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                <p class="text-white font-semibold text-xs">
                    Family Resort & Agri-Tourism Farm
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE REGISTRATION FORM -->
        <div class="flex flex-col justify-center px-6 py-6 md:px-12 md:py-8 bg-white">
            <div class="animate-form">
                <h2 class="text-2xl font-bold mb-5 text-center text-gray-800">Create Account</h2>

                <!-- FORM -->
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Full Name</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name"
                            placeholder="Juan Dela Cruz"
                            value="{{ old('name') }}"
                            required autofocus
                            class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition input-enhanced @error('name') border-red-500 @enderror"
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- username -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Username</label>
                        <input 
                            id="username" 
                            type="text" 
                            name="username"
                            placeholder="juandelacruz"
                            value="{{ old('username') }}"
                            required
                            class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition input-enhanced @error('username') border-red-500 @enderror"
                        />
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email"
                            placeholder="juandelacruz@gmail.com"
                            value="{{ old('email') }}"
                            required
                            class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition input-enhanced @error('email') border-red-500 @enderror"
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 input-enhanced @error('password') border-red-500 @enderror"
                            />
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 input-enhanced @error('password_confirmation') border-red-500 @enderror"
                            />
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Register Button -->
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

                    <!-- Login Link -->
                    <p class="text-center text-sm mt-4 text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-black font-semibold hover:underline transition">Log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility with smooth animation
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            // Add a little scale animation
            this.style.transform = 'scale(0.9) translateY(-50%)';
            setTimeout(() => {
                this.style.transform = 'scale(1) translateY(-50%)';
            }, 100);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            
            // Add a little scale animation
            this.style.transform = 'scale(0.9) translateY(-50%)';
            setTimeout(() => {
                this.style.transform = 'scale(1) translateY(-50%)';
            }, 100);
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Form validation and loading state
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (!name || !username || !email || !password || !passwordConfirmation) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'Please fill in all required fields.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#000000',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                return;
            }
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match. Please try again.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#000000',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                return;
            }

            // Show loading state
            const button = document.getElementById('registerButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoader.classList.remove('hidden');
        });

        // Add subtle hover effect to inputs
        document.querySelectorAll('.input-enhanced').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Remove loading state when page loads (in case of errors)
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('registerButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            
            button.disabled = false;
            buttonText.classList.remove('hidden');
            buttonLoader.classList.add('hidden');
        });
    </script>

    <!-- SweetAlert for Registration Errors -->
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                html: `
                    <div class="text-left">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm mb-1">• {{ $error }}</p>
                        @endforeach
                    </div>
                `,
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#000000',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        </script>
    @endif

    <!-- SweetAlert for Success Messages -->
    @if (session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('status') }}",
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        </script>
    @endif

    <!-- SweetAlert for Registration Success -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Account Created!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Continue',
                confirmButtonColor: '#10b981',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        </script>
    @endif
</body>
</html>