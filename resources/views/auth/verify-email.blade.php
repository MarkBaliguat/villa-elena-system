<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: #ffffff;">
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
                class="md:hidden relative bg-cover bg-center"
                style="background-image: url('/images/contact.jpg'); height: 200px;">
                
                <div class="absolute inset-0 bg-gradient-to-br from-black/50 to-black/30 flex flex-col justify-center items-center text-center px-6">
                    <p class="text-white text-sm mb-1">Welcome to</p>
                    <h1 class="text-3xl cursive-font text-white mb-2 drop-shadow-lg">Villa Elena</h1>
                    <p class="text-white font-semibold text-xs">
                        Family Resort & Agri-Tourism Farm
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE CONTENT -->
            <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12 bg-white">
                <div class="animate-form">
                    
                    <!-- Email Icon -->
                    <div class="flex justify-center mb-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mb-3 text-gray-800 text-center">Verify Your Email</h2>
                    
                    <div class="mb-5 text-sm text-gray-600 leading-relaxed text-center">
                        Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                    </div>

                    <!-- Success Message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-5 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="font-medium text-sm text-green-800">
                                    A new verification link has been sent!
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <!-- Resend Email Button -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                                Resend Verification Email
                            </button>
                        </form>

                        <!-- Logout Button -->
                        <!-- <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full bg-white text-gray-700 py-2.5 rounded-xl hover:bg-gray-50 transition font-medium text-base border-2 border-gray-200 hover:border-gray-300">
                                Log Out
                            </button>
                        </form> -->
                    </div>

                    <!-- Info Footer -->
                    <div class="mt-5 pt-5 border-t border-gray-200 text-center">
                        <p class="text-xs text-gray-500">
                            Didn't receive the email? Check your spam folder or click resend.
                        </p>
                    </div>
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
        .animate-form > *:nth-child(6) { animation-delay: 0.6s; }

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
</x-guest-layout>