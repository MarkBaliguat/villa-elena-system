<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 lg:p-8" style="background: linear-gradient(135deg, #FFF4D5 0%, #ffffff 100%);">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
            
            <!-- LEFT SIDE IMAGE - Hidden on mobile/tablet, shown on desktop -->
            <div class="hidden lg:block relative bg-cover bg-center" style="background-image: url('/images/contact-image.jpg'); min-height: 600px;">
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-8">
                    <p class="text-white text-base mb-2 tracking-wide">Welcome to</p>
                    <h1 class="text-6xl cursive-font text-white mb-3 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-base tracking-wide">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- MOBILE/TABLET IMAGE - Only shown on smaller screens -->
            <div class="lg:hidden relative bg-cover bg-center" style="background-image: url('/images/contact-image.jpg'); height: 220px;">
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                    <p class="text-white text-sm sm:text-base mb-1 tracking-wide">Welcome to</p>
                    <h1 class="text-4xl sm:text-5xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-xs sm:text-sm tracking-wide">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div class="flex flex-col justify-center px-6 py-8 sm:px-10 sm:py-10 lg:px-12 lg:py-14 xl:px-16">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-900">Reset Password</h2>
                
                <div class="mb-6 sm:mb-8 text-sm sm:text-base text-gray-600 leading-relaxed">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus
                            placeholder="juandelacruz@gmail.com"
                            class="w-full px-4 py-3 sm:py-3.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all duration-200 text-sm sm:text-base @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-black text-white py-3 sm:py-3.5 rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-all duration-200 font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Send Password Reset Link
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

        /* Medium screens optimization */
        @media (min-width: 768px) and (max-width: 1023px) {
            .mobile-tablet-optimize {
                max-width: 600px;
                margin: 0 auto;
            }
        }

        /* Large screens optimization */
        @media (min-width: 1280px) {
            .bg-white {
                max-width: 1100px;
            }
        }
    </style>
</x-guest-layout>