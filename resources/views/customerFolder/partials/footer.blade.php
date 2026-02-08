<footer class="main-footer py-12">
    <div class="container mx-auto px-4">
        <!-- Logo and Tagline -->
        <div class="text-center mb-10 footer-logo">
            <h2 class="text-4xl cursive-font mb-3 text-gray-800">Villa Elena</h2>
            <p class="text-sm mb-4 tracking-widest text-gray-600">Family Resort & Agri-Tourism Farm</p>
            <p class="italic mb-6 tagline text-gray-700">"Relax. Reconnect. Recharge at Villa Elena"</p>
            
            <!-- Sunflower Animation -->
            <div class="sunflower-animation mx-auto mb-6">
                <div class="sunflower">
                    <div class="petals">
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                        <div class="petal"></div>
                    </div>
                    <div class="center"></div>
                </div>
            </div>
        </div>
        
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-5xl mx-auto text-center md:text-left">
            <!-- Location Section with Map -->
            <div class="footer-section">
                <div class="section-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="footer-heading text-gray-800">Find us here</h4>
                
                <!-- Address -->
                <div class="mb-4">
                    <p class="text-sm mb-2 flex items-center justify-center text-gray-600">
                        <i class="fas fa-location-dot mr-2 text-yellow-500"></i>
                        Munoz, Philippines
                    </p>
                    <a href="https://maps.google.com/?q=Villa+Elena+Munoz+Philippines" 
                       target="_blank" 
                       class="map-link inline-flex items-center text-sm hover-effect text-yellow-600 hover:text-yellow-700 mb-4">
                        <i class="fas fa-directions mr-2"></i>
                        Get Directions
                    </a>
                </div>
                
                <!-- Map Image with proper styling -->
                <div class="map-container mt-4">
                    <a href="https://maps.google.com/?q=Villa+Elena+Munoz+Philippines" 
                       target="_blank" 
                       class="block map-image-link hover-effect group" 
                       aria-label="Open Villa Elena location in Google Maps">
                        <div class="relative overflow-hidden rounded-xl border-2 border-yellow-100 shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:border-yellow-300">
                            <img src="/images/map.png" 
                                 alt="Villa Elena Location Map in Munoz, Philippines" 
                                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <!-- Map overlay effect -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Zoom icon -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-yellow-500 text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 scale-0 group-hover:scale-100 shadow-lg">
                                <i class="fas fa-search-plus text-lg"></i>
                            </div>
                            
                            <!-- Map label -->
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 text-white">
                                <p class="text-xs font-semibold">Click to view on Google Maps</p>
                                <p class="text-xs opacity-90">Villa Elena, Munoz</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Map caption -->
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Interactive map - Click to explore our location
                    </p>
                </div>
            </div>
            
            <!-- Quick Links Section -->
            <div class="footer-section">
                <div class="section-icon">
                    <i class="fas fa-link"></i>
                </div>
                <h4 class="footer-heading text-gray-800">Quick Links</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/') }}" 
                           class="footer-link hover-effect group text-gray-600 hover:text-yellow-600">
                            <i class="fas fa-home mr-2 group-hover:rotate-12 transition-transform"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roomBooking') }}" 
                           class="footer-link hover-effect group text-gray-600 hover:text-yellow-600">
                            <i class="fas fa-bed mr-2 group-hover:bounce transition"></i>
                            Book a Room
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cottageBooking') }}" 
                           class="footer-link hover-effect group text-gray-600 hover:text-yellow-600">
                            <i class="fas fa-home mr-2 group-hover:wobble transition"></i>
                            Book a Cottage
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cart') }}" 
                           class="footer-link hover-effect group text-gray-600 hover:text-yellow-600">
                            <i class="fas fa-shopping-cart mr-2 group-hover:spin transition"></i>
                            View Cart
                            <span id="footer-cart-badge" class="ml-2 bg-yellow-500 text-black text-xs rounded-full px-2 py-1 hidden"></span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Section -->
            <div class="footer-section">
                <div class="section-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4 class="footer-heading text-gray-800">Contact Info</h4>
                
                <div class="space-y-4">
                    <!-- Phone -->
                    <a href="tel:+639173010790" 
                       class="contact-item hover-effect group">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="text-left flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">0917 301 0790</p>
                            <p class="text-xs text-gray-500">Call us directly</p>
                        </div>
                        <i class="fas fa-chevron-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 flex-shrink-0"></i>
                    </a>
                    
                    <!-- Email -->
                    <a href="mailto:ebs_sunflower@yahoo.com" 
                       class="contact-item hover-effect group email-item">
                        <div class="contact-icon email-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="text-left flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 break-all email-text">ebs_sunflower@yahoo.com</p>
                            <p class="text-xs text-gray-500">Email us anytime</p>
                        </div>
                        <i class="fas fa-chevron-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 flex-shrink-0"></i>
                    </a>
                    
                    <!-- Social Media -->
                    <div class="social-links mt-6">
                        <h5 class="text-sm font-medium mb-3 text-gray-800">Follow us on</h5>
                        <div class="flex justify-center space-x-4">
                            <a href="https://www.facebook.com/VillaElenaFamilyResort" 
                               target="_blank" 
                               class="social-icon facebook hover-effect"
                               title="Follow us on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" 
                               class="social-icon instagram hover-effect"
                               title="Follow us on Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" 
                               class="social-icon twitter hover-effect"
                               title="Follow us on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" 
                               class="social-icon tiktok hover-effect"
                               title="Follow us on TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="text-center mt-12 pt-8 border-t border-gray-300">

            
            <!-- Copyright -->
            <p class="text-xs mb-4 text-gray-600">
                © <span id="current-year">2025</span> Villa Elena Resort. All rights reserved.
            </p>
            
            <!-- Back to Top Button -->
            <button id="back-to-top" 
                    class="back-to-top-btn hover-effect" 
                    aria-label="Back to top">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
    </div>
</footer>

<style>
    /* Footer Base Styles */
    .main-footer {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        position: relative;
        overflow: hidden;
        border-top: 1px solid #e5e7eb;
    }
    
    /* Sunflower Animation */
    .sunflower-animation {
        width: 60px;
        height: 60px;
        position: relative;
    }
    
    .sunflower {
        width: 100%;
        height: 100%;
        position: relative;
        animation: float 6s ease-in-out infinite;
    }
    
    .petals {
        position: absolute;
        width: 100%;
        height: 100%;
        animation: rotate 20s linear infinite;
    }
    
    .petal {
        position: absolute;
        width: 30px;
        height: 15px;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        margin: -7.5px -15px;
        transform-origin: 15px 0;
    }
    
    .petal:nth-child(1) { transform: rotate(0deg) translateX(30px); }
    .petal:nth-child(2) { transform: rotate(45deg) translateX(30px); }
    .petal:nth-child(3) { transform: rotate(90deg) translateX(30px); }
    .petal:nth-child(4) { transform: rotate(135deg) translateX(30px); }
    .petal:nth-child(5) { transform: rotate(180deg) translateX(30px); }
    .petal:nth-child(6) { transform: rotate(225deg) translateX(30px); }
    .petal:nth-child(7) { transform: rotate(270deg) translateX(30px); }
    .petal:nth-child(8) { transform: rotate(315deg) translateX(30px); }
    
    .center {
        position: absolute;
        width: 29px;
        height: 29px;
        background: linear-gradient(135deg, #8B4513, #A0522D);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-40%, -60%);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
    }
    
    /* Footer Sections */
    .footer-section {
        position: relative;
        padding: 25px;
        border-radius: 16px;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f1f1;
        min-width: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-align: center;
        
    }
    
    /* First section (map) specific styling */
    .footer-section:nth-child(1) {
        min-height: 420px;
    }
    
    .footer-section:hover {
        background: white;
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(255, 215, 0, 0.15);
        border-color: rgba(255, 215, 0, 0.3);
    }
    
    .section-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 1.4rem;
        color: #000;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        flex-shrink: 0;
    }
    
    .footer-section:hover .section-icon {
        transform: scale(1.15) rotate(360deg);
        box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
    }
    
    /* Footer Headings */
    .footer-heading {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
        flex-shrink: 0;
    }
    
    .footer-heading::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #FFD700, #FFA500);
        transition: width 0.4s ease;
        border-radius: 2px;
    }
    
    .footer-section:hover .footer-heading::after {
        width: 100%;
    }
    
    /* Links */
    .footer-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 8px 12px;
        border-radius: 8px;
        width: 100%;
    }
    
    .footer-link:hover {
        background: rgba(255, 215, 0, 0.08);
        transform: translateX(8px);
    }
    
    /* Contact Items */
    .contact-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: #f9fafb;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        border: 1px solid #f1f1f1;
        width: 100%;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .contact-item:hover {
        background: rgba(255, 215, 0, 0.08);
        transform: translateX(8px);
        border-color: rgba(255, 215, 0, 0.3);
    }
    
    .contact-item > div.text-left {
        flex: 1;
        min-width: 0;
    }
    
    /* Email specific styling */
    .email-text {
        word-break: break-all;
        overflow-wrap: anywhere;
        line-height: 1.4;
    }
    
    .contact-item:hover .email-text {
        word-break: break-word;
    }
    
    .contact-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        transition: all 0.3s ease;
        flex-shrink: 0;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }
    
    /* Email icon */
    .email-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
    }
    
    .contact-item:hover .contact-icon {
        background: linear-gradient(135deg, #000, #333);
        color: #FFD700;
        transform: scale(1.1);
    }
    
    /* Social Media Icons */
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .social-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, currentColor, transparent);
        opacity: 0.1;
    }
    
    .social-icon.facebook {
        background: #1877F2;
        color: white;
    }
    
    .social-icon.instagram {
        background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D);
        color: white;
    }
    
    .social-icon.twitter {
        background: #1DA1F2;
        color: white;
    }
    
    .social-icon.tiktok {
        background: #000000;
        color: white;
    }
    
    .social-icon:hover {
        transform: translateY(-4px) scale(1.15);
        box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
    }
    
    /* Policy Links */
    .policy-link {
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        padding: 4px 0;
    }
    
    .policy-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #FFD700, #FFA500);
        transition: width 0.3s ease;
        border-radius: 1px;
    }
    
    .policy-link:hover::after {
        width: 100%;
    }
    
    .separator {
        user-select: none;
    }
    
    /* Back to Top Button */
    .back-to-top-btn {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border: none;
        border-radius: 50%;
        color: #000;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }
    
    .back-to-top-btn.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    .back-to-top-btn:hover {
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 10px 30px rgba(255, 215, 0, 0.6);
    }
    
    .back-to-top-btn:active {
        transform: translateY(-2px) scale(1.05);
    }
    
    /* MAP SPECIFIC STYLES */
    .map-container {
        position: relative;
        margin-top: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .map-image-link {
        border-radius: 12px;
        overflow: hidden;
        display: block;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex: 1;
        position: relative;
    }
    
    .map-image-link img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .map-image-link:hover img {
        transform: scale(1.05);
    }
    
    /* Map hover effects */
    .map-image-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
        pointer-events: none;
    }
    
    .map-image-link:hover::before {
        opacity: 1;
    }
    
    /* Location pin animation */
    .map-container::after {
        content: '\f3c5';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        color: #FF0000;
        font-size: 2rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        z-index: 2;
        transition: transform 0.3s ease;
        pointer-events: none;
    }
    
    .map-container:hover::after {
        transform: translate(-50%, -50%) scale(1);
        animation: pinPulse 1.5s infinite;
    }
    
    @keyframes pinPulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
        50% {
            transform: translate(-50%, -50%) scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
    }
    
    /* Animations */
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-12px);
        }
    }
    
    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Hover Effect Base */
    .hover-effect {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-effect:hover {
        transform: translateY(-3px);
    }
    
    /* Special Animations for Icons */
    .group-hover\:rotate-12:hover i {
        transform: rotate(15deg);
    }
    
    .group-hover\:bounce:hover i {
        animation: bounce 0.6s ease;
    }
    
    .group-hover\:wobble:hover i {
        animation: wobble 0.6s ease;
    }
    
    .group-hover\:spin:hover i {
        animation: spin 0.6s ease;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    @keyframes wobble {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-8deg); }
        75% { transform: rotate(8deg); }
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .footer-section {
            text-align: center;
            padding: 20px 15px;
            min-height: auto !important;
        }
        
        .footer-section:nth-child(1) {
            min-height: 380px;
        }
        
        .contact-item {
            gap: 10px;
            padding: 12px;
        }
        
        .contact-item > div.text-left {
            text-align: left;
        }
        
        .policy-links .flex {
            flex-direction: column;
            gap: 10px;
        }
        
        .separator {
            display: none;
        }
        
        .social-links .flex {
            justify-content: center;
        }
        
        .section-icon {
            width: 55px;
            height: 55px;
            font-size: 1.2rem;
        }
        
        .contact-icon {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }
        
        .email-icon {
            width: 44px;
            height: 44px;
        }
        
        .email-text {
            font-size: 0.9rem;
            line-height: 1.3;
        }
        
        .map-image-link img {
            height: 160px;
        }
        
        .map-container::after {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 640px) {
        .contact-item {
            flex-direction: row;
            align-items: center;
            text-align: left;
        }
        
        .contact-item > div.text-left {
            width: 100%;
        }
        
        .contact-icon {
            align-self: center;
        }
        
        .fas.fa-chevron-right {
            position: static;
            transform: none;
        }
        
        .email-text {
            font-size: 0.85rem;
            word-break: break-word;
        }
        
        .map-image-link img {
            height: 140px;
        }
    }
    
    @media (max-width: 480px) {
        .grid.grid-cols-1.md\:grid-cols-3 {
            gap: 1.5rem;
        }
        
        .footer-section {
            padding: 15px;
        }
        
        .email-text {
            font-size: 0.8rem;
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
        }
        
        .map-image-link img {
            height: 130px;
        }
        
        .footer-section:nth-child(1) {
            min-height: 360px;
        }
    }
    
    /* Extra small screens */
    @media (max-width: 360px) {
        .email-text {
            font-size: 0.75rem;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .map-image-link img {
            height: 120px;
        }
    }
    
    /* Email hover expansion effect */
    .email-item:hover .email-text {
        white-space: normal;
        overflow: visible;
    }
    
    /* Grid alignment fixes */
    .grid.grid-cols-1.md\:grid-cols-3 {
        align-items: stretch;
    }
    
    /* Ripple effect for map */
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set current year
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Back to Top functionality
        const backToTopBtn = document.getElementById('back-to-top');
        
        function toggleBackToTop() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('visible');
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.classList.remove('visible');
                setTimeout(() => {
                    if (window.pageYOffset <= 300) {
                        backToTopBtn.style.display = 'none';
                    }
                }, 300);
            }
        }
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Show/hide back to top button based on scroll position
        window.addEventListener('scroll', toggleBackToTop);
        toggleBackToTop(); // Initial check
        
        // Load cart count for footer badge
        loadFooterCartCount();
        
        function loadFooterCartCount() {
            fetch('/api/cart/count')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.count > 0) {
                        const cartBadge = document.getElementById('footer-cart-badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.count;
                            cartBadge.classList.remove('hidden');
                            
                            // Add animation
                            cartBadge.style.animation = 'none';
                            setTimeout(() => {
                                cartBadge.style.animation = 'bounce 0.5s ease';
                            }, 10);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }
        
        // Email text expansion on hover
        const emailItem = document.querySelector('.email-item');
        const emailText = document.querySelector('.email-text');
        
        if (emailItem && emailText) {
            emailItem.addEventListener('mouseenter', function() {
                // Expand email text on hover
                emailText.style.whiteSpace = 'normal';
                emailText.style.overflow = 'visible';
                emailText.style.wordBreak = 'break-word';
            });
            
            emailItem.addEventListener('mouseleave', function() {
                // Reset on mouse leave
                setTimeout(() => {
                    emailText.style.whiteSpace = 'nowrap';
                    emailText.style.overflow = 'hidden';
                    emailText.style.textOverflow = 'ellipsis';
                }, 300);
            });
            
            // For mobile touch devices
            emailItem.addEventListener('touchstart', function(e) {
                e.preventDefault();
                emailText.style.whiteSpace = 'normal';
                emailText.style.overflow = 'visible';
                emailText.style.wordBreak = 'break-word';
            });
        }
        
        // Map hover effects and interactions
        const mapLink = document.querySelector('.map-image-link');
        const mapContainer = document.querySelector('.map-container');
        
        if (mapLink && mapContainer) {
            // Add click tracking and loading effect
            mapLink.addEventListener('click', function(e) {
                console.log('Map clicked - redirecting to Google Maps');
                
                // Optional: Add loading animation
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'absolute inset-0 bg-yellow-500/20 flex items-center justify-center z-10';
                loadingOverlay.innerHTML = `
                    <div class="text-yellow-600">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                        <p class="mt-2 text-sm font-semibold">Opening Google Maps...</p>
                    </div>
                `;
                
                this.style.position = 'relative';
                this.appendChild(loadingOverlay);
                
                // Remove overlay after 1.5 seconds
                setTimeout(() => {
                    if (loadingOverlay.parentNode) {
                        loadingOverlay.remove();
                    }
                }, 1500);
            });
            
            // Add keyboard navigation
            mapLink.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
            
            // Accessibility improvements
            mapLink.setAttribute('role', 'button');
            mapLink.setAttribute('tabindex', '0');
        }
        
        // Preload map image for better performance
        const mapImage = document.querySelector('.map-container img');
        if (mapImage) {
            const img = new Image();
            img.src = mapImage.src;
            img.onload = function() {
                mapImage.classList.add('loaded');
            };
        }
        
        // Add hover effects to all interactive elements
        const hoverElements = document.querySelectorAll('.hover-effect');
        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });
        
        // Add click animation to social icons
        const socialIcons = document.querySelectorAll('.social-icon');
        socialIcons.forEach(icon => {
            icon.addEventListener('click', function(e) {
                // Prevent default for demo icons without links
                if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
                    e.preventDefault();
                    this.style.animation = 'none';
                    setTimeout(() => {
                        this.style.animation = 'spin 0.5s ease';
                    }, 10);
                }
            });
        });
        
        // Get Directions link animation
        const directionsLink = document.querySelector('.map-link');
        if (directionsLink) {
            directionsLink.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transition = 'transform 0.3s ease';
                    icon.style.transform = 'rotate(45deg) scale(1.2)';
                }
            });
            
            directionsLink.addEventListener('mouseleave', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'rotate(0deg) scale(1)';
                }
            });
        }
        
        // Logo hover effect
        const logo = document.querySelector('.footer-logo h2');
        if (logo) {
            logo.addEventListener('mouseenter', function() {
                this.style.textShadow = '0 0 10px rgba(255, 215, 0, 0.5)';
                this.style.transition = 'text-shadow 0.3s ease';
            });
            
            logo.addEventListener('mouseleave', function() {
                this.style.textShadow = 'none';
            });
        }
        
        // Section animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.footer-section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(section);
        });
        
        // Add ripple effect to contact items
        document.querySelectorAll('.contact-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 215, 0, 0.2);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                    z-index: 1;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            /* Additional email text handling */
            @media (max-width: 768px) {
                .email-text {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                
                .email-item:hover .email-text,
                .email-item:active .email-text,
                .email-item:focus .email-text {
                    -webkit-line-clamp: unset;
                    display: block;
                }
            }
            
            /* Map image loading effect */
            .map-container img.loaded {
                animation: fadeIn 0.5s ease;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        
        // Adjust footer sections height on load
        function adjustFooterSections() {
            const footerSections = document.querySelectorAll('.footer-section');
            if (footerSections.length > 0) {
                // Find the tallest section
                let maxHeight = 0;
                footerSections.forEach(section => {
                    section.style.height = 'auto';
                    const height = section.offsetHeight;
                    if (height > maxHeight) maxHeight = height;
                });
                
                // Apply consistent height on desktop
                if (window.innerWidth >= 768) {
                    footerSections.forEach(section => {
                        section.style.height = maxHeight + 'px';
                    });
                } else {
                    footerSections.forEach(section => {
                        section.style.height = 'auto';
                    });
                }
            }
        }
        
        // Run on load and resize
        window.addEventListener('load', adjustFooterSections);
        window.addEventListener('resize', adjustFooterSections);
        adjustFooterSections();
        
        // Update cart count periodically
        setInterval(loadFooterCartCount, 30000); // Every 30 seconds
    });
</script>