<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Family Resort & Agri-Tourism Farm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-gold: #FFD700;
            --secondary-gold: #FFA500;
            --dark-bg: #000000;
            --light-bg: #FFFBF0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            background-color: var(--light-bg);
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px; /* Account for fixed navbar */
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--primary-gold), var(--secondary-gold));
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(var(--secondary-gold), var(--primary-gold));
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 700px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('/images/main-photo.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero-content h1 {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
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
        
        .btn-book {
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            color: var(--dark-bg);
            padding: 16px 45px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
        }
        
        .btn-book:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 215, 0, 0.4);
            color: var(--dark-bg);
        }
        
        .btn-book:active {
            transform: translateY(-1px) scale(1.02);
        }
        
        /* Floating Particles */
        .floating-particle {
            position: absolute;
            background: rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            animation: floatParticle linear infinite;
            z-index: 1;
        }
        
        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* About Section */
        .about-section {
            background: linear-gradient(135deg, #ffffff 0%, #FFF9E6 100%);
            position: relative;
            overflow: hidden;
            padding: 100px 0;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-gold), var(--secondary-gold));
            border-radius: 2px;
        }
        
        /* Animated Sunflowers */
        .sunflower-left, .sunflower-right {
            position: absolute;
            z-index: 1;
            opacity: 0.7;
            filter: drop-shadow(0 10px 20px rgba(255, 215, 0, 0.3));
        }
        
        .sunflower-left {
            top: 20%;
            left: -50px;
            animation: floatSunflower 8s ease-in-out infinite;
        }
        
        .sunflower-right {
            bottom: 20%;
            right: -50px;
            animation: floatSunflower 8s ease-in-out infinite 2s;
        }
        
        @keyframes floatSunflower {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            50% { 
                transform: translateY(-30px) rotate(15deg);
            }
        }
        
        /* Gallery Section */
        .gallery-section {
            padding: 100px 0;
            background: white;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .gallery-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            cursor: pointer;
        }
        
        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .gallery-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        /* Tour Section */
        .tour-section {
            position: relative;
            height: 700px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('/images/virtualtour.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .tour-content {
            max-width: 800px;
            padding: 0 20px;
            animation: fadeIn 1.5s ease-out;
        }
        
        .btn-explore {
            background: transparent;
            color: white;
            padding: 16px 45px;
            border: 2px solid white;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-explore:hover {
            background: white;
            color: var(--dark-bg);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
        }
        
        /* Facilities Section */
        .facilities-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #FFF9E6 0%, #FFFAF0 100%);
        }
        
        .facility-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            z-index: 1;
        }
        
        .facility-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-gold), var(--secondary-gold));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
            z-index: 2;
        }
        
        .facility-card:hover::before {
            transform: scaleX(1);
        }
        
        .facility-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .facility-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .facility-card:hover .facility-image {
            transform: scale(1.05);
        }
        
        .facility-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -30px auto 20px;
            position: relative;
            z-index: 3;
            color: var(--dark-bg);
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }
        
        /* Products Carousel */
        .products-section {
            padding: 100px 0;
            background: white;
        }
        
        .swiper {
            width: 100%;
            height: 600px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }
        
        .product-slide {
            display: flex;
            height: 100%;
        }
        
        .product-image {
            flex: 1;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .product-slide:hover .product-image img {
            transform: scale(1.05);
        }
        
        .product-content {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        /* Contact Section */
        .contact-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #FFF9E6 0%, #ffffff 100%);
        }
        
        .contact-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            transition: transform 0.4s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-10px);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover {
            background: rgba(255, 215, 0, 0.1);
            transform: translateX(5px);
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: var(--dark-bg);
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: white;
        }
        
        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .cta-title {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        /* Section Entrance Animations */
        .section-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .section-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Navigation Indicator */
        .nav-indicator {
            position: fixed;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1000;
            display: none;
        }
        
        @media (min-width: 1024px) {
            .nav-indicator {
                display: block;
            }
        }
        
        .nav-dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid transparent;
            border-radius: 50%;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-dot:hover {
            background: var(--primary-gold);
            transform: scale(1.3);
        }
        
        .nav-dot.active {
            background: transparent;
            border-color: var(--primary-gold);
            transform: scale(1.5);
        }
        
        .nav-dot::after {
            content: attr(data-title);
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-bg);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        
        .nav-dot:hover::after {
            opacity: 1;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .cta-title {
                font-size: 2.5rem;
            }
            
            .product-slide {
                flex-direction: column;
            }
            
            .swiper {
                height: auto;
            }
            
            .product-image {
                height: 300px;
            }
            
            .product-content {
                padding: 30px;
            }
            
            .sunflower-left, .sunflower-right {
                width: 150px;
            }
            
            .sunflower-left {
                left: -75px;
            }
            
            .sunflower-right {
                right: -75px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .btn-book, .btn-explore {
                padding: 14px 35px;
                font-size: 0.9rem;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-card {
                padding: 20px;
            }
            
            .facility-card {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    @include('customerFolder.partials.navbar')
    
    <!-- Navigation Indicator -->
    <div class="nav-indicator">
        <div class="nav-dot active" data-target="#home" data-title="Home"></div>
        <div class="nav-dot" data-target="#about" data-title="About"></div>
        <div class="nav-dot" data-target="#gallery" data-title="Gallery"></div>
        <div class="nav-dot" data-target="#activities" data-title="Activities"></div>
        <div class="nav-dot" data-target="#contact" data-title="Contact"></div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div id="particles-container"></div>
        <div class="hero-content text-center text-white">
            <h1 class="mb-4">Relax. Recharge. Reconnect at<br><span class="cursive-font">Villa Elena</span></h1>
            <a href="{{ route('roomBooking') }}" class="btn-book inline-block">Book Your Stay</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section section-animate">
        <div class="sunflower-left">
            <img src="/images/sunflower1.png" alt="Sunflower" class="w-64">
        </div>
        
        <div class="sunflower-right">
            <img src="/images/sunflower2.png" alt="Sunflower" class="w-64">
        </div>
        
        <div class="container mx-auto px-4 text-center max-w-4xl relative z-10">
            <h2 class="section-title text-4xl cursive-font mb-6 text-center w-full">About Villa Elena</h2>
            <p class="text-sm mb-6 tracking-wider text-gray-600">Cabisuculan, Science City of Munoz, Nueva Ecija 3119</p>
            
            <div class="text-center mb-8">
                <span class="text-4xl">🌻</span>
            </div>
            
            <p class="text-lg mb-4 text-gray-800 font-medium">Family Resort & Agri-Tourism Farm</p>
            
            <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                Discover the perfect blend of relaxation and nature at Villa Elena — a family-friendly resort nestled in the peaceful countryside of Nueva Ecija. Whether you come to unwind in our cozy cottages, explore our lush sunflower fields, or experience life on the farm, Villa Elena offers a refreshing escape for all.
            </p>
            
            <p class="text-gray-700 mb-6 leading-relaxed">
                Enjoy scenic views, fresh air, and meaningful experiences that connect you to both nature and community. From restful stays to interactive agri-tourism activities, every visit is designed to create memories that last.
            </p>
            
            <p class="text-gray-800 font-semibold text-lg italic mt-8">
                "Empowering families and communities to serve humanity."
            </p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery-section section-animate">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-4 section-title w-full text-center">Be surrounded by fields of sunshine.</h2>
            <p class="text-center text-gray-600 mb-12 text-lg max-w-2xl mx-auto">Our vibrant sunflowers offer the perfect backdrop for peace, joy, and family memories that last a lifetime.</p>
            
            <div class="gallery-grid mb-8">
                <div class="gallery-item">
                    <img src="/images/sunflower-gallery1.jpg" alt="Sunflower field 1" 
                         onerror="this.src='/images/golden-fields.jpg'">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white text-xl font-semibold">Golden Fields</h3>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="/images/sunflower-gallery2.jpg" alt="Sunflower field 2"
                         onerror="this.src='/images/sunsetglow.jpg'">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white text-xl font-semibold">Sunset Glow</h3>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="/images/sunflower-gallery3.jpg" alt="Sunflower field 3"
                         onerror="this.src='/images/nature-beauty.jpg'">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white text-xl font-semibold">Nature's Beauty</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour Section -->
    <section class="tour-section section-animate">
        <div class="tour-content text-center text-white">
            <h2 class="text-4xl font-bold mb-6 text-center">Take a Virtual Tour</h2>
            <p class="text-xl mb-8 leading-relaxed">Experience Villa Elena from anywhere in the world. Explore our resort grounds, cozy cottages, and vibrant agri-farm through our interactive 360° virtual tour.</p>
            <a href="{{ url('/virtual-tour/index.html') }}" class="btn-explore">
                <i class="fas fa-vr-cardboard mr-2"></i>
                Start Virtual Tour
            </a>
        </div>
    </section>

    <!-- Facilities Section -->
    <section id="activities" class="facilities-section section-animate">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-4 section-title w-full text-center">Enjoy Our Facilities</h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto text-lg">Experience a perfect blend of relaxation, adventure, and farm activities designed for the whole family.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Massage Therapy -->
                <div class="facility-card">
                    <img src="/images/massage.jpg" alt="Massage Therapy" class="facility-image"
                         onerror="this.src='https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=500&h=250&fit=crop'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Massage Therapy</h3>
                        <p class="text-gray-600 text-center">Test. Rejuvenate. Recharge. Our professional massage therapists provide the perfect escape from stress, helping restore balance to body and mind.</p>
                    </div>
                </div>
                
                <!-- Fishing -->
                <div class="facility-card">
                    <img src="/images/fishing.jpg" alt="Fishing" class="facility-image"
                         onerror="this.src='/images/fishing-area.jpg'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Fishing</h3>
                        <p class="text-gray-600 text-center">Unwind by our tranquil fishing pond. Perfect for beginners and experienced anglers alike, offering a peaceful escape surrounded by nature.</p>
                    </div>
                </div>
                
                <!-- Tree Hugging -->
                <div class="facility-card">
                    <img src="/images/tree-hugging.jpg" alt="Tree Hugging" class="facility-image"
                         onerror="this.src='/images/treehugging-area.jpg'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Tree Hugging</h3>
                        <p class="text-gray-600 text-center">Reconnect with nature through our guided tree hugging sessions. Experience the grounding power of our ancient trees.</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Swimming -->
                <div class="facility-card">
                    <img src="/images/swimming.jpg" alt="Swimming" class="facility-image"
                         onerror="this.src='/images/pool-area.jpg'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-swimming-pool"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Swimming Pool</h3>
                        <p class="text-gray-600 text-center">Take a refreshing dip in our crystal-clear swimming pool surrounded by lush greenery. Perfect for family fun and relaxation.</p>
                    </div>
                </div>
                
                <!-- Farm Tour -->
                <div class="facility-card">
                    <img src="/images/farm-tour.jpg" alt="Farm Tour" class="facility-image"
                         onerror="this.src='/images/contact-image.jpg'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Farm Tour</h3>
                        <p class="text-gray-600 text-center">Experience authentic farm life with our guided tours. Learn about sustainable agriculture and interact with farm animals.</p>
                    </div>
                </div>
                
                <!-- Picnic Areas -->
                <div class="facility-card">
                    <img src="/images/picnic.jpg" alt="Picnic Areas" class="facility-image"
                         onerror="this.src='/images/picnic-area.jpg'">
                    <div class="p-6">
                        <div class="facility-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Picnic Areas</h3>
                        <p class="text-gray-600 text-center">Enjoy delightful picnics with family and friends in our scenic picnic spots, perfect for creating lasting memories.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section section-animate">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 section-title w-full text-center">Villa Elena Products</h2>
            
            <!-- Swiper Carousel -->
            <div class="swiper">
                <div class="swiper-wrapper">
                    <!-- Product 1 -->
                    <div class="swiper-slide">
                        <div class="product-slide">
                            <div class="product-image">
                                <img src="/images/crafts.jpg" alt="Women's Hands Crafts"
                                     onerror="this.src='/images/womens-craft.jpg'">
                            </div>
                            <div class="product-content">
                                <h3 class="text-3xl font-bold mb-6">Women's Hands Crafts</h3>
                                <p class="text-gray-700 mb-6 text-lg leading-relaxed">
                                    Empowering local women artisans through our craft workshops and fair-trade partnerships. Each handcrafted piece tells a unique story of Filipino heritage, skill, and community empowerment.
                                </p>
                                <div class="flex items-center space-x-4">
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Handmade</span>
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Fair Trade</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product 2 -->
                    <div class="swiper-slide">
                        <div class="product-slide">
                            <div class="product-image">
                                <img src="/images/organic-produce.jpg" alt="Organic Produce"
                                     onerror="this.src='/images/organic-produce1.jpg'">
                            </div>
                            <div class="product-content">
                                <h3 class="text-3xl font-bold mb-6">Organic Produce</h3>
                                <p class="text-gray-700 mb-6 text-lg leading-relaxed">
                                    Fresh, organic fruits and vegetables grown right here at Villa Elena. Experience the true taste of farm-to-table freshness with our seasonal harvests, all cultivated using sustainable and eco-friendly farming practices.
                                </p>
                                <div class="flex items-center space-x-4">
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Organic</span>
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Farm-to-Table</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product 3 -->
                    <div class="swiper-slide">
                        <div class="product-slide">
                            <div class="product-image">
                                <img src="/images/sunflower-products.jpg" alt="Sunflower Products"
                                     onerror="this.src='/images/sunflower-products.jpg'">
                            </div>
                            <div class="product-content">
                                <h3 class="text-3xl font-bold mb-6">Sunflower Products</h3>
                                <p class="text-gray-700 mb-6 text-lg leading-relaxed">
                                    From premium sunflower oil to beautiful decorative items, our sunflower products capture the essence of Villa Elena. Each product is carefully crafted to bring the beauty, benefits, and sunshine of our fields into your home.
                                </p>
                                <div class="flex items-center space-x-4">
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Natural</span>
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Handcrafted</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section section-animate">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 section-title w-full text-center">Get In Touch With Us</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div>
                    <div class="contact-card">
                        <h3 class="text-2xl font-bold mb-8 text-center">Contact Information</h3>
                        
                        <div class="space-y-6">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Operating Hours</strong>
                                    <p>Monday - Sunday</p>
                                    <p class="text-yellow-600 font-semibold">8:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Resort Address</strong>
                                    <p>Cabisuculan, Science City of Munoz</p>
                                    <p>Nueva Ecija 3119, Philippines</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="far fa-envelope"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Email Address</strong>
                                    <a href="mailto:ebs_sunflower@yahoo.com" class="text-yellow-600 hover:text-yellow-700">
                                        ebs_sunflower@yahoo.com
                                    </a>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Phone Number</strong>
                                    <a href="tel:+639173010790" class="text-yellow-600 hover:text-yellow-700">
                                        0917-301-0790
                                    </a>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fab fa-facebook"></i>
                                </div>
                                <div>
                                    <strong class="block text-lg">Facebook Page</strong>
                                    <a href="https://www.facebook.com/VillaElenaFamilyResort" 
                                       target="_blank" 
                                       class="text-yellow-600 hover:text-yellow-700">
                                        Villa Elena Family Resort
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <img src="/images/contact.jpg" alt="Villa Elena Contact" 
                         class="rounded-2xl w-full h-full object-cover shadow-xl"
                         onerror="this.src='images/contact-image.jpg'">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section section-animate">
        <div class="cta-content">
            <h2 class="text-4xl font-bold mb-6 text-center">Ready for Your Perfect Getaway?</h2>
            <h3 class="cta-title cursive-font mb-8 text-center">Book Your Stay Today</h3>
            
            <p class="text-xl mb-10 text-gray-300 max-w-2xl mx-auto text-center">
                Experience the perfect blend of relaxation, nature, and Filipino hospitality at Villa Elena. Whether you're planning a family vacation, a romantic escape, or a special event, we're here to make it unforgettable.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('roomBooking') }}" class="btn-book">
                    <i class="fas fa-bed mr-2"></i>
                    Book a Room
                </a>
                <a href="{{ route('cottageBooking') }}" class="btn-book">
                    <i class="fas fa-home mr-2"></i>
                    Book a Cottage
                </a>
            </div>
            
            <p class="text-sm text-gray-400 mt-10 max-w-xl mx-auto text-center">
                For special events like weddings, birthdays, or corporate retreats, please contact us directly through our phone number for personalized assistance.
            </p>
        </div>
    </section>

    @include('customerFolder.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        const swiper = new Swiper('.swiper', {
            direction: 'horizontal',
            loop: true,
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });

        // Create floating particles for hero section
        function createParticles() {
            const container = document.getElementById('particles-container');
            if (!container) return;
            
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.classList.add('floating-particle');
                
                // Random properties
                const size = Math.random() * 20 + 5;
                const duration = Math.random() * 10 + 10;
                const delay = Math.random() * 5;
                const left = Math.random() * 100;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${left}%`;
                particle.style.animationDuration = `${duration}s`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.background = `rgba(255, ${Math.random() * 100 + 155}, 0, ${Math.random() * 0.3 + 0.2})`;
                
                container.appendChild(particle);
            }
        }

        // Smooth section navigation
        function initSmoothNavigation() {
            const navDots = document.querySelectorAll('.nav-dot');
            const sections = document.querySelectorAll('section[id]');
            
            // Update active nav dot on scroll
            function updateActiveNavDot() {
                let currentSection = '';
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    const sectionHeight = section.clientHeight;
                    
                    if (window.pageYOffset >= sectionTop && 
                        window.pageYOffset < sectionTop + sectionHeight) {
                        currentSection = section.getAttribute('id');
                    }
                });
                
                navDots.forEach(dot => {
                    dot.classList.remove('active');
                    if (dot.getAttribute('data-target') === `#${currentSection}`) {
                        dot.classList.add('active');
                    }
                });
            }
            
            // Smooth scroll to section
            function scrollToSection(targetId) {
                const targetSection = document.querySelector(targetId);
                if (targetSection) {
                    const headerOffset = 80;
                    const elementPosition = targetSection.offsetTop;
                    const offsetPosition = elementPosition - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }
            
            // Nav dot click events
            navDots.forEach(dot => {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('data-target');
                    scrollToSection(target);
                    
                    // Update active dot
                    navDots.forEach(d => d.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Update on scroll
            window.addEventListener('scroll', updateActiveNavDot);
            
            // Initial update
            updateActiveNavDot();
        }

        // Section entrance animations
        function initSectionAnimations() {
            const animatedSections = document.querySelectorAll('.section-animate');
            
            function checkScroll() {
                animatedSections.forEach(section => {
                    const sectionTop = section.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (sectionTop < windowHeight * 0.85) {
                        section.classList.add('visible');
                    }
                });
            }
            
            window.addEventListener('scroll', checkScroll);
            window.addEventListener('load', checkScroll);
            checkScroll(); // Initial check
        }

        // Virtual tour function
        function openVirtualTour() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/90 z-[9999] flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                        <div class="flex justify-between items-center p-4 border-b">
                            <h3 class="text-xl font-bold">Virtual Tour - Villa Elena</h3>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="text-2xl hover:text-red-500 transition-colors">
                                &times;
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="aspect-video bg-gray-800 rounded-lg flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="fas fa-vr-cardboard text-6xl mb-4"></i>
                                    <p class="text-xl">Virtual Tour Coming Soon!</p>
                                    <p class="text-gray-400 mt-2">Experience Villa Elena in 360°</p>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-600 text-center">
                                Our immersive virtual tour is currently under development. 
                                Check back soon to explore Villa Elena from anywhere in the world!
                            </p>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            initSmoothNavigation();
            initSectionAnimations();
            
            // Add smooth scroll to anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;
                    
                    e.preventDefault();
                    const targetSection = document.querySelector(href);
                    if (targetSection) {
                        const headerOffset = 80;
                        const elementPosition = targetSection.offsetTop;
                        const offsetPosition = elementPosition - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Close mobile menu if open
                        const mobileMenu = document.getElementById('mobile-menu');
                        const menuOverlay = document.getElementById('menu-overlay');
                        if (mobileMenu && mobileMenu.classList.contains('active')) {
                            mobileMenu.classList.remove('active');
                            if (menuOverlay) menuOverlay.classList.remove('active');
                            document.body.style.overflow = 'auto';
                        }
                    }
                });
            });
            
            // Parallax effect for hero section
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero-section');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
            });
            
            // Add hover effect to facility cards
            document.querySelectorAll('.facility-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                e.preventDefault();
                const sections = Array.from(document.querySelectorAll('section[id]'));
                const currentScroll = window.pageYOffset + 100;
                
                let targetIndex = 0;
                let minDistance = Infinity;
                
                sections.forEach((section, index) => {
                    const distance = Math.abs(section.offsetTop - currentScroll);
                    if (distance < minDistance) {
                        minDistance = distance;
                        targetIndex = index;
                    }
                });
                
                if (e.key === 'ArrowDown' && targetIndex < sections.length - 1) {
                    targetIndex++;
                } else if (e.key === 'ArrowUp' && targetIndex > 0) {
                    targetIndex--;
                }
                
                const targetSection = sections[targetIndex];
                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            }
        });
    </script>
</body>
</html>