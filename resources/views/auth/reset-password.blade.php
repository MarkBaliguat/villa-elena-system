<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: #ffffff;">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-container">
            
            <!-- LEFT SIDE IMAGE - Hidden on mobile, shown on tablet and desktop -->
            <div 
                class="hidden md:block relative bg-cover bg-center"
                style="background-image: url('/images/contact-image.jpg');">
                
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
                class="md:hidden relative bg-cover bg-center"
                style="background-image: url('/images/contact-image.jpg'); height: 200px;">
                
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                    <p class="text-white text-sm mb-1">Welcome to</p>
                    <h1 class="text-3xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-xs">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="flex flex-col justify-center px-6 py-6 md:px-12 md:py-8 bg-white">
                <div class="animate-form">
                    <h2 class="text-2xl font-bold mb-3 text-center text-gray-800">Create New Password</h2>
                    
                    <div class="mb-4 text-sm text-gray-600 text-center leading-relaxed">
                        Please enter your new password below. Make sure it's strong and secure.
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address (Hidden) -->
                        <input 
                            id="email" 
                            type="hidden" 
                            name="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autocomplete="username"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        <!-- Password -->
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
                                    class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 input-enhanced @error('password') border-red-500 @enderror"
                                />
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                    <i id="password-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
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
                                    class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 input-enhanced @error('password_confirmation') border-red-500 @enderror"
                                />
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                    <i id="password_confirmation-icon" class="far fa-eye"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Password Requirements -->
                        <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-600">
                            <p class="font-semibold mb-2 text-gray-700">Password must contain:</p>
                            <ul class="space-y-1.5">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    At least 8 characters
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One uppercase & lowercase letter
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One number or special character
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                            Reset Password
                        </button>

                        <!-- Back to Login Link -->
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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }

        .cursive-font {
            font-family: 'Dancing Script', cursive;
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

        /* Enhanced input styles */
        .input-enhanced {
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .input-enhanced:focus {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            outline: none;
        }

        /* Button hover effect */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Image overlay animation */
        .image-overlay {
            animation: fadeIn 1s ease-out;
        }

        @media (max-width: 768px) {
            .mobile-image {
                height: 200px;
            }
        }
    </style>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            // Add scale animation
            icon.parentElement.style.transform = 'scale(0.9) translateY(-50%)';
            setTimeout(() => {
                icon.parentElement.style.transform = 'scale(1) translateY(-50%)';
            }, 100);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>