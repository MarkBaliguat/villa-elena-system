<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        
        /* Hero Section for About */
        .about-hero {
            position: relative;
            height: 60vh;
            min-height: 500px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('/images/pool-area.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .about-hero-content {
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
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
        
        /* Animated Sunflowers for Our Story Section */
        .sunflower-ourstory-left, .sunflower-ourstory-right {
            position: absolute;
            z-index: 1;
            opacity: 0.7;
            filter: drop-shadow(0 10px 20px rgba(255, 215, 0, 0.3));
            pointer-events: none;
        }
        
        .sunflower-ourstory-left {
            top: 10%;
            left: -50px;
            animation: floatSunflower 8s ease-in-out infinite;
        }
        
        .sunflower-ourstory-right {
            bottom: 10%;
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
        
        /* Animated Sunflowers for Mission/Vision Section */
        .sunflower-mission-left, .sunflower-mission-right {
            position: absolute;
            z-index: 1;
            opacity: 0.7;
            filter: drop-shadow(0 10px 20px rgba(255, 215, 0, 0.3));
            pointer-events: none;
        }
        
        .sunflower-mission-left {
            top: 10%;
            left: -50px;
            animation: floatSunflowerReverse 8s ease-in-out infinite;
        }
        
        .sunflower-mission-right {
            bottom: 10%;
            right: -50px;
            animation: floatSunflowerReverse 8s ease-in-out infinite 2s;
        }
        
        @keyframes floatSunflowerReverse {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            50% { 
                transform: translateY(-30px) rotate(-15deg);
            }
        }
        
        /* Section Animations */
        .section-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .section-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Feature Card Hover Effects */
        .feature-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            z-index: 1;
        }
        
        .feature-card::before {
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
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--dark-bg);
            font-size: 1.8rem;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Mission & Vision Cards */
        .mission-vision-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            height: 100%;
            z-index: 2;
        }
        
        .mission-vision-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), rgba(255, 165, 0, 0.05));
            z-index: 0;
        }
        
        .mission-vision-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        }
        
        /* Image Container - FIXED FOR LANDSCAPE */
        .image-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            z-index: 2;
        }
        
        /* Landscape-specific styles */
        .landscape-image-container {
            width: 100%;
            height: 350px; /* Fixed height for landscape */
        }
        
        .landscape-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .image-container:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .image-container:hover img {
            transform: scale(1.1);
        }
        
        /* History Timeline */
        .timeline-item {
            position: relative;
            padding-left: 60px;
            margin-bottom: 40px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: -40px;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary-gold), var(--secondary-gold));
        }
        
        .timeline-item:last-child::before {
            bottom: 0;
        }
        
        .timeline-dot {
            position: absolute;
            left: 15px;
            top: 0;
            width: 15px;
            height: 15px;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            border-radius: 50%;
            z-index: 2;
        }
        
        /* CTA Section */
        .cta-about {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            position: relative;
            overflow: hidden;
        }
        
        .cta-about::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
            animation: pulse 8s infinite alternate;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }
            100% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }
        
        .btn-about {
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
        
        .btn-about:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 215, 0, 0.4);
            color: var(--dark-bg);
        }
        
        .btn-about:active {
            transform: translateY(-1px) scale(1.02);
        }
        
        /* Our Story Section */
        .our-story-section {
            position: relative;
            overflow: hidden;
            padding: 80px 0;
            background: linear-gradient(to top, #FFF9E6 0%,#FFF9E6 70%,#ffffff 100%);
        }
        
        
        .our-story-content {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Mission/Vision Section */
        .mission-vision-section {
            position: relative;
            overflow: hidden;
            padding: 80px 0;
            background: linear-gradient(to bottom, #FFF9E6 0%,#FFF9E6 30%,#ffffff 100%);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .about-hero {
                height: 50vh;
                min-height: 400px;
            }
            
            .sunflower-ourstory-left, .sunflower-ourstory-right,
            .sunflower-mission-left, .sunflower-mission-right {
                width: 120px;
            }
            
            .sunflower-ourstory-left, .sunflower-mission-left {
                left: -60px;
            }
            
            .sunflower-ourstory-right, .sunflower-mission-right {
                right: -60px;
            }
            
            .mission-vision-card {
                padding: 30px;
            }
            
            .landscape-image-container {
                height: 250px;
            }
            
            .our-story-content {
                padding: 30px;
            }
        }
        
        @media (max-width: 480px) {
            .about-hero {
                height: 40vh;
                min-height: 300px;
            }
            
            .sunflower-ourstory-left, .sunflower-ourstory-right,
            .sunflower-mission-left, .sunflower-mission-right {
                display: none;
            }
            
            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .btn-about {
                padding: 14px 35px;
                font-size: 0.9rem;
            }
            
            .landscape-image-container {
                height: 200px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('customerFolder.partials.navbar')
    
    <!-- Hero Section -->
    <section class="about-hero">
        <div id="particles-container"></div>
        <div class="about-hero-content text-center text-white px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">About Villa Elena</h1>
                    <!-- <a href="{{ url('/virtual-tour/index.html') }}?panorama=22papaya.jpg" class="inline-block px-6 py-3 bg-[#ff6b35] hover:bg-[#ff824e] text-white rounded-sm text-lg font-medium transition-all duration-300">
                        Go Directly to Test Panorama
                    </a> -->
            <p class="text-xl md:text-2xl max-w-3xl mx-auto leading-relaxed">
                A sanctuary where nature, family, and heartfelt hospitality come together
            </p>
        </div>
    </section>

    <main class="pt-0">
        <!-- About Us / Our Story Section with Sunflowers -->
        <section class="our-story-section section-animate">
            <!-- Sunflowers -->
            <div class="sunflower-ourstory-left">
                <img src="/images/sunflower1.png" alt="Sunflower" class="w-48" onerror="this.style.display='none'">
            </div>
            
            <div class="sunflower-ourstory-right">
                <img src="/images/sunflower2.png" alt="Sunflower" class="w-48" onerror="this.style.display='none'">
            </div>
            
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <!-- Landscape Photo -->
                        <div class="image-container landscape-image-container">
                            <img src="/images/our-story.jpg" 
                                 alt="Villa Elena Resort" class="w-full h-full object-cover rounded-3xl">
                        </div>
                        
                        <!-- Our Story Content -->
                        <div class="our-story-content">
                            <h2 class="text-4xl font-bold mb-6 text-gray-800">Our Story</h2>
                            <div class="space-y-6">
                                <p class="text-gray-700 leading-relaxed text-lg">
                                    Nestled in a tranquil setting surrounded by nature, Villa Elena Resort is your 
                                    perfect escape from the hustle and bustle of everyday life. Founded with the 
                                    vision of creating a peaceful haven for families, friends, and travelers, our 
                                    resort offers a relaxing blend of comfort, beauty, and warm hospitality.
                                </p>
                                <p class="text-gray-700 leading-relaxed">
                                    What started as a family dream has blossomed into a destination where memories 
                                    are made, bonds are strengthened, and the simple joys of life are celebrated 
                                    amidst golden sunflower fields and serene landscapes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Sections -->
        <section class="mission-vision-section">
            <!-- Sunflowers for Mission/Vision Section -->
            
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- Mission -->
                    <div class="mb-20 section-animate">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="mission-vision-card">
                                <div class="relative z-10">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-bullseye text-white text-xl"></i>
                                        </div>
                                        <h2 class="text-3xl font-bold text-gray-800">OUR MISSION</h2>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed text-lg">
                                        "To provide our guests with a relaxing and memorable escape through exceptional hospitality, a serene natural environment, and heartfelt service—by creating a peaceful sanctuary where every moment feels like a retreat from the ordinary."
                                    </p>
                                </div>
                            </div>
                            <!-- LANDSCAPE PHOTO FOR MISSION -->
                            <div class="image-container landscape-image-container order-first md:order-last">
                                <img src="/images/mission-pic2.jpg" 
                                     alt="Villa Elena Mission" class="w-full h-full object-cover rounded-3xl">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vision -->
                    <div class="section-animate">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <!-- LANDSCAPE PHOTO FOR VISION -->
                            <div class="image-container landscape-image-container">
                                <img src="/images/contact.jpg" 
                                     alt="Villa Elena Vision" class="w-full h-full object-cover rounded-3xl">
                            </div>
                            <div class="mission-vision-card">
                                <div class="relative z-10">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-eye text-white text-xl"></i>
                                        </div>
                                        <h2 class="text-3xl font-bold text-gray-800">OUR VISION</h2>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed text-lg">
                                        To be a premier destination for rest and recreation, known for our serene environment, exceptional service, and heartfelt hospitality — inspiring lasting memories and meaningful connections for every guest we welcome.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- History Section -->
        <section class="py-16 bg-gradient-to-b from-white to-yellow-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16 section-animate">
                        <h2 class="text-4xl font-bold mb-4 text-gray-800">Our Journey</h2>
                        <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                            From a family's dream to a beloved destination, discover the story behind Villa Elena
                        </p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-12 items-center section-animate">
                        <div>
                            <div class="mb-8">
                                <h3 class="text-3xl font-bold mb-6 flex items-center text-gray-800">
                                    <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-3 rounded-full mr-4">
                                        <i class="fas fa-history"></i>
                                    </span>
                                    HISTORY OF VILLA ELENA
                                </h3>
                            </div>
                            
                            <div class="space-y-8">
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <h4 class="text-xl font-bold mb-2 text-gray-800">2018: The Beginning</h4>
                                    <p class="text-gray-700">
                                        Villa Elena opened in 2018, a family project envisioned to provide 
                                        venues and opportunities for families and community groups to come 
                                        together. It was initially conceived as a way to connect people with one 
                                        another—with the goal of building stronger families and communities.
                                    </p>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <h4 class="text-xl font-bold mb-2 text-gray-800">The Name's Origin</h4>
                                    <p class="text-gray-700">
                                        The name "Villa Elena" was chosen as a tribute to their beloved matriarch, 
                                        whose warmth and care are reflected in every corner of the resort.
                                    </p>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <h4 class="text-xl font-bold mb-2 text-gray-800">A Tribute to Elena</h4>
                                    <p class="text-gray-700">
                                        <strong>VILLA ELENA is a tribute to ELENA</strong>—a woman who dedicated her life to 
                                        nurturing her family and community, instilling them with care and 
                                        responsibility.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="image-container landscape-image-container">
                            <img src="/images/elena-history.jpg" 
                                 alt="Villa Elena History" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    </div>
                    
                    <div class="mt-12 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-3xl p-8 border border-yellow-200 section-animate">
                        <div class="text-center">
                            <i class="fas fa-heart text-4xl text-yellow-600 mb-4"></i>
                            <p class="text-gray-700 text-lg italic">
                                VILLA ELENA stands on the farmland where Elena and Alejandro 
                                shared their first year of married life together—a testament to love, family, and enduring legacy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Villa Elena Section -->
        <section class="py-16 bg-gradient-to-t from-white to-yellow-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 section-animate">
                    <h2 class="text-4xl font-bold mb-4 text-gray-800">Why Choose Villa Elena?</h2>
                    <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                        Discover what makes our resort a truly special destination for relaxation and connection
                    </p>
                </div>
                
                <div class="max-w-6xl mx-auto">
                    <!-- First Row -->
                    <div class="grid md:grid-cols-3 gap-8 mb-8">
                        <!-- Sunflower Farm -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">🌻</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Sunflower Farm</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Experience the beauty of our 1-acre sunflower fields, perfect for photography and peaceful walks among golden blooms.
                            </p>
                        </div>

                        <!-- Eco-Friendly -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">🌿</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Eco-Friendly</h3>
                            <p class="text-gray-600 leading-relaxed">
                                At Villa Elena, we're building "Basura"—a creative and eco-friendly space made from materials used in the resort's DIY upcycling of recycled waste.
                            </p>
                        </div>

                        <!-- Support Womens Craft -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">👩‍🎨</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Support Womens Craft</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Empower local women artisans through our craft workshop and fair-trade partnerships featuring traditional Filipino handicrafts.
                            </p>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Wellness -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">🧘</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Wellness</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Enjoy holistic experiences including massage therapy, rejuvenating yoga and Zumba sessions, and relaxing breathwork exercises.
                            </p>
                        </div>

                        <!-- Farm to Table -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">🍽️</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Farm to table</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Enjoy fresh, organic produce and sustainably sourced fish served at Villa Elena Family Resort—straight from the farm into plates to your table.
                            </p>
                        </div>

                        <!-- Activities -->
                        <div class="feature-card p-8 text-center">
                            <div class="feature-icon">
                                <span class="text-3xl">🎯</span>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-gray-800">Activities</h3>
                            <p class="text-gray-600 leading-relaxed">
                                You can enjoy a variety of activities including tree hugging, fishing, grounding, sunrise and sunset viewing, and more.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-about py-16 relative overflow-hidden">
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl p-12 border border-yellow-200 section-animate">
                    <h2 class="text-4xl font-bold mb-6">Ready to Experience Villa Elena?</h2>
                    <p class="text-gray-700 text-lg mb-8">
                        Join thousands of satisfied guests who have made Villa Elena their preferred destination for luxury and relaxation.
                    </p>
                    <a href="{{ route('roomBooking') }}"  class="btn-about inline-block">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Book Your Stay Now
                    </a>
                    <p class="text-sm text-gray-600 mt-6">
                        For special events and group bookings, contact us directly at 
                        <a href="tel:+639173010790" class="text-yellow-600 font-semibold">0917-301-0790</a>
                    </p>
                </div>
            </div>
        </section>
    </main>

    @include('customerFolder.partials.footer')

    <script>
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

        // Add hover effects to feature cards
        function initCardInteractions() {
            const cards = document.querySelectorAll('.feature-card, .mission-vision-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
        }

        // Parallax effect for hero section
        function initParallax() {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.about-hero');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            initSectionAnimations();
            initCardInteractions();
            initParallax();
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Handle missing sunflower images
            const sunflowerImages = document.querySelectorAll('.sunflower-ourstory-left img, .sunflower-ourstory-right img, .sunflower-mission-left img, .sunflower-mission-right img');
            sunflowerImages.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
                
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            });
            
            // Add loading animation to all images
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                e.preventDefault();
                const sections = Array.from(document.querySelectorAll('section'));
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