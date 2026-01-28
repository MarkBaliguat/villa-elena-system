<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 lg:p-8" style="background: linear-gradient(135deg, #FFF4D5 0%, #ffffff 100%);">
        <div class="w-full max-w-5xl rounded-2xl shadow-2xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
            
            <!-- LEFT SIDE IMAGE - Hidden on mobile/tablet, shown on desktop -->
            <div class="hidden lg:block relative bg-cover bg-center" style="background-image: url('/images/login-bg.jpg'); min-height: 600px;">
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-8">
                    <p class="text-white text-base mb-2 tracking-wide">Welcome to</p>
                    <h1 class="text-6xl cursive-font text-white mb-3 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-base tracking-wide">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- MOBILE/TABLET IMAGE - Only shown on smaller screens -->
            <div class="lg:hidden relative bg-cover bg-center" style="background-image: url('/images/login-bg.jpg'); height: 220px;">
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                    <p class="text-white text-sm sm:text-base mb-1 tracking-wide">Welcome to</p>
                    <h1 class="text-4xl sm:text-5xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-xs sm:text-sm tracking-wide">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="flex flex-col justify-center px-6 py-8 sm:px-10 sm:py-10 lg:px-12 lg:py-14 xl:px-16 bg-white">
                <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-900">Create New Password</h2>
                
                <div class="mb-6 sm:mb-8 text-sm sm:text-base text-gray-600 leading-relaxed">
                    Please enter your new password below. Make sure it's strong and secure.
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="juandelacruz@gmail.com"
                            class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all duration-200 text-sm sm:text-base @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Enter your new password"
                                class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all duration-200 text-sm sm:text-base @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Re-enter your new password"
                                class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all duration-200 text-sm sm:text-base @error('password_confirmation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="password_confirmation-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-gray-50 rounded-lg p-4 text-xs sm:text-sm text-gray-600">
                        <p class="font-semibold mb-2 text-gray-700">Password must contain:</p>
                        <ul class="space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                At least 8 characters
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One uppercase & lowercase letter
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One number or special character
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-black text-white py-3 sm:py-3.5 rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-all duration-200 font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transform hover:-translate-y-0.5 mt-6">
                        Reset Password
                    </button>

                    <!-- Back to Login Link -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}" 
                           class="text-gray-600 hover:text-black transition-colors duration-200 font-medium inline-flex items-center text-sm sm:text-base group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Smooth transitions for all interactive elements */
        input:focus {
            outline: none;
        }

        /* Responsive adjustments for very small screens */
        @media (max-width: 375px) {
            .cursive-font {
                font-size: 2.5rem;
            }
        }

        /* Large screens optimization */
        @media (min-width: 1280px) {
            .max-w-5xl {
                max-width: 1100px;
            }
        }
    </style>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                field.type = 'password';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>
</x-guest-layout>