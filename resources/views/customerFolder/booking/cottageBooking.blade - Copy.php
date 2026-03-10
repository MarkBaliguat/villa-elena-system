<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Elena - Cottage Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-black: #000000;
            --primary-yellow: #FFD700;
            --secondary-yellow: #FFA500;
            --light-bg: #FFFBF0;
            --card-bg: #FFFFFF;
            --text-dark: #1F2937;
            --text-medium: #6B7280;
            --text-light: #9CA3AF;
            --border-color: #E5E7EB;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            background-color: var(--light-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        .main-content {
            margin-top: 0;
            min-height: calc(100vh - 300px);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 500px;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), 
                        url('/images/pool-area.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
            max-width: 1200px;
            width: 100%;
        }

        /* Booking Card - IMPROVED */
        .booking-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: 3rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
        }

        /* Form Elements */
        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: white;
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
            height: 48px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
        }

        .form-input:hover {
            border-color: #D1D5DB;
        }

        .form-input:disabled {
            background-color: #f8f9fa;
            color: var(--text-light);
            cursor: not-allowed;
            opacity: 0.8;
        }

        /* Info Badge */
        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 0.25rem;
            animation: gentlePulse 2s ease-in-out infinite;
        }

        @keyframes gentlePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Search Button - ALIGNED */
        .search-btn {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-btn:active {
            transform: translateY(-1px);
        }

        .search-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .search-btn:hover::after {
            left: 100%;
        }

        /* Accommodation Section */
        .accommodation-section {
            padding: 5rem 0;
            background: linear-gradient(to bottom, var(--light-bg), #FFFFFF);
        }

        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            /* background: linear-gradient(135deg, var(--text-dark), #4B5563); */
            background: #1F2937;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-medium);
            max-width: 700px;
            margin: 0 auto 4rem;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        /* Tabs Navigation */
        .tabs-container {
            display: flex;
            justify-content: center;
            margin-bottom: 3.5rem;
            position: relative;
        }

        .tab-nav {
            display: flex;
            background: white;
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-medium);
            background: transparent;
            border: none;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .tab-btn:hover {
            color: var(--text-dark);
            background: rgba(255, 215, 0, 0.05);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.3);
        }

        .tab-btn i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .tab-btn:hover i {
            transform: scale(1.1);
        }

        .tab-btn.active i {
            transform: scale(1.1);
        }

        /* Accommodation Cards - Horizontal Layout */
        .accommodation-cards {
            display: flex;
            flex-direction: column;
            gap: 3rem;
            margin-top: 2rem;
        }

        .accommodation-card {
            background: var(--card-bg);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: grid;
            grid-template-columns: 45% 1fr;
            gap: 0;
            min-height: 400px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .accommodation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            border-color: rgba(255, 215, 0, 0.3);
        }

        /* Image Gallery Section */
        .card-image-container {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.03), rgba(255, 165, 0, 0.03));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .image-gallery {
            position: relative;
            width: 100%;
            height: 100%;
            aspect-ratio: 4 / 3;
        }

        .gallery-main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
            cursor: pointer;
            transition: transform 0.4s ease;
        }

        .gallery-main-image:hover {
            transform: scale(1.02);
        }

        .gallery-thumbnails {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            padding: 10px 14px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .thumbnail {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            opacity: 0.5;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .thumbnail:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .thumbnail.active {
            opacity: 1;
            border-color: var(--primary-yellow);
            transform: scale(1.1);
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .gallery-nav:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: translateY(-50%) scale(1.1);
        }

        .gallery-nav.prev {
            left: 15px;
        }

        .gallery-nav.next {
            right: 15px;
        }

        /* Image Placeholder Style */
        .card-image-placeholder {
            width: 100%;
            height: 100%;
            min-height: 370px;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-medium);
            border-radius: 16px;
        }

        .card-image-placeholder i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--primary-yellow);
            opacity: 0.5;
        }

        .card-image-placeholder p {
            font-size: 1rem;
            font-weight: 500;
        }

        .card-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
            padding: 8px 20px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            z-index: 3;
        }

        /* Card Content Section */
        .card-content {
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-header {
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .card-description {
            color: var(--text-medium);
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .feature-tag {
            background: rgba(255, 215, 0, 0.1);
            color: var(--text-dark);
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 500;
            border: 1px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .feature-tag:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateY(-2px);
        }

        .card-price-section {
            margin-bottom: 2rem;
        }

        .card-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .price-period {
            font-size: 1.1rem;
            color: var(--text-medium);
            font-weight: 500;
        }

        /* Button Section */
        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .action-btn {
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-btn:active {
            transform: translateY(-1px);
        }

        .btn-virtual-tour {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #FFFBF0, #FFF8E1);
            color: var(--primary-black);
            border: 2px solid var(--primary-yellow);
            font-size: 1.05rem;
        }

        .btn-virtual-tour:hover {
            background: linear-gradient(135deg, #FFF8E1, #FFECB3);
            border-color: var(--secondary-yellow);
        }

        /* Disabled Virtual Tour Button Styling */
        .btn-virtual-tour.virtual-tour-disabled {
            background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
            color: #9CA3AF;
            border: 2px solid #E5E7EB;
            cursor: pointer;
            opacity: 0.7;
        }

        .btn-virtual-tour.virtual-tour-disabled:hover {
            background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-cart {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
        }

        .btn-cart:hover {
            background: linear-gradient(135deg, #FFC800, #FF9500);
        }

        .btn-book {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
            color: white;
        }

        .btn-book:hover {
            background: linear-gradient(135deg, #2D3748, var(--primary-black));
        }

        /* Image Zoom Modal */
        .image-zoom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .image-zoom-modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .zoom-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .zoom-image {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .zoom-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }

        .zoom-nav:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 215, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }

        .zoom-nav.prev {
            left: 30px;
        }

        .zoom-nav.next {
            right: 30px;
        }

        .zoom-close {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }

        .zoom-close:hover {
            background: rgba(255, 69, 58, 0.8);
            border-color: rgba(255, 69, 58, 1);
            transform: scale(1.1);
        }

        .zoom-counter {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Loading Spinner */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            min-height: 300px;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 215, 0, 0.2);
            border-top-color: var(--primary-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1.5rem;
        }

        .loading-text {
            color: var(--text-medium);
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-icon {
            font-size: 4rem;
            color: rgba(255, 215, 0, 0.3);
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .empty-description {
            color: var(--text-medium);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Notification System */
        .notification {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 9999;
            padding: 20px 25px;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 400px;
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }

        .notification.error {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
        }

        .notification.info {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: white;
        }

        .notification.warning {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: white;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 1rem;
        }

        .notification-message {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.5;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: opacity 0.3s;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #FFC800, #FF9500);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .accommodation-card {
                grid-template-columns: 50% 1fr;
            }

            .card-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 992px) {
            .hero-section {
                height: 90vh;
                min-height: 450px;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .booking-card {
                padding: 1.75rem;
            }
            
            .tab-btn {
                padding: 14px 30px;
                font-size: 1rem;
            }

            .accommodation-card {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .card-content {
                padding: 2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 110vh;
                min-height: 400px;
                background-attachment: scroll;
            }
            
            .section-title {
                font-size: 2.25rem;
            }
            
            .booking-card {
                padding: 1.5rem;
                margin-top: 2rem;
            }
            
            .tab-nav {
                flex-direction: column;
                gap: 8px;
                padding: 12px;
            }
            
            .tab-btn {
                width: 100%;
                justify-content: center;
            }
            
            .accommodation-cards {
                gap: 2rem;
            }

            .card-title {
                font-size: 1.75rem;
            }

            .card-price {
                font-size: 2rem;
            }
            
            .card-actions {
                grid-template-columns: 1fr;
            }

            .btn-virtual-tour {
                grid-column: 1;
            }

            .zoom-nav {
                width: 50px;
                height: 50px;
            }

            .zoom-nav.prev {
                left: 15px;
            }

            .zoom-nav.next {
                right: 15px;
            }

            .zoom-close {
                width: 45px;
                height: 45px;
                top: 15px;
                right: 15px;
            }
            
            .notification {
                left: 20px;
                right: 20px;
                max-width: none;
                top: 80px;
            }

            .gallery-thumbnails {
                bottom: 10px;
                padding: 8px 10px;
            }

            .thumbnail {
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 1rem;
                height: 110vh;
                min-height: 450px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .booking-card {
                padding: 1.25rem;
            }
            
            .form-input {
                padding: 10px 12px;
                height: 44px;
            }
            
            .search-btn {
                padding: 10px 20px;
                height: 44px;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .card-price {
                font-size: 1.75rem;
            }

            .card-content {
                padding: 1.5rem;
            }

            .image-gallery {
                min-height: 280px;
            }

            .card-image-placeholder {
                min-height: 280px;
            }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('customerFolder.partials.navbar')
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 fade-in">Discover Our Cottages</h1>
                <p class="text-xl md:text-2xl mb-8 fade-in" style="animation-delay: 0.2s;">Perfect for day trips and family gatherings</p>
                
                <!-- Booking Card -->
                <div class="booking-card fade-in" style="animation-delay: 0.4s;">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Book Your Cottage</h3>
                    
                    <form id="bookingForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        
                        <!-- Check-in Date -->
                        <div class="form-group">
                            <label class="form-label">Booking Date</label>
                            <input type="date" name="check_in" id="check_in" class="form-input" required>
                        </div>
                        
                        <!-- Check-out Date (Auto-set to same day) -->
                        <div class="form-group">
                            <label class="form-label">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out" class="form-input" disabled>
                            <div class="info-badge">
                                <i class="fas fa-info-circle"></i>
                                Same day booking
                            </div>
                        </div>
                        
                        <!-- Number of Guests -->
                        <div class="form-group">
                            <label class="form-label">Number of Guests</label>
                            <input type="number" name="guests" id="guest-number" class="form-input" value="1" min="1" max="40" required>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-group">
                            <label class="form-label" style="opacity: 0;">Search</label>
                            <button type="button" id="searchBtn" class="search-btn">
                                <i class="fas fa-search"></i>
                                Search Cottages
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Accommodation Section -->
        <section class="accommodation-section">
            <div class="container mx-auto px-4 md:px-6">
                <div class="text-center mb-12">
                    <h2 class="section-title">Our Cottages</h2>
                    <p class="section-subtitle">
                        Explore our selection of beautifully designed cottages, perfect for day trips, 
                        family gatherings, and memorable outdoor experiences.
                    </p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tabs-container">
                    <div class="tab-nav">
                        <a href="{{ route('roomBooking') }}" id="room-tab" class="tab-btn">
                            <i class="fas fa-bed"></i>
                            <span>Rooms</span>
                        </a>
                        <a href="{{ route('cottageBooking') }}" id="cottage-tab" class="tab-btn active">
                            <i class="fas fa-home"></i>
                            <span>Cottages</span>
                        </a>
                    </div>
                </div>

                <!-- Cottages Content -->
                <div id="cottages-content" class="accommodation-content active">
                    <div class="loading-container" id="cottages-loading" style="display: none;">
                        <div class="loading-spinner"></div>
                        <p class="loading-text">Loading available cottages...</p>
                    </div>
                    <div id="cottages-container" class="accommodation-cards fade-in">
                        <!-- Cottages will be loaded here -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageZoomModal" class="image-zoom-modal">
        <button class="zoom-close" onclick="closeZoomModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="zoom-content">
            <button class="zoom-nav prev" onclick="changeZoomImage(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <img id="zoomImage" class="zoom-image" src="" alt="Zoomed image">
            <button class="zoom-nav next" onclick="changeZoomImage(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="zoom-counter" id="zoomCounter">1 / 3</div>
    </div>

    <!-- Include Footer -->
    @include('customerFolder.partials.footer')

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <script>
        // Global variables
        let currentCheckIn = '';
        let currentCheckOut = '';
        let currentGuests = 1;
        let cartItemCount = 0;
        let cartItems = [];
        let cartDates = { checkIn: '', checkOut: '' };
        let currentZoomImages = [];
        let currentZoomIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date inputs
            const today = new Date().toISOString().split('T')[0];
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            
            checkInInput.min = today;
            // DO NOT set default date - leave empty
            
            // For cottages, check-out is always same as check-in (same day)
            checkOutInput.min = today;
            
            // Disable check-out input (auto-set to same day)
            checkOutInput.disabled = true;

            // Set active tab
            const currentPath = window.location.pathname;
            const roomTab = document.getElementById('room-tab');
            const cottageTab = document.getElementById('cottage-tab');
            
            if (currentPath.includes('cottage')) {
                roomTab.classList.remove('active');
                cottageTab.classList.add('active');
            } else {
                roomTab.classList.add('active');
                cottageTab.classList.remove('active');
            }
            
            // Search button functionality
            document.getElementById('searchBtn').addEventListener('click', function() {
                const checkIn = checkInInput.value;
                const guests = document.getElementById('guest-number').value;
                
                if (!checkIn) {
                    showNotification('Please select a booking date first', 'error');
                    return;
                }
                
                // For cottages, check-out is always same as check-in (same day)
                currentCheckIn = checkIn;
                currentCheckOut = checkIn;
                currentGuests = guests;
                
                // Update check-out input
                checkOutInput.value = checkIn;
                
                loadUnits('cottages');
            });
            
            // Guest number picker functionality
            const guestNumberInput = document.getElementById('guest-number');
            
            guestNumberInput.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    let value = parseInt(this.value);
                    if (value < 40) this.value = value + 1;
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    let value = parseInt(this.value);
                    if (value > 1) this.value = value - 1;
                }
            });
            
            guestNumberInput.addEventListener('input', function() {
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) value = 1;
                else if (value > 40) value = 40;
                this.value = value;
            });
            
            guestNumberInput.addEventListener('wheel', function(e) {
                e.preventDefault();
                let value = parseInt(this.value);
                
                if (e.deltaY < 0) {
                    if (value < 40) this.value = value + 1;
                } else {
                    if (value > 1) this.value = value - 1;
                }
            });

            // Check-in date event listener - AUTO SET CHECK-OUT TO SAME DATE
            checkInInput.addEventListener('change', function() {
                const checkIn = this.value;
                
                if (checkIn) {
                    // Auto-set check-out to same date as check-in (same day booking)
                    checkOutInput.value = checkIn;
                    currentCheckIn = checkIn;
                    currentCheckOut = checkIn;
                    
                    console.log('Check-out auto-set to:', checkIn, '(same day booking)');
                }
            });

            // Event delegation for virtual tour buttons (ACTIVE)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.virtual-tour-btn')) {
                    e.preventDefault();
                    const button = e.target.closest('.virtual-tour-btn');
                    const tourUrl = button.dataset.tourUrl;
                    openVirtualTour(tourUrl);
                }
            });

            // Event delegation for disabled virtual tour buttons (CLICKABLE with notification)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.virtual-tour-disabled')) {
                    e.preventDefault();
                    showNotification('Virtual tour is not available for this cottage yet. Please check back later!', 'info', 4000);
                }
            });

            // Load initial cottages
            loadUnits('cottages');
            
            // Load cart count and items
            loadCartCount();
            loadCartItemsForValidation();

            // Close zoom modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeZoomModal();
            });
        });

        // ==================== IMAGE GALLERY FUNCTIONS ====================
        function createImageGallery(images, unitId) {
            if (!images || images.length === 0) {
                return `
                    <div class="card-image-placeholder">
                        <i class="fas fa-home"></i>
                        <p>No images available</p>
                    </div>
                `;
            }

            const mainImageUrl = images[0];
            
            let html = `
                <div class="image-gallery" data-unit-id="${unitId}">
                    <img src="${mainImageUrl}" alt="Unit image" class="gallery-main-image" onclick="openZoomModal(${unitId}, 0)">
            `;

            if (images.length > 1) {
                html += `
                    <button class="gallery-nav prev" onclick="changeGalleryImage(${unitId}, -1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="gallery-nav next" onclick="changeGalleryImage(${unitId}, 1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                `;

                html += '<div class="gallery-thumbnails">';
                images.forEach((img, index) => {
                    html += `
                        <img src="${img}" 
                             alt="Thumbnail ${index + 1}" 
                             class="thumbnail ${index === 0 ? 'active' : ''}" 
                             onclick="setGalleryImage(${unitId}, ${index})">
                    `;
                });
                html += '</div>';
            }

            html += '</div>';
            return html;
        }

        function changeGalleryImage(unitId, direction) {
            const gallery = document.querySelector(`[data-unit-id="${unitId}"]`);
            if (!gallery) return;

            const thumbnails = gallery.querySelectorAll('.thumbnail');
            if (thumbnails.length === 0) return;

            let currentIndex = -1;
            thumbnails.forEach((thumb, index) => {
                if (thumb.classList.contains('active')) currentIndex = index;
            });

            let newIndex = currentIndex + direction;
            if (newIndex < 0) newIndex = thumbnails.length - 1;
            if (newIndex >= thumbnails.length) newIndex = 0;

            setGalleryImage(unitId, newIndex);
        }

        function setGalleryImage(unitId, index) {
            const gallery = document.querySelector(`[data-unit-id="${unitId}"]`);
            if (!gallery) return;

            const mainImage = gallery.querySelector('.gallery-main-image');
            const thumbnails = gallery.querySelectorAll('.thumbnail');

            if (index < 0 || index >= thumbnails.length) return;

            mainImage.src = thumbnails[index].src;
            mainImage.onclick = () => openZoomModal(unitId, index);

            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            thumbnails[index].classList.add('active');
        }

        // ==================== ZOOM MODAL FUNCTIONS ====================
        function openZoomModal(unitId, startIndex = 0) {
            const gallery = document.querySelector(`[data-unit-id="${unitId}"]`);
            if (!gallery) return;

            const thumbnails = gallery.querySelectorAll('.thumbnail');
            currentZoomImages = Array.from(thumbnails).map(thumb => thumb.src);
            
            if (currentZoomImages.length === 0) {
                const mainImage = gallery.querySelector('.gallery-main-image');
                if (mainImage) currentZoomImages = [mainImage.src];
            }

            currentZoomIndex = startIndex;
            updateZoomModal();

            const modal = document.getElementById('imageZoomModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeZoomModal() {
            const modal = document.getElementById('imageZoomModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function changeZoomImage(direction) {
            currentZoomIndex += direction;
            
            if (currentZoomIndex < 0) currentZoomIndex = currentZoomImages.length - 1;
            if (currentZoomIndex >= currentZoomImages.length) currentZoomIndex = 0;

            updateZoomModal();
        }

        function updateZoomModal() {
            const zoomImage = document.getElementById('zoomImage');
            const zoomCounter = document.getElementById('zoomCounter');

            if (currentZoomImages.length > 0) {
                zoomImage.src = currentZoomImages[currentZoomIndex];
                zoomCounter.textContent = `${currentZoomIndex + 1} / ${currentZoomImages.length}`;
            }
        }

        // ==================== CART FUNCTIONS ====================
        function loadCartCount() {
            fetch('/api/cart/items')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.cart && data.items.length > 0) {
                        cartItemCount = data.items.length;
                        updateCartBadge(cartItemCount);
                    } else {
                        cartItemCount = 0;
                        updateCartBadge(0);
                    }
                })
                .catch(error => console.error('Error loading cart count:', error));
        }

        function loadCartItemsForValidation() {
            fetch('/api/cart/items')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.cart && data.items.length > 0) {
                        cartItems = data.items;
                        cartDates.checkIn = data.cart.checkInDate;
                        cartDates.checkOut = data.cart.checkOutDate;
                        
                        currentCheckIn = cartDates.checkIn;
                        currentCheckOut = cartDates.checkOut;
                        
                        document.getElementById('check_in').value = currentCheckIn;
                        document.getElementById('check_out').value = currentCheckOut;
                        
                        if (data.items.length > 0) {
                            showNotification(
                                `You have ${data.items.length} item(s) in your cart.`, 
                                'info', 
                                5000
                            );
                        }
                    } else {
                        cartItems = [];
                        cartDates = { checkIn: '', checkOut: '' };
                    }
                })
                .catch(error => {
                    console.error('Error loading cart items:', error);
                    cartItems = [];
                    cartDates = { checkIn: '', checkOut: '' };
                });
        }

        function updateCartBadge(count) {
            const navbarCartBadge = document.getElementById('navbar-cart-badge');
            if (navbarCartBadge) {
                if (count > 0) {
                    navbarCartBadge.textContent = count;
                    navbarCartBadge.style.display = 'flex';
                    navbarCartBadge.style.animation = 'badgePop 0.3s ease';
                } else {
                    navbarCartBadge.style.display = 'none';
                }
            }
        }

        // ==================== LOAD UNITS ====================
        function loadUnits(type) {
            const container = document.getElementById(`${type}-container`);
            const loading = document.getElementById(`${type}-loading`);
            
            if (container) {
                container.innerHTML = '';
                container.style.display = 'none';
            }
            if (loading) loading.style.display = 'flex';
            
            const params = new URLSearchParams();
            if (currentCheckIn) params.append('check_in', currentCheckIn);
            if (currentCheckOut) params.append('check_out', currentCheckOut);
            if (currentGuests) params.append('guests', currentGuests);
            
            fetch(`/api/available-cottages?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (loading) loading.style.display = 'none';
                    
                    if (data.success) {
                        renderUnits(data.cottages, container, type);
                    } else {
                        renderEmptyState(container, 'No cottages available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (loading) loading.style.display = 'none';
                    renderEmptyState(container, 'Error loading cottages. Please try again.');
                });
        }

        // ==================== RENDER UNITS ====================
        function renderUnits(units, container, type) {
            if (!units || units.length === 0) {
                renderEmptyState(container, 'No ' + type + ' available for the selected date.');
                return;
            }
            
            container.innerHTML = '';
            container.style.display = 'flex';
            
            units.forEach(unit => {
                // Parse images
                let images = [];
                try {
                    if (unit.images) {
                        const parsedImages = typeof unit.images === 'string' ? JSON.parse(unit.images) : unit.images;
                        if (Array.isArray(parsedImages)) {
                            images = parsedImages.map(img => {
                                if (!img.startsWith('http')) return `/storage/${img}`;
                                return img;
                            }).filter(img => img);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing images:', e);
                }
                
                // Virtual tour button - UPDATED with clickable disabled state
                let virtualTourButton = '';
                if (unit.has_virtual_tour && unit.virtual_tour_url) {
                    virtualTourButton = `
                        <button data-tour-url="${escapeHtml(unit.virtual_tour_url)}" 
                                class="action-btn btn-virtual-tour virtual-tour-btn">
                            <i class="fas fa-vr-cardboard"></i>
                            View Virtual Tour
                        </button>
                    `;
                } else {
                    // NO disabled attribute - clickable with notification
                    virtualTourButton = `
                        <button class="action-btn btn-virtual-tour virtual-tour-disabled">
                            <i class="fas fa-vr-cardboard"></i>
                            No Virtual Tour Available
                        </button>
                    `;
                }
                
                const card = document.createElement('div');
                card.className = 'accommodation-card fade-in';
                card.innerHTML = `
                    <div class="card-image-container">
                        ${createImageGallery(images, unit.unitID)}
                        <span class="card-badge">
                            <i class="fas fa-check-circle mr-1"></i>
                            Available
                        </span>
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <h3 class="card-title">${escapeHtml(unit.unitName)}</h3>
                            <p class="card-description">${escapeHtml(unit.description || 'Perfect for day trips and family gatherings. Enjoy the outdoors in comfort and style.')}</p>
                        </div>
                        
                        <div class="card-features">
                            <span class="feature-tag">
                                <i class="fas fa-users"></i>
                                ${unit.capacity} guests
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-home"></i>
                                ${unit.unitType}
                            </span>
                            ${unit.has_virtual_tour ? '<span class="feature-tag"><i class="fas fa-vr-cardboard"></i> Virtual Tour Available</span>' : ''}
                        </div>
                        
                        <div class="card-price-section">
                            <div class="card-price">
                                ₱${parseInt(unit.unitRatePrice).toLocaleString()}
                                <span class="price-period">/ day</span>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            ${virtualTourButton}
                            <button onclick="addToCart(${unit.unitID}, this)" class="action-btn btn-cart">
                                <i class="fas fa-cart-plus"></i>
                                Add to Cart
                            </button>
                            <button onclick="bookNow(${unit.unitID}, this)" class="action-btn btn-book">
                                <i class="fas fa-calendar-check"></i>
                                Book Now
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // ==================== VIRTUAL TOUR FUNCTION ====================
        function openVirtualTour(virtualTourUrl) {
            console.log('Opening virtual tour:', virtualTourUrl);
            
            if (!virtualTourUrl || virtualTourUrl === 'null' || virtualTourUrl === 'undefined') {
                showNotification('Virtual tour not available for this cottage', 'info');
                return;
            }
            
            // Open in new tab
            const newWindow = window.open(virtualTourUrl, '_blank');
            
            if (newWindow) {
                newWindow.focus();
                showNotification('Opening virtual tour in new tab...', 'success', 2000);
            } else {
                showNotification('Please allow popups to view the virtual tour', 'warning');
            }
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ==================== OTHER FUNCTIONS ====================
        function renderEmptyState(container, message) {
            container.style.display = 'block';
            container.innerHTML = `
                <div class="empty-state fade-in">
                    <div class="empty-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="empty-title">No Cottages Available</h3>
                    <p class="empty-description">${message}</p>
                    <button onclick="resetSearch()" class="action-btn btn-book" style="max-width: 200px; margin: 0 auto;">
                        <i class="fas fa-calendar-alt"></i>
                        Try Different Date
                    </button>
                </div>
            `;
        }

        function resetSearch() {
            document.getElementById('check_in').value = '';
            document.getElementById('check_out').value = '';
            document.getElementById('guest-number').value = 1;
            
            currentCheckIn = '';
            currentCheckOut = '';
            currentGuests = 1;
            
            loadUnits('cottages');
        }

        function addToCart(unitId, button) {
            const checkIn = document.getElementById('check_in').value;
            const guests = document.getElementById('guest-number').value;
            
            if (!checkIn) {
                showNotification('Please select a booking date first', 'error');
                return;
            }
            
            const effectiveCheckOut = checkIn;
            const originalContent = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            button.disabled = true;
            
            fetch('/api/cart/add-cottage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    unit_id: unitId,
                    check_in: checkIn,
                    check_out: effectiveCheckOut,
                    guests: guests
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.login_required) {
                        window.location.href = '/login';
                    } else {
                        cartItemCount = data.cart_count;
                        updateCartBadge(cartItemCount);
                        cartItems = data.items || [];
                        cartDates.checkIn = checkIn;
                        cartDates.checkOut = effectiveCheckOut;
                        currentCheckIn = checkIn;
                        currentCheckOut = effectiveCheckOut;
                        
                        let message = 'Cottage added to cart successfully! ';
                        message += `(Same-day booking: ${checkIn} for ${guests} guest${guests > 1 ? 's' : ''})`;
                        
                        if (data.calculation_breakdown) {
                            message += ` - ${data.calculation_breakdown.formula}`;
                        }
                        
                        showNotification(message, 'success');
                        
                        button.innerHTML = '<i class="fas fa-check"></i> Added!';
                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }, 2000);
                    }
                } else {
                    showNotification('Error: ' + data.message, 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add cottage to cart.', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }

        function bookNow(unitId, button) {
            const checkIn = document.getElementById('check_in').value;
            const guests = document.getElementById('guest-number').value;
            
            if (!checkIn) {
                showNotification('Please select a booking date first', 'error');
                return;
            }
            
            const effectiveCheckOut = checkIn;
            const originalContent = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
            
            fetch('/api/cart/add-cottage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    unit_id: unitId,
                    check_in: checkIn,
                    check_out: effectiveCheckOut,
                    guests: guests
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.login_required) {
                        window.location.href = '/login';
                    } else {
                        cartItemCount = data.cart_count;
                        updateCartBadge(cartItemCount);
                        window.location.href = "{{ route('booking.page') }}";
                    }
                } else {
                    showNotification('Error: ' + data.message, 'error');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to book cottage.', 'error');
                button.innerHTML = originalContent;
                button.disabled = false;
            });
        }

        function showNotification(message, type = 'info', duration = 3000) {
            const container = document.getElementById('notification-container');
            const id = 'notification-' + Date.now();
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-triangle',
                warning: 'fa-exclamation-circle',
                info: 'fa-info-circle'
            };
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas ${icons[type]} notification-icon"></i>
                <div class="notification-content">
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="closeNotification('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => notification.classList.add('show'), 10);
            
            const autoRemove = setTimeout(() => closeNotification(id), duration);
            notification.dataset.timer = autoRemove;
        }

        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                clearTimeout(notification.dataset.timer);
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) notification.remove();
                }, 500);
            }
        }
    </script>
</body>
</html>