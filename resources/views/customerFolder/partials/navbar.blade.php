<nav class="main-header">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center relative">
        <!-- Left Section - Mobile Menu + Home Text (hidden on mobile) -->
        <div class="flex items-center">
            <button id="mobile-menu-btn" class="text-white mr-4 hover-effect" aria-label="Open menu">
                <i class="fas fa-bars text-xl menu-icon"></i>
            </button>
            <!-- Home text only shows on medium screens and up -->
            <a href="{{ url('/') }}" class="text-white nav-link hidden md:inline">Home</a>
        </div>
        
        <!-- Center Section - Logo/Title -->
        <div class="absolute left-1/2 -translate-x-1/2 text-center pointer-events-none">
            <h1 class="text-xl sm:text-2xl md:text-3xl cursive-font">Villa Elena</h1>
            <!-- Hide subtitle on small screens -->
            <p class="text-xs hidden sm:block">Family Resort & Agri-Tourism Farm</p>
        </div>
        
        <!-- Right Section - Cart + User -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            <!-- Cart Icon -->
            <a href="{{ route('cart') }}" class="cart-icon relative hover-effect" id="cart-icon" aria-label="Cart">
                <i class="fas fa-shopping-cart text-lg sm:text-xl cart-icon-img"></i>
                <span id="navbar-cart-badge" class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full h-5 w-5 flex items-center justify-center hidden transition-all duration-300">
                    0
                </span>
            </a>

            <!-- User Authentication Section -->
            @auth
                <!-- My Bookings Link (for desktop only) -->
                <a href="{{ route('customer.bookings') }}" class="text-white hover:text-yellow-300 transition hidden md:inline hover-effect" aria-label="My Bookings">
                    <i class="fas fa-calendar-alt mr-1"></i> My Bookings
                </a>
                
                <!-- User Dropdown for logged in users -->
                <div class="relative dropdown group">
                    <button class="flex items-center space-x-1 sm:space-x-2 text-white hover:text-yellow-400 px-2 sm:px-3 py-2 rounded-lg transition hover-effect" aria-label="User menu">
                        <i class="fas fa-user-circle text-lg sm:text-xl user-icon"></i>
                        <!-- User name hidden on mobile, shows on medium screens -->
                        <span class="hidden md:inline text-sm lg:text-base">
                            {{ Auth::user()->firstName }} 
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300 dropdown-arrow hidden sm:block"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100 opacity-0 invisible transition-all duration-300 transform -translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                        <!-- My Bookings link for mobile dropdown -->
                        <a href="{{ route('customer.bookings') }}" class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition hover-effect md:hidden">
                            <i class="fas fa-calendar-alt mr-2"></i>My Bookings
                        </a>
                        <!-- Regular dropdown items -->
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition hover-effect">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        @if(Auth::user()->role === 'staff' || Auth::user()->role === 'manager')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition hover-effect">
                                <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                            </a>
                        @endif
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="hover:bg-yellow-50">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:text-yellow-600 transition hover-effect">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Show when user is NOT logged in - HIDDEN ON MOBILE -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Login button - shows only on medium screens and up -->
                    <a href="{{ route('login') }}" class="text-white hover:text-yellow-400 font-medium transition hover-effect">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                    <!-- Register button - shows only on medium screens and up -->
                    <a href="{{ route('register') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition hover-effect">
                        <i class="fas fa-user-plus mr-1"></i>Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Overlay for closing mobile menu -->
<div id="menu-overlay" class="menu-overlay"></div>

<!-- Mobile Menu -->
<div id="mobile-menu" class="mobile-menu">
    <button class="close-menu hover-effect" aria-label="Close menu">
        <i class="fas fa-times close-icon"></i>
    </button>
    
    <!-- Navigation Links -->
    <a href="{{ url('/') }}" class="nav-link hover-effect">
        <i class="fas fa-home mr-2"></i>Home
    </a>
    <a href="{{ route('about') }}" class="nav-link hover-effect">
        <i class="fas fa-info-circle mr-2"></i>About
    </a>
    <a href="{{ url('/virtual-tour/index.html') }}" class="nav-link hover-effect">
        <i class="fas fa-vr-cardboard mr-2"></i>Virtual Tour
    </a>
    <a href="{{ url('/#activities') }}" class="nav-link hover-effect">
        <i class="fas fa-hiking mr-2"></i>Activities
    </a>
    <a href="{{ url('/#gallery') }}" class="nav-link hover-effect">
        <i class="fas fa-images mr-2"></i>Gallery
    </a>
    <a href="{{ url('/#contact') }}" class="nav-link hover-effect">
        <i class="fas fa-phone-alt mr-2"></i>Contact Us
    </a>
    <a href="{{ route('roomBooking') }}" class="nav-link hover-effect">
        <i class="fas fa-calendar-check mr-2"></i>Book Now
    </a>
    
    <!-- Mobile Auth Links - ALWAYS SHOWS IN MOBILE MENU -->
    @auth
        <div class="border-t border-gray-600 mt-4 pt-4">
            <a href="{{ route('customer.bookings') }}" class="block py-2 text-white hover:text-yellow-400 transition hover-effect">
                <i class="fas fa-calendar-alt mr-2"></i>My Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="block py-2 text-white hover:text-yellow-400 transition hover-effect">
                <i class="fas fa-user mr-2"></i>Profile
            </a>
            @if(Auth::user()->role === 'staff' || Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block py-2 text-white hover:text-yellow-400 transition hover-effect">
                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2 text-white hover:text-yellow-400 transition hover-effect">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>
    @else
        <div class="border-t border-gray-600 mt-4 pt-4">
            <a href="{{ route('login') }}" class="block py-2 text-white hover:text-yellow-400 transition hover-effect">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
            <a href="{{ route('register') }}" class="block py-2 text-white hover:text-yellow-400 transition hover-effect">
                <i class="fas fa-user-plus mr-2"></i>Register
            </a>
        </div>
    @endauth
</div>

<style>
    /* Header Styles */
    .main-header {
        background-color: #000;
        color: white;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    
    .nav-link {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        color: white;
        text-decoration: none;
    }
    
    .nav-link:hover {
        color: #FFD700;
        transform: translateX(5px);
    }
    
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #FFD700;
        transition: width 0.3s ease;
    }
    
    .nav-link:hover::after {
        width: 100%;
    }
    
    /* Hover Effect Class */
    .hover-effect {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }
    
    .hover-effect:hover {
        transform: scale(1.05);
    }
    
    /* Icon Animations */
    .menu-icon, .cart-icon-img, .user-icon, .close-icon {
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .hover-effect:hover .menu-icon {
        animation: gentleShake 0.5s ease-in-out;
        color: #FFD700;
    }
    
    .hover-effect:hover .cart-icon-img {
        animation: cartBounce 0.6s ease;
        color: #FFD700;
    }
    
    .hover-effect:hover .user-icon {
        animation: gentlePulse 0.5s ease;
        color: #FFD700;
    }
    
    .hover-effect:hover .close-icon {
        animation: gentleSpin 0.5s ease;
        color: #FFD700;
    }
    
    /* Cart Icon Styles */
    .cart-icon {
        color: white;
        cursor: pointer;
        position: relative;
        padding: 5px;
    }
    
    .cart-icon:hover {
        color: #FFD700;
    }
    
    .cart-icon.active {
        color: #FFD700;
    }
    
    #navbar-cart-badge {
        animation: badgePop 0.3s ease;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    /* Mobile Menu - Smooth Transition */
    .mobile-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 300px;
        height: 100vh;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
        z-index: 1100;
        flex-direction: column;
        padding: 30px 25px;
        box-shadow: 5px 0 25px rgba(0, 0, 0, 0.4);
        overflow-y: auto;
        transition: all 0.4s cubic-bezier(0.77, 0, 0.175, 1);
        backdrop-filter: blur(10px);
    }
    
    .mobile-menu.active {
        left: 0;
    }
    
    .mobile-menu a {
        color: white;
        font-size: 1.1rem;
        margin: 12px 0;
        text-decoration: none;
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 3px solid transparent;
    }
    
    .mobile-menu a:hover {
        color: #FFD700;
        background: rgba(255, 215, 0, 0.1);
        border-left-color: #FFD700;
        transform: translateX(10px);
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.1);
    }
    
    .close-menu {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 1.8rem;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        cursor: pointer;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .close-menu:hover {
        background: rgba(255, 215, 0, 0.2);
        transform: rotate(90deg) scale(1.1);
    }
    
    /* Overlay */
    .menu-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 1099;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(3px);
    }
    
    .menu-overlay.active {
        display: block;
        opacity: 1;
    }
    
    /* Dropdown Styles */
    .dropdown-menu {
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .group:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .group:hover .dropdown-arrow {
        transform: rotate(180deg);
    }
    
    .dropdown-menu a {
        transition: all 0.2s ease;
    }
    
    .dropdown-menu a:hover {
        transform: translateX(5px);
    }
    
    /* Animations */
    @keyframes gentleShake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }
    
    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        30% { transform: scale(1.2) rotate(10deg); }
        60% { transform: scale(1.1) rotate(-5deg); }
    }
    
    @keyframes gentlePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
    
    @keyframes gentleSpin {
        0% { transform: rotate(0deg) scale(1); }
        100% { transform: rotate(90deg) scale(1.1); }
    }
    
    @keyframes badgePop {
        0% { transform: scale(0); }
        70% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    /* Responsive Design */
    /* Extra small devices (phones, less than 640px) */
    @media (max-width: 639px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .main-header .flex.justify-between {
            gap: 0.5rem;
        }
        
        .cursive-font {
            font-size: 1.25rem;
        }
        
        .mobile-menu {
            width: 280px;
            padding: 25px 20px;
        }
        
        .mobile-menu a {
            font-size: 1rem;
            padding: 12px 15px;
            margin: 10px 0;
        }
        
        /* Hide all text labels on very small screens */
        .main-header span:not(#navbar-cart-badge),
        .main-header .cursive-font + p {
            display: none;
        }
    }
    
    /* Small devices (640px to 767px) */
    @media (min-width: 640px) and (max-width: 767px) {
        .cursive-font {
            font-size: 1.5rem;
        }
        
        .main-header .text-xs {
            font-size: 0.75rem;
        }
        
        .mobile-menu {
            width: 280px;
        }
    }
    
    /* Medium devices (768px to 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        .cursive-font {
            font-size: 2rem;
        }
        
        /* Show user first name only on medium screens */
        .main-header .dropdown button span {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
    
    /* Large devices (1024px and up) */
    @media (min-width: 1024px) {
        .cursive-font {
            font-size: 2.5rem;
        }
    }
    
    /* Custom Scrollbar for Mobile Menu */
    .mobile-menu::-webkit-scrollbar {
        width: 5px;
    }
    
    .mobile-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .mobile-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 215, 0, 0.3);
        border-radius: 3px;
    }
    
    .mobile-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 215, 0, 0.5);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenuBtn = document.querySelector('.close-menu');
        const menuOverlay = document.getElementById('menu-overlay');
        
        // Function to open mobile menu with smooth animation
        function openMobileMenu() {
            // First show overlay
            menuOverlay.style.display = 'block';
            
            // Trigger reflow
            void menuOverlay.offsetWidth;
            
            // Add active classes
            setTimeout(() => {
                menuOverlay.classList.add('active');
                mobileMenu.classList.add('active');
                document.body.style.overflow = 'hidden';
            }, 10);
            
            // Add animation to menu items
            const menuItems = mobileMenu.querySelectorAll('.nav-link, .border-t a, form button');
            menuItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 100 + (index * 50));
            });
        }
        
        // Function to close mobile menu with smooth animation
        function closeMobileMenu() {
            // Animate out menu items first
            const menuItems = mobileMenu.querySelectorAll('.nav-link, .border-t a, form button');
            menuItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                }, index * 30);
            });
            
            // Close menu and overlay
            setTimeout(() => {
                mobileMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                
                setTimeout(() => {
                    menuOverlay.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    
                    // Reset menu items
                    menuItems.forEach(item => {
                        item.style.opacity = '';
                        item.style.transform = '';
                        item.style.transition = '';
                    });
                }, 300);
            }, 200);
        }
        
        // Event Listeners
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
            mobileMenuBtn.addEventListener('touchstart', function(e) {
                e.preventDefault();
                openMobileMenu();
            }, { passive: false });
        }
        
        if (closeMenuBtn) {
            closeMenuBtn.addEventListener('click', closeMobileMenu);
        }
        
        if (menuOverlay) {
            menuOverlay.addEventListener('click', closeMobileMenu);
            menuOverlay.addEventListener('touchstart', closeMobileMenu, { passive: true });
        }
        
        // Close mobile menu when clicking on a link
        if (mobileMenu) {
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Don't close if it's a dropdown item within the mobile menu
                    if (!this.closest('.dropdown-menu')) {
                        closeMobileMenu();
                    }
                });
            });
            
            // Close dropdown forms too
            mobileMenu.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    // Small delay to allow form submission
                    setTimeout(() => {
                        closeMobileMenu();
                    }, 300);
                });
            });
        }
        
        // Load cart count on page load
        loadCartCount();
        
        // Function to load cart count
        function loadCartCount() {
            fetch('/api/cart/count')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.count > 0) {
                        const cartBadge = document.getElementById('navbar-cart-badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.count;
                            cartBadge.style.display = 'flex';
                            
                            // Animate badge on load
                            cartBadge.style.animation = 'badgePop 0.3s ease';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }
        
        // Add hover effect to all hover-effect elements
        document.querySelectorAll('.hover-effect').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.classList.add('hovering');
            });
            
            element.addEventListener('mouseleave', function() {
                this.classList.remove('hovering');
            });
            
            // Touch support for mobile
            element.addEventListener('touchstart', function() {
                this.classList.add('hovering');
                setTimeout(() => {
                    this.classList.remove('hovering');
                }, 300);
            }, { passive: true });
        });
        
        // Keyboard support for mobile menu
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });
        
        // Responsive adjustments on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Close mobile menu if open on resize to larger screens
                if (window.innerWidth >= 768 && mobileMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            }, 250);
        });
        
        // Add active state to current page link in mobile menu
        const currentPath = window.location.pathname;
        mobileMenu.querySelectorAll('a[href]').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                link.style.color = '#FFD700';
                link.style.borderLeftColor = '#FFD700';
                link.style.backgroundColor = 'rgba(255, 215, 0, 0.1)';
            }
        });
    });
</script>