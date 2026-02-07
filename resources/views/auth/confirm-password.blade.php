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

            <!-- RIGHT SIDE FORM -->
            <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12 bg-white">
                <div class="animate-form">
                    

                    <div class="flex justify-center mb-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-700 to-gray-900 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mb-3 text-center text-gray-800">Confirm Password</h2>
                    
                    <div class="mb-6 text-sm text-gray-600 text-center leading-relaxed">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password"
                                    placeholder="••••••••••••••••"
                                    required 
                                    autocomplete="current-password"
                                    class="w-full mt-1 px-3 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 input-enhanced @error('password') border-red-500 @enderror"
                                />
                                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition mt-0.5">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Button -->
                        <button 
                            type="submit"
                            class="w-full bg-black text-white py-2.5 rounded-xl hover:bg-gray-800 transition font-semibold text-base btn-primary">
                            Confirm
                        </button>
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
       
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            
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
    </script>
</x-guest-layout>