<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 lg:p-8" style="background: linear-gradient(135deg, #FFF4D5 0%, #ffffff 100%);">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
            
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

            <!-- RIGHT SIDE CONTENT -->
            <div class="flex flex-col justify-center px-6 py-8 sm:px-10 sm:py-10 lg:px-12 lg:py-14 xl:px-16">
                
                <!-- Email Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl sm:text-3xl font-bold mb-4 text-gray-900 text-center">Verify Your Email</h2>
                
                <div class="mb-6 text-sm sm:text-base text-gray-600 leading-relaxed text-center">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="font-medium text-sm text-green-800">
                                {{ __('A new verification link has been sent to your email address!') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <!-- Resend Email Button -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full bg-black text-white py-3 sm:py-3.5 rounded-lg hover:bg-gray-800 active:bg-gray-900 transition-all duration-200 font-semibold text-sm sm:text-base shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Resend Verification Email
                        </button>
                    </form>

                    <!-- Logout Button -->
                    <!-- <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full bg-gray-100 text-gray-700 py-3 sm:py-3.5 rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-all duration-200 font-semibold text-sm sm:text-base border-2 border-gray-300 hover:border-gray-400">
                            Log Out
                        </button>
                    </form> -->
                </div>

                <!-- Info Footer -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-xs sm:text-sm text-gray-500">
                        Didn't receive the email? Check your spam folder or click resend.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>