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
            /* background: #ffffff;
            background-attachment: fixed; */
            background:linear-gradient(to bottom,#ffffff 0%,#fffdf5 60%,rgb(255, 249, 230) 100%);
            /* background-attachment: fixed; */
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
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
        .animate-form > *:nth-child(2) { animation-delay: 0.2s; }
        .animate-form > *:nth-child(3) { animation-delay: 0.3s; }
        .animate-form > *:nth-child(4) { animation-delay: 0.4s; }
        .animate-form > *:nth-child(5) { animation-delay: 0.5s; }
        .animate-form > *:nth-child(6) { animation-delay: 0.6s; }

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

        <!-- RIGHT SIDE LOGIN FORM -->
        <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12 bg-white">
            <div class="animate-form">
                <h2 class="text-2xl font-bold mb-6 md:mb-8 text-center text-gray-800">Log back in</h2>

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!--Email -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email"
                            placeholder="juandelacruz@gmail.com"
                            value="{{ old('email') }}"
                            required autofocus
                            class="w-full mt-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition input-enhanced @error('email') border-red-500 @enderror"
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label class="text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password"
                                placeholder="••••••••••••••••"
                                required
                                class="w-full mt-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-12 input-enhanced @error('password') border-red-500 @enderror"
                            />
                            <button type="button" id="togglePassword" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                <i class="far fa-eye text-lg"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex justify-between items-center mb-6">
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
        
        // Form validation and loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
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

            // Show loading state
            const button = document.getElementById('loginButton');
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
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            
            button.disabled = false;
            buttonText.classList.remove('hidden');
            buttonLoader.classList.add('hidden');
        });
    </script>

    <!-- SweetAlert for Login Errors -->
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '{{ $errors->first() }}',
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

    <!-- SweetAlert for Success Messages (if any) -->
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

    <!-- SweetAlert for Password Reset Success -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
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
</body>
</html>