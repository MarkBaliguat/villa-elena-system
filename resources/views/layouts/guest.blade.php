<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Villa Elena - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Tailwind CSS CDN -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
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
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </body>
</html>