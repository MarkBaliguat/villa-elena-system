<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    
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
            background-color: var(--light-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        
        .cursive-font {
            font-family: 'Dancing Script', cursive;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--primary-yellow), var(--secondary-yellow));
            border-radius: 10px;
            border: 2px solid #f8f9fa;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #FFC800, #FF9500);
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Glass morphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Gradients */
        .gradient-sunflower {
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10B981, #059669);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }
        
        .gradient-error {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }
        
        .gradient-dark {
            background: linear-gradient(135deg, var(--primary-black), #2D3748);
        }
        
        /* Transitions */
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Card hover effects */
        .cart-item-hover {
            transition: all 0.3s ease;
        }
        
        .cart-item-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.15);
        }
        
        /* Notification Container */
        .notification-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 400px;
        }
        
        .notification {
            padding: 18px 22px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: flex-start;
            gap: 15px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 200px;
            overflow: hidden;
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
            background: linear-gradient(135deg, var(--primary-yellow), var(--secondary-yellow));
            color: var(--primary-black);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: white;
        }
        
        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
            margin-top: 3px;
        }
        
        .notification-content {
            flex-grow: 1;
            min-width: 0;
        }
        
        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .notification-message {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: inherit;
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
            flex-shrink: 0;
        }
        
        .notification-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Animation for notifications */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification-slide-in {
            animation: slideInRight 0.5s ease-out forwards;
        }
        
        .notification-slide-out {
            animation: slideOutRight 0.5s ease-out forwards;
        }
        
        /* Button styling */
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-modern::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-modern:hover::after {
            left: 100%;
        }
        
        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        /* Loading spinner */
        .spinner-modern {
            border: 3px solid rgba(255, 215, 0, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary-yellow);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Badge styling */
        .type-badge-modern {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        
        /* Input styling */
        .input-modern {
            border-radius: 12px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .input-modern:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255, 215, 0, 0.15);
            outline: none;
        }
        
        /* Price display */
        .price-display {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }
        
        /* Main content spacing */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 300px);
        }
        
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        /* Modal styling */
        .modal-enter {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Pulse animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <!-- Import Navigation -->
    @include('customerFolder.partials.navbar')
    <!-- Main Content -->
    <main class="main-content flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="text-center mb-12 slide-up">
                <h1 class="text-5xl font-bold mb-4 cursive-font">Your Shopping Cart</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Review your selected items and proceed to checkout</p>
                <div class="w-24 h-1 gradient-sunflower mx-auto mt-6 rounded-full"></div>
            </div>
            
            <div id="cart-container" class="max-w-6xl mx-auto">
                <!-- Loading Spinner -->
                <div id="loading-spinner" class="text-center py-16 fade-in">
                    <div class="inline-block relative mb-6">
                        <div class="spinner-modern w-16 h-16"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-yellow-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xl font-medium text-gray-700 mb-2">Loading your cart</p>
                    <p class="text-gray-500">Please wait while we fetch your items...</p>
                    <div class="w-48 h-1 bg-gradient-to-r from-yellow-100 to-orange-100 mx-auto mt-4 rounded-full overflow-hidden">
                        <div class="h-full gradient-sunflower rounded-full pulse" style="width: 60%;"></div>
                    </div>
                </div>
                
                <!-- Cart items will be loaded here by JavaScript -->
            </div>
        </div>
    </main>

    <!-- Import Footer -->
    @include('customerFolder.partials.footer')

    <!-- Notification Container -->
    <div id="notification-container" class="notification-container"></div>

    <!-- Availability Modal Container -->
    <div id="availability-modal-container"></div>

    <script>
        // Global variables
        let entranceFeeAmount = 0;
        let hasActiveEntranceFee = false;
        let cartUnitType = null;
        let cartItemCount = 0;
        let activeNotifications = new Set();

        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
        });

        function loadCartItems() {
            const container = document.getElementById('cart-container');
            const loadingSpinner = document.getElementById('loading-spinner');
            
            console.log('Loading cart items from CartController...');
            
            // Show loading
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }
            
            // Load cart items using CartController
            fetch('/api/cart/items', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(cartData => {
                console.log('Cart data loaded:', cartData);
                
                // Hide loading spinner
                if (loadingSpinner) {
                    loadingSpinner.classList.add('hidden');
                }
                
                // Check if cart exists and has items
                if (cartData.success && cartData.cart && cartData.items && cartData.items.length > 0) {
                    cartItemCount = cartData.items.length;
                    cartUnitType = cartData.cart_type;
                    entranceFeeAmount = parseFloat(cartData.entrance_fee) || 0;
                    hasActiveEntranceFee = cartData.has_active_entrance_fee || false;
                    
                    // Render cart items
                    renderCartItems(cartData.cart, cartData.items, container);
                } else {
                    showEmptyCartMessage(container);
                }
            })
            .catch(error => {
                console.error('Error loading cart items:', error);
                if (loadingSpinner) {
                    loadingSpinner.classList.add('hidden');
                }
                
                showErrorMessage(container, error);
            });
        }

        function showEmptyCartMessage(container) {
            container.innerHTML = `
                <div class="text-center py-16 slide-up">
                    <div class="glass-card max-w-md mx-auto p-10 rounded-2xl">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full gradient-sunflower mb-6 text-white">
                            <i class="fas fa-shopping-cart text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 cursive-font">Your cart is empty</h3>
                        <p class="text-gray-600 mb-8">Looks like you haven't added any items yet. Start exploring our offerings!</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('roomBooking') }}" class="btn-modern gradient-dark text-white font-bold py-3 px-8 rounded-xl hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-bed mr-3 text-lg"></i>
                                Book Rooms
                            </a>
                            <a href="{{ route('cottageBooking') }}" class="btn-modern gradient-sunflower text-black font-bold py-3 px-8 rounded-xl hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-home mr-3 text-lg"></i>
                                Book Cottages
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        function showErrorMessage(container, error) {
            container.innerHTML = `
                <div class="text-center py-16 slide-up">
                    <div class="glass-card max-w-md mx-auto p-10 rounded-2xl border-l-4 border-red-500">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-pink-50 mb-6">
                            <i class="fas fa-exclamation-triangle text-4xl text-gradient-error"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Error</h3>
                        <p class="text-gray-600 mb-6">We're having trouble loading your cart. Please login first or add something in your cart.</p>

                        <button onclick="loadCartItems()" class="btn-modern gradient-sunflower text-black font-bold py-3 px-8 rounded-xl hover:shadow-lg mx-auto">
                            <i class="fas fa-redo-alt mr-2"></i>
                            Retry Loading
                        </button>
                    </div>
                </div>
            `;
        }

        function renderCartItems(cart, items, container) {
            const daysCount = cart.daysCount && cart.daysCount > 0 ? cart.daysCount : 1;
            const numGuests = parseInt(cart.numGuests);
            
            // ✅ FIXED DATE FORMAT: "2025-12-13"
            const checkInDate = cart.checkInDate;
            const checkOutDate = cart.checkOutDate;
            
            let totalRoomAmount = 0;
            let totalCottageAmount = 0;
            let cottageEntranceFee = 0;
            let hasRoom = false;
            let hasCottage = false;
            
            // Process each item
            const itemsHTML = items.map(item => {
                const unit = item.unit;
                const unitPrice = parseFloat(unit.unitRatePrice);
                let itemTotal = 0;
                let calculation = item.calculation || '';
                let itemType = unit.unitType;
                
                if (itemType === 'room') {
                    hasRoom = true;
                    itemTotal = item.calculatedSubtotal || (unitPrice * numGuests * daysCount);
                    totalRoomAmount += itemTotal;
                    
                    return `
                        <div class="glass-card cart-item-hover p-6 rounded-2xl mb-6 slide-up border-l-4 border-blue-500">
                            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                                <div class="flex items-start space-x-6 flex-1">
                                    <div class="relative">
                                        <img src="${getUnitImage(unit)}" alt="${unit.unitName}" class="cart-item-image w-32 h-32 rounded-xl">
                                        <span class="type-badge-modern bg-gradient-to-r from-blue-500 to-indigo-600 text-white absolute -top-2 -right-2">
                                            <i class="fas fa-bed mr-1"></i>
                                            Room
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                            <h3 class="font-bold text-xl text-gray-900 mb-2 md:mb-0">${unit.unitName}</h3>
                                            <div class="text-right">
                                                <p class="font-bold text-2xl text-blue-600 price-display">₱${itemTotal.toFixed(2)}</p>
                                                <button onclick="removeFromCart(${item.cartItemID})" class="text-red-500 hover:text-red-700 text-sm mt-1 transition-colors flex items-center justify-end">
                                                    <i class="fas fa-trash-alt mr-2"></i>
                                                    Remove Item
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center">
                                                <i class="fas fa-users mr-2"></i> ${numGuests} ${numGuests === 1 ? 'guest' : 'guests'}
                                            </span>
                                            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center">
                                                <i class="fas fa-calendar-alt mr-2"></i> ${daysCount} day(s)
                                            </span>
                                        </div>
                                        
                                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100">
                                            <div class="flex items-start">
                                                <i class="fas fa-calculator text-blue-500 text-lg mr-3 mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-blue-800 mb-1">Price Calculation</p>
                                                    <p class="text-sm text-blue-700">${calculation}</p>
                                                    ${numGuests === 1 ? 
                                                        `<p class="text-xs text-blue-600 mt-2 flex items-center">
                                                            <i class="fas fa-info-circle mr-2"></i> Single occupancy: minimum charge for 2 guests
                                                        </p>` : ''
                                                    }
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (itemType === 'cottage') {
                    hasCottage = true;
                    itemTotal = item.calculatedSubtotal || unitPrice;
                    
                    if (hasActiveEntranceFee) {
                        cottageEntranceFee += parseFloat(entranceFeeAmount) * numGuests;
                        totalCottageAmount += itemTotal;
                    } else {
                        totalCottageAmount += unitPrice;
                    }
                    
                    const isDisabled = !hasActiveEntranceFee;
                    
                    return `
                        <div class="glass-card cart-item-hover p-6 rounded-2xl mb-6 slide-up border-l-4 ${isDisabled ? 'border-red-500' : 'border-green-500'} ${isDisabled ? 'opacity-80' : ''}">
                            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                                <div class="flex items-start space-x-6 flex-1">
                                    <div class="relative">
                                        <img src="${getUnitImage(unit)}" alt="${unit.unitName}" class="cart-item-image w-32 h-32 rounded-xl ${isDisabled ? 'grayscale' : ''}">
                                        <span class="type-badge-modern ${isDisabled ? 'bg-gradient-to-r from-red-500 to-pink-600' : 'gradient-sunflower'} text-white absolute -top-2 -right-2">
                                            <i class="fas fa-home mr-1"></i>
                                            Cottage
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                            <h3 class="font-bold text-xl text-gray-900 mb-2 md:mb-0">${unit.unitName}</h3>
                                            <div class="text-right">
                                                <p class="font-bold text-2xl ${isDisabled ? 'text-red-600' : 'text-green-600'} price-display">₱${itemTotal.toFixed(2)}</p>
                                                <button onclick="removeFromCart(${item.cartItemID})" class="${isDisabled ? 'text-red-400 hover:text-red-600' : 'text-red-500 hover:text-red-700'} text-sm mt-1 transition-colors flex items-center justify-end">
                                                    <i class="fas fa-trash-alt mr-2"></i>
                                                    Remove Item
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            <span class="${isDisabled ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'} px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center">
                                                <i class="fas fa-users mr-2"></i> ${numGuests} ${numGuests === 1 ? 'guest' : 'guests'}
                                            </span>
                                            <span class="${isDisabled ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'} px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center">
                                                <i class="fas fa-calendar-day mr-2"></i> Day use only
                                            </span>
                                        </div>
                                        
                                        <div class="${isDisabled ? 'bg-gradient-to-r from-red-50 to-pink-50 border-red-100' : 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-100'} p-4 rounded-xl border">
                                            <div class="flex items-start">
                                                <i class="fas ${isDisabled ? 'fa-exclamation-triangle text-red-500' : 'fa-calculator text-green-500'} text-lg mr-3 mt-1"></i>
                                                <div>
                                                    <p class="font-medium ${isDisabled ? 'text-red-800' : 'text-green-800'} mb-1">${isDisabled ? 'Important Notice' : 'Price Calculation'}</p>
                                                    <p class="text-sm ${isDisabled ? 'text-red-700' : 'text-green-700'}">${calculation}</p>
                                                    ${isDisabled ? 
                                                        `<p class="text-xs text-red-600 mt-2 flex items-center">
                                                            <i class="fas fa-info-circle mr-2"></i> Cannot proceed to booking without active entrance fee
                                                        </p>` :
                                                        `<p class="text-xs text-green-600 mt-2 flex items-center">
                                                            <i class="fas fa-check-circle mr-2"></i> Day use only (not multiplied by days)
                                                        </p>`
                                                    }
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                return '';
            }).join('');

            // Calculate totals
            const accommodationSubtotal = totalRoomAmount + totalCottageAmount;
            const total = accommodationSubtotal;

            // Check if we can proceed to booking
            const hasCottageInCart = cartUnitType === 'cottage' || cartUnitType === 'mixed';
            const canProceedToBooking = !(hasCottageInCart && !hasActiveEntranceFee) && cartUnitType !== 'mixed';

            // Warning messages
            let warningMessage = '';
            if (cartUnitType === 'mixed') {
                warningMessage = `
                    <div class="glass-card mb-8 p-6 rounded-2xl slide-up border-l-4 border-yellow-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full gradient-sunflower flex items-center justify-center mr-4 text-white">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-yellow-800 mb-2">Mixed Cart Items Detected</h4>
                                <p class="text-yellow-700 mb-4">
                                    You cannot have both rooms and cottages in the same booking. Please remove either all room items or all cottage items to proceed.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button onclick="removeAllRooms()" class="btn-modern gradient-dark text-white py-3 px-6 rounded-xl hover:shadow-lg flex items-center justify-center">
                                        <i class="fas fa-bed mr-3"></i>
                                        Remove All Rooms (${items.filter(i => i.unit?.unitType === 'room').length})
                                    </button>
                                    <button onclick="removeAllCottages()" class="btn-modern gradient-warning text-white py-3 px-6 rounded-xl hover:shadow-lg flex items-center justify-center">
                                        <i class="fas fa-home mr-3"></i>
                                        Remove All Cottages (${items.filter(i => i.unit?.unitType === 'cottage').length})
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (hasCottageInCart && !hasActiveEntranceFee) {
                warningMessage = `
                    <div class="glass-card mb-8 p-6 rounded-2xl slide-up border-l-4 border-red-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full gradient-error text-white flex items-center justify-center mr-4">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-red-800 mb-2">Entrance Fee Required</h4>
                                <p class="text-red-700 mb-4">
                                    Your cart contains cottage items, but there is no active entrance fee set up in the system.
                                    Please contact villa management to set up an entrance fee before proceeding with booking.
                                </p>
                                <div class="flex flex-wrap gap-3">
                                    <button onclick="loadCartItems()" class="btn-modern gradient-sunflower text-black py-2 px-5 rounded-xl text-sm hover:shadow-lg">
                                        <i class="fas fa-sync-alt mr-2"></i> Refresh Cart
                                    </button>
                                    <button onclick="removeAllCottages()" class="btn-modern gradient-error text-white py-2 px-5 rounded-xl text-sm hover:shadow-lg">
                                        <i class="fas fa-trash-alt mr-2"></i> Remove All Cottages
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            const cartHTML = `
                ${warningMessage}
                
                <!-- Cart Header -->
                <div class="glass-card rounded-2xl p-8 mb-8 slide-up">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2 cursive-font">Booking Summary</h2>
                            ${cartUnitType && cartUnitType !== 'mixed' ? `
                                <span class="type-badge-modern ${cartUnitType === 'room' ? 'bg-gradient-to-r from-blue-500 to-indigo-600' : 'gradient-sunflower'} text-white mt-2">
                                    <i class="fas ${cartUnitType === 'room' ? 'fa-bed' : 'fa-home'} mr-2"></i>
                                    ${cartUnitType === 'room' ? 'Room Booking' : 'Cottage Booking'}
                                </span>
                            ` : ''}
                        </div>
                        <div class="mt-6 lg:mt-0 grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                                <p class="text-sm font-medium text-blue-700 mb-1">Check-in</p>
                                <p class="font-bold text-blue-900">${checkInDate}</p>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                <p class="text-sm font-medium text-purple-700 mb-1">Check-out</p>
                                <p class="font-bold text-purple-900">${checkOutDate}</p>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                                <p class="text-sm font-medium text-green-700 mb-1">Duration</p>
                                <p class="font-bold text-green-900">${daysCount} day(s)</p>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl">
                                <p class="text-sm font-medium text-amber-700 mb-1">Guests</p>
                                <p class="font-bold text-amber-900">${numGuests}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cart Items -->
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-list-ul mr-3 text-blue-500"></i>
                        Your Items (${items.length})
                    </h3>
                    <div class="space-y-4">
                        ${itemsHTML}
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 mt-8 pt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-invoice-dollar mr-3 text-green-500"></i>
                            Price Breakdown
                        </h3>
                        <div class="space-y-4 max-w-lg ml-auto">
                            ${totalRoomAmount > 0 ? `
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700 flex items-center">
                                    <i class="fas fa-bed text-blue-500 mr-3"></i>
                                    Room Total
                                </span>
                                <span class="font-bold text-xl text-blue-600 price-display">₱${totalRoomAmount.toFixed(2)}</span>
                            </div>
                            ` : ''}
                            
                            ${totalCottageAmount > 0 ? `
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-700 flex items-center">
                                    <i class="fas fa-home ${hasActiveEntranceFee ? 'text-green-500' : 'text-red-500'} mr-3"></i>
                                    Cottage Total
                                </span>
                                <span class="font-bold text-xl ${hasActiveEntranceFee ? 'text-green-600' : 'text-red-600'} price-display">₱${totalCottageAmount.toFixed(2)}</span>
                            </div>
                            ` : ''}
                            
                            <!-- Grand Total -->
                            <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-300">
                                <span class="text-gray-900 text-xl font-bold flex items-center">
                                    <i class="fas fa-receipt mr-3 text-purple-500"></i>
                                    Total Amount
                                </span>
                                <span class="${cartUnitType === 'mixed' ? 'text-yellow-600' : 'text-green-600'} font-bold text-3xl price-display">₱${total.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-12 flex flex-col md:flex-row gap-6">
                        <div class="flex-1 flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('roomBooking') }}" class="btn-modern gradient-dark text-white text-center font-bold py-4 px-8 rounded-xl hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-plus-circle mr-3 text-lg"></i>
                                Add More Rooms
                            </a>
                            <a href="{{ route('cottageBooking') }}" class="btn-modern gradient-sunflower text-black text-center font-bold py-4 px-8 rounded-xl hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-plus-circle mr-3 text-lg"></i>
                                Add More Cottages
                            </a>
                        </div>
                        
                        ${canProceedToBooking ? 
                            `<button onclick="proceedToCheckout()" class="flex-1 btn-modern gradient-success text-white text-center font-bold py-4 px-8 rounded-xl hover:shadow-xl transition-all duration-300 flex items-center justify-center checkout-button">
                                <i class="fas fa-lock mr-3 text-lg"></i>
                                Proceed to Secure Checkout
                            </button>` :
                            cartUnitType === 'mixed' ?
                            `<button class="flex-1 gradient-warning text-white text-center font-bold py-4 px-8 rounded-xl flex items-center justify-center btn-disabled">
                                <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                                Fix Mixed Cart to Continue
                            </button>` :
                            hasCottage && !hasActiveEntranceFee ?
                            `<button onclick="showEntranceFeeAlert()" class="flex-1 gradient-error text-white text-center font-bold py-4 px-8 rounded-xl flex items-center justify-center btn-disabled">
                                <i class="fas fa-ban mr-3 text-lg"></i>
                                Entrance Fee Required
                            </button>` :
                            `<button onclick="proceedToCheckout()" class="flex-1 btn-modern gradient-success text-white text-center font-bold py-4 px-8 rounded-xl hover:shadow-xl transition-all duration-300 flex items-center justify-center checkout-button">
                                <i class="fas fa-lock mr-3 text-lg"></i>
                                Proceed to Secure Checkout
                            </button>`
                        }
                    </div>
                </div>
            `;
            
            container.innerHTML = cartHTML;
        }

        // ✅ ENHANCED: proceedToCheckout function with ALL validations
        function proceedToCheckout() {
            const checkoutBtn = document.querySelector('.checkout-button');
            if (!checkoutBtn) return;
            
            const originalContent = checkoutBtn.innerHTML;
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Validating...';
            checkoutBtn.disabled = true;
            
            // Clear any existing notifications first
            clearAllNotifications();
            
            // First, load cart to get current cart ID
            fetch('/api/cart/items', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network error: ${response.status}`);
                }
                return response.json();
            })
            .then(cartData => {
                if (!cartData.success || !cartData.cart) {
                    throw new Error('Cart not found');
                }
                
                // Validate cart before proceeding
                return fetch('/api/cart/validate-before-checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }
                return response.json();
            })
            .then(validationData => {
                console.log('Validation response:', validationData);
                
                if (validationData.success) {
                    // ✅ SUCCESS - ALL CHECKS PASSED
                    showNotification('✓ All items are available! Redirecting to checkout...', 'success', 2000);
                    
                    // ✅ Only redirect if validation is 100% successful
                    setTimeout(() => {
                        window.location.href = "{{ route('booking.page') }}";
                    }, 1500);
                    
                } else {
                    // ❌ VALIDATION FAILED - DO NOT REDIRECT
                    checkoutBtn.innerHTML = originalContent;
                    checkoutBtn.disabled = false;
                    
                    // Handle different validation failures with proper error messages
                    if (validationData.has_special_event_conflict) {
                        showNotification(
                            '❌ Cannot book during special event period. Please choose different dates.',
                            'error',
                            5000
                        );
                        
                    } else if (validationData.has_special_event_unit_conflict) {
                        showNotification(
                            `❌ ${validationData.special_event_details?.unitName || 'Selected unit'} is reserved for special events only.`,
                            'error',
                            5000
                        );
                        
                    } else if (validationData.has_availability_issues && validationData.unavailable_items) {
                        // Show first error message
                        if (validationData.validation_errors && validationData.validation_errors.length > 0) {
                            showNotification(
                                `❌ ${validationData.validation_errors[0]}`,
                                'error',
                                5000
                            );
                        }
                        
                        // Show modal for unavailable items
                        setTimeout(() => {
                            showUnavailableItemsModal(validationData.unavailable_items, validationData.validation_errors);
                        }, 1000);
                        
                    } else if (validationData.has_mixed_items) {
                        showNotification(
                            '❌ Rooms and cottages cannot be booked together. Please book them separately.',
                            'error',
                            5000
                        );
                        
                    } else if (validationData.has_entrance_fee_issue) {
                        showNotification(
                            '❌ Cottage bookings require an active entrance fee. Please contact management.',
                            'error',
                            5000
                        );
                        
                    } else if (validationData.cart_empty) {
                        showNotification(
                            '❌ Your cart is empty! Please add accommodations first.',
                            'error',
                            5000
                        );
                        
                    } else if (validationData.login_required) {
                        showNotification(
                            '❌ Please login first to proceed with booking.',
                            'error',
                            5000
                        );
                        
                    } else {
                        showNotification(
                            `❌ ${validationData.message || 'Unable to process your request. Please try again.'}`,
                            'error',
                            5000
                        );
                    }
                    
                    // ✅ CRITICAL: DO NOT REDIRECT ON ANY ERROR
                }
            })
            .catch(error => {
                console.error('Validation error:', error);
                
                checkoutBtn.innerHTML = originalContent;
                checkoutBtn.disabled = false;
                
                showNotification(
                    '❌ Validation failed. Please check your internet connection and try again.',
                    'error',
                    5000
                );
            });
        }

        // ✅ Show Unavailable Items Modal
        function showUnavailableItemsModal(unavailableItems, validationErrors) {
            const itemsList = unavailableItems.map((item, index) => {
                let icon = 'fa-exclamation-triangle';
                let bgColor = 'bg-gradient-to-r from-red-50 to-pink-50';
                let borderColor = 'border-red-500';
                let iconColor = 'text-red-600';
                let badgeColor = 'bg-red-100 text-red-800';
                
                if (item.reason && item.reason.includes('blocked')) {
                    icon = 'fa-tools';
                    bgColor = 'bg-gradient-to-r from-yellow-50 to-orange-50';
                    borderColor = 'border-yellow-500';
                    iconColor = 'text-yellow-600';
                    badgeColor = 'bg-yellow-100 text-yellow-800';
                } else if (item.reason && item.reason.includes('already booked')) {
                    icon = 'fa-calendar-times';
                    bgColor = 'bg-gradient-to-r from-blue-50 to-indigo-50';
                    borderColor = 'border-blue-500';
                    iconColor = 'text-blue-600';
                    badgeColor = 'bg-blue-100 text-blue-800';
                }
                
                return `
                    <div class="${bgColor} border-l-4 ${borderColor} p-5 rounded-xl mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm border">
                                <i class="fas ${icon} ${iconColor} text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">
                                        ${item.unit?.unitName || 'Unknown Unit'}
                                        <span class="ml-2 px-2 py-1 ${badgeColor} text-xs font-medium rounded-full">
                                            ${item.unit?.unitType === 'room' ? '🏨 Room' : '🏡 Cottage'}
                                        </span>
                                    </h4>
                                    <span class="text-sm font-medium px-3 py-1 rounded-full bg-red-100 text-red-700">
                                        Not Available
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 mb-3 font-medium">
                                    <i class="fas ${icon} ${iconColor} mr-2"></i>
                                    ${item.reason || 'Not available for selected dates'}
                                </p>
                                
                                ${item.cartItemID ? `
                                <div class="flex justify-end">
                                    <button onclick="removeUnavailableItem(${item.cartItemID})" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center text-sm">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        Remove Item
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            const modalHTML = `
                <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-white p-3 rounded-full shadow-lg mr-4">
                                        <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">Booking Issues Found</h3>
                                        <p class="text-red-100 mt-1">${unavailableItems.length} item(s) need attention</p>
                                    </div>
                                </div>
                                <button onclick="closeModal()" class="text-white hover:text-red-200 text-3xl transition-transform hover:rotate-90">
                                    &times;
                                </button>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="p-6 overflow-y-auto max-h-[55vh]">
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-bold text-blue-800 mb-1">What happened?</h4>
                                        <p class="text-blue-700">Some accommodations in your cart are no longer available for your selected dates.</p>
                                    </div>
                                </div>
                            </div>
                            
                            ${validationErrors && validationErrors.length > 0 ? `
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-bold text-red-800 mb-1">Validation Errors</h4>
                                        <ul class="list-disc pl-5 text-red-700 space-y-1">
                                            ${validationErrors.map(error => `<li>${error}</li>`).join('')}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            
                            <div class="space-y-4">
                                ${itemsList}
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="border-t border-gray-200 bg-gray-50 p-6">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button onclick="closeModal()" 
                                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-100 transition-all flex-1 flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Continue Shopping
                                </button>
                                <button onclick="removeAllUnavailableItems(${JSON.stringify(unavailableItems.filter(item => item.cartItemID).map(item => item.cartItemID))})" 
                                        class="px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors flex-1 flex items-center justify-center">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Remove All Items
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            showCustomModal(modalHTML);
        }

        // ✅ Show Custom Modal
        function showCustomModal(modalHTML) {
            let modalContainer = document.getElementById('availability-modal-container');
            if (!modalContainer) {
                modalContainer = document.createElement('div');
                modalContainer.id = 'availability-modal-container';
                document.body.appendChild(modalContainer);
            }
            
            modalContainer.innerHTML = modalHTML;
        }

        // ✅ Close Modal
        function closeModal() {
            const modalContainer = document.getElementById('availability-modal-container');
            if (modalContainer) {
                modalContainer.innerHTML = '';
            }
        }

        // ✅ Show Notification Function
        function showNotification(message, type = 'error', duration = 4000) {
            const notificationId = 'notification-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            
            // Create notification element
            const notification = document.createElement('div');
            notification.id = notificationId;
            notification.className = `notification notification-slide-in ${type}`;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            const titles = {
                success: 'Success',
                error: 'Error',
                warning: 'Warning',
                info: 'Information'
            };
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas ${icons[type] || 'fa-info-circle'}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${titles[type] || 'Notification'}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close" onclick="removeNotification('${notificationId}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to container
            const container = document.getElementById('notification-container');
            container.appendChild(notification);
            
            // Add to active set
            activeNotifications.add(notificationId);
            
            // Auto remove after duration
            setTimeout(() => {
                removeNotification(notificationId);
            }, duration);
            
            return notificationId;
        }

        // ✅ Remove specific notification
        function removeNotification(notificationId) {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.remove('notification-slide-in');
                notification.classList.add('notification-slide-out');
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }
            activeNotifications.delete(notificationId);
        }

        // ✅ Clear all notifications
        function clearAllNotifications() {
            const container = document.getElementById('notification-container');
            const notifications = container.querySelectorAll('.notification');
            
            notifications.forEach(notification => {
                notification.classList.remove('notification-slide-in');
                notification.classList.add('notification-slide-out');
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            });
            
            activeNotifications.clear();
        }

        // ✅ Remove Unavailable Item
        function removeUnavailableItem(cartItemId) {
            if (!confirm('Are you sure you want to remove this unavailable item from your cart?')) {
                return;
            }
            
            removeFromCart(cartItemId);
            closeModal();
            setTimeout(() => {
                loadCartItems();
            }, 1000);
        }

        // ✅ Remove All Unavailable Items
        function removeAllUnavailableItems(cartItemIds) {
            if (!confirm('Are you sure you want to remove all unavailable items from your cart?')) {
                return;
            }
            
            if (!Array.isArray(cartItemIds) || cartItemIds.length === 0) {
                showNotification('No items to remove', 'info');
                closeModal();
                return;
            }
            
            const modalContainer = document.getElementById('availability-modal-container');
            if (modalContainer) {
                const loadingHTML = `
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
                            <div class="spinner-modern w-16 h-16 mx-auto mb-4"></div>
                            <p class="text-xl font-medium text-gray-700">Removing items...</p>
                        </div>
                    </div>
                `;
                modalContainer.innerHTML = loadingHTML;
            }
            
            const removePromises = cartItemIds.map(cartItemId => 
                fetch(`/api/cart/remove/${cartItemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
            );
            
            Promise.all(removePromises)
                .then(() => {
                    showNotification('All unavailable items removed successfully!', 'success');
                    closeModal();
                    setTimeout(() => {
                        loadCartItems();
                        updateNavbarCartBadge();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error removing items:', error);
                    showNotification('Failed to remove some items. Please try again.', 'error');
                    closeModal();
                });
        }

        function removeFromCart(cartItemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Removing...';
            button.disabled = true;
            button.classList.add('btn-disabled');
            
            fetch(`/api/cart/remove/${cartItemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Item removed from cart successfully!', 'success');
                    setTimeout(() => {
                        loadCartItems();
                        updateNavbarCartBadge();
                    }, 500);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                    button.classList.remove('btn-disabled');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to remove item. Please try again.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
                button.classList.remove('btn-disabled');
            });
        }

        function removeAllRooms() {
            if (!confirm('Are you sure you want to remove all room items from your cart? This cannot be undone.')) {
                return;
            }
            
            fetch('/api/cart/items', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.items && data.items.length > 0) {
                    const roomItems = data.items.filter(item => 
                        item.unit && item.unit.unitType === 'room'
                    );
                    
                    if (roomItems.length === 0) {
                        showNotification('No room items found in cart', 'info');
                        return;
                    }
                    
                    const removePromises = roomItems.map(item => 
                        fetch(`/api/cart/remove/${item.cartItemID}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                    );
                    
                    Promise.all(removePromises)
                        .then(() => {
                            showNotification('All room items removed from cart successfully!', 'success');
                            setTimeout(() => {
                                loadCartItems();
                                updateNavbarCartBadge();
                            }, 500);
                        })
                        .catch(error => {
                            console.error('Error removing room items:', error);
                            showNotification('Failed to remove some room items', 'error');
                        });
                }
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
                showNotification('Failed to load cart items', 'error');
            });
        }

        function removeAllCottages() {
            if (!confirm('Are you sure you want to remove all cottage items from your cart? This cannot be undone.')) {
                return;
            }
            
            fetch('/api/cart/items', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.items && data.items.length > 0) {
                    const cottageItems = data.items.filter(item => 
                        item.unit && item.unit.unitType === 'cottage'
                    );
                    
                    if (cottageItems.length === 0) {
                        showNotification('No cottage items found in cart', 'info');
                        return;
                    }
                    
                    const removePromises = cottageItems.map(item => 
                        fetch(`/api/cart/remove/${item.cartItemID}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                    );
                    
                    Promise.all(removePromises)
                        .then(() => {
                            showNotification('All cottage items removed from cart successfully!', 'success');
                            setTimeout(() => {
                                loadCartItems();
                                updateNavbarCartBadge();
                            }, 500);
                        })
                        .catch(error => {
                            console.error('Error removing cottage items:', error);
                            showNotification('Failed to remove some cottage items', 'error');
                        });
                }
            })
            .catch(error => {
                console.error('Error fetching cart items:', error);
                showNotification('Failed to load cart items', 'error');
            });
        }

        function showEntranceFeeAlert() {
            showNotification('Cannot proceed to booking. Please remove cottage items or contact management to set up an entrance fee.', 'error');
        }

        function getUnitImage(unit) {
            let imageUrl = '';
            
            try {
                if (unit.images) {
                    const images = typeof unit.images === 'string' ? JSON.parse(unit.images) : unit.images;
                    if (Array.isArray(images) && images.length > 0 && images[0]) {
                        imageUrl = images[0];
                        if (!imageUrl.startsWith('http')) {
                            imageUrl = `/storage/${imageUrl}`;
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing images:', e);
            }
            
            return imageUrl;
        }

        function updateNavbarCartBadge() {
            fetch('/api/cart/count', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge) {
                    if (data.success && data.count > 0) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = 'flex';
                        cartBadge.classList.add('animate-pulse');
                        setTimeout(() => {
                            cartBadge.classList.remove('animate-pulse');
                        }, 1000);
                    } else {
                        cartBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart badge:', error);
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge) {
                    cartBadge.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>