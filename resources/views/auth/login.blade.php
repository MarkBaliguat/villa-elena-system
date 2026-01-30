<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFF4D5 0%, #ffffff 100%);
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 overflow-hidden">
        
        <!-- LEFT SIDE IMAGE - Hidden on mobile, shown on tablet and desktop -->
        <div 
            class="hidden md:block relative bg-cover bg-center"
            style="background-image: url('/images/contact-image.jpg');">
            
            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center px-6">
                <p class="text-white text-sm mb-1">Welcome to</p>
                <h1 class="text-5xl cursive-font text-white mb-2">Villa Elena</h1>
                <p class="text-white font-semibold text-sm">
                    Family Resort & Agri-Tourism Farm
                </p>
            </div>
        </div>

        <!-- MOBILE IMAGE - Only shown on mobile devices -->
        <div 
            class="md:hidden mobile-image relative bg-cover bg-center"
            style="background-image: url('/images/contact-image.jpg');">
          
            
            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center px-6">
                <p class="text-white text-sm mb-1">Welcome to</p>
                <h1 class="text-3xl cursive-font text-white mb-2">Villa Elena</h1>
                <p class="text-white font-semibold text-xs">
                    Family Resort & Agri-Tourism Farm
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE LOGIN FORM -->
        <div class="flex flex-col justify-center px-6 py-8 md:px-14 md:py-12">
            <h2 class="text-2xl font-bold mb-6 md:mb-8 text-center">Log back in</h2>

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!--Email -->
                <div class="mb-4">
                    <label class="text-sm font-medium">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email"
                        placeholder="Juandelacruz@gmail.com"
                        value="{{ old('email') }}"
                        required autofocus
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition @error('email') border-red-500 @enderror"
                    />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label class="text-sm font-medium">Password</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password"
                            placeholder="••••••••••••••••"
                            required
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition pr-10 @error('password') border-red-500 @enderror"
                        />
                        <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 transition">
                            <i class="far fa-eye"></i>
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
                            class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded"
                        />
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-black transition">Forgot password?</a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition font-medium">
                    Log In
                </button>

                <!-- Sign Up Link -->
                <p class="text-center text-sm mt-6 text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-black font-semibold hover:underline transition">Create an account</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
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
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html>