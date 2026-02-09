<footer class="main-footer py-12">
    <div class="w-full">

        <div class="text-center mb-10 footer-logo">
            <h2 class="text-4xl cursive-font mb-3 text-gray-800">Villa Elena</h2>
            <p class="text-sm mb-4 tracking-widest text-gray-600">Family Resort & Agri-Tourism Farm</p>
            <p class="italic mb-10 tagline text-gray-700">"Relax. Reconnect. Recharge."</p>
            
    
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
        
        <div class="footer-content-wrapper w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full px-4 md:px-8">
                <div class="footer-section">
                    <div class="section-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="footer-heading text-gray-800 text-center">Find us here</h4>
                    
                 
                    <div class="mb-4 text-center">
                        <p class="text-sm mb-2 flex items-center justify-center text-gray-600">
                            <i class="fas fa-location-dot mr-2 text-yellow-500"></i>
                            Munoz, Philippines
                        </p>
                        <div class="flex justify-center">
                            <a href="https://maps.google.com/?q=Villa+Elena+Munoz+Philippines" 
                               target="_blank" 
                               class="map-link inline-flex items-center text-sm hover-effect text-yellow-600 hover:text-yellow-700 mb-4">
                                <i class="fas fa-directions mr-2"></i>
                                Get Directions
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="map-container mt-4 mx-auto" style="max-width: 280px;">
                        <a href="https://maps.google.com/?q=Villa+Elena+Munoz+Philippines" 
                           target="_blank" 
                           class="block map-image-link hover-effect group" 
                           aria-label="Open Villa Elena location in Google Maps">
                            <div class="relative overflow-hidden rounded-xl border-2 border-yellow-100 shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:border-yellow-300">
                                <img src="/images/map.png" 
                                     alt="Villa Elena Location Map in Munoz, Philippines" 
                                     class="w-full h-24 md:h-28 object-cover transition-transform duration-500 group-hover:scale-105">
                                
                              
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                               
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-yellow-500 text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 scale-0 group-hover:scale-100 shadow-lg">
                                    <i class="fas fa-search-plus text-lg"></i>
                                </div>
                                
                                
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-2 md:p-3 text-white">
                                    <p class="text-xs font-semibold">Click to view on Google Maps</p>
                                    <p class="text-xs opacity-90">Villa Elena, Munoz</p>
                                </div>
                            </div>
                        </a>
                        
                       
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Interactive map - Click to explore our location
                        </p>
                    </div>
                </div>
                
                
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
                
     
                <div class="footer-section">
                    <div class="section-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4 class="footer-heading text-gray-800">Contact Info</h4>
                    
                    <div class="space-y-4">
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
                        
                       
                        <div class="social-links mt-6">
                            <h5 class="text-sm font-medium mb-3 text-gray-800 text-center md:text-left">Follow us on</h5>
                            <div class="flex justify-center md:justify-start">
                                <a href="https://www.facebook.com/VillaElenaFamilyResort" 
                                   target="_blank" 
                                   class="social-icon facebook hover-effect"
                                   title="Follow us on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="text-center mt-12 pt-8 border-t border-gray-300">
            <p class="text-xs mb-4 text-gray-600">
                © <span id="current-year">2025</span> Villa Elena Resort. All rights reserved.
            </p>  
           
            <button id="back-to-top" 
                    class="back-to-top-btn hover-effect" 
                    aria-label="Back to top">
                <i class="fas fa-chevron-up"></i>
            </button>
        </div>
    </div>
</footer>

<style>

    .main-footer {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        position: relative;
        overflow: hidden;
        border-top: 1px solid #e5e7eb;
    }
    
    .footer-content-wrapper {
        background: transparent;
        border-radius: 0;
        padding: 0;
        box-shadow: none;
        border: none;
        position: relative;
    }
    
    .footer-content-wrapper::before {
        display: none;
    }
    

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
        inset: 0;
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
        width: 27px;
        height: 27px;
        background: linear-gradient(135deg, #8B4513, #A0522D);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
    }
    

    .footer-section {
        position: relative;
        padding: 0 15px;
        min-width: 0;
        margin-top: 40px;
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
    

    .footer-heading {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
        flex-shrink: 0;
        text-align: center;
        width: 100%;
    }
    
    .footer-heading::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #FFD700, #FFA500);
        transition: width 0.4s ease;
        border-radius: 2px;
    }
    
    .footer-section:hover .footer-heading::after {
        width: 60%;
    }
    

    .footer-link {
        display: flex;
        align-items: center;
        justify-content: center; 
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
    
    .email-text {
        word-break: break-all;
        overflow-wrap: anywhere;
        line-height: 1.4;
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
    
    .contact-item:hover .contact-icon {
        background: linear-gradient(135deg, #000, #333);
        color: #FFD700;
        transform: scale(1.1);
    }
    

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 1.3rem;
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
    
    .social-icon:hover {
        transform: translateY(-4px) scale(1.15);
        box-shadow: 0 8px 20px rgba(24, 119, 242, 0.4);
    }
    
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
    
    .map-container {
        position: relative;
        margin-top: auto;
    }
    
    .map-image-link {
        border-radius: 12px;
        overflow: hidden;
        display: block;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .map-image-link img {
        width: 100%;
        transition: transform 0.5s ease;
    }
    
    .map-image-link:hover img {
        transform: scale(1.05);
    }
    

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
    

    .hover-effect {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    

    @media (max-width: 768px) {
        .footer-section {
            padding: 20px 0;
            text-align: center;
        }
        
        .footer-section:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 30px;
            margin-bottom: 10px;
        }
        
        .section-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .footer-heading {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        
        
        .map-container {
            max-width: 250px;
            margin: 0 auto;
        }
        
        .map-image-link img {
            height: 100px;
        }
        
        
        .contact-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .contact-item {
            padding: 12px;
            gap: 10px;
        }
        
        .contact-item p {
            font-size: 0.85rem;
        }
        
        .email-text {
            font-size: 0.8rem;
        }
        
        
        .footer-link {
            padding: 10px;
            font-size: 0.9rem;
            justify-content: center;
        }
        
        .footer-link:hover {
            transform: translateX(0) scale(1.02);
        }
        
      
        .social-icon {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }
        
        .social-links h5 {
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 480px) {
        .main-footer {
            padding: 2rem 0;
        }
        
        .section-icon {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }
        
        .footer-heading {
            font-size: 1rem;
        }
        
        .map-image-link img {
            height: 90px;
        }
        
        .contact-icon {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
        
        .contact-item p {
            font-size: 0.8rem;
        }
        
        .email-text {
            font-size: 0.75rem;
        }
        
        .footer-link {
            font-size: 0.85rem;
        }
        
        .social-icon {
            width: 42px;
            height: 42px;
            font-size: 1.1rem;
        }
    }
    
   
    @media (min-width: 769px) {
        .footer-heading::after {
            left: 50%;
            transform: translateX(-50%);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
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
        
        window.addEventListener('scroll', toggleBackToTop);
        toggleBackToTop();
        
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
        
        // Update cart count periodically
        setInterval(loadFooterCartCount, 30000);
    });
</script>