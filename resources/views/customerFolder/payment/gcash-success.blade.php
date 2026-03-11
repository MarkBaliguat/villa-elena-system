<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFFFFF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        
        .success-container {
            max-width: 480px;
            width: 100%;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            text-align: center;
            animation: fadeIn 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.4s ease;
        }
        
        .success-icon i {
            color: white;
            font-size: 2rem;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: #6B7280;
            font-size: 0.9375rem;
            margin-bottom: 2rem;
            font-weight: 400;
        }
        
        .booking-details {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border: 1px solid #E5E7EB;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .detail-item:first-child {
            padding-top: 0;
        }
        
        .detail-label {
            color: #6B7280;
            font-weight: 400;
            font-size: 0.875rem;
        }
        
        .detail-value {
            color: #111827;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .amount-paid {
            color: #10b981 !important;
            font-size: 1.125rem !important;
            font-weight: 700 !important;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000000;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }
        
        .btn-secondary {
            background: #FFFFFF;
            color: #374151;
            border: 1.5px solid #E5E7EB;
        }
        
        .btn-secondary:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }
        
        .loading {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2.5px solid #E5E7EB;
            border-radius: 50%;
            border-top-color: #10b981;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading-state {
            text-align: center;
            padding: 2rem 1rem;
        }
        
        .loading-text {
            color: #6B7280;
            margin-top: 1rem;
            font-size: 0.9375rem;
            font-weight: 400;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: #D1FAE5;
            color: #065F46;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .payment-method-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .gcash-logo {
            color: #007DFF;
            font-size: 1.125rem;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .success-container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.375rem;
            }
            
            .subtitle {
                font-size: 0.875rem;
            }
            
            .success-icon {
                width: 64px;
                height: 64px;
            }
            
            .success-icon i {
                font-size: 1.75rem;
            }
            
            .booking-details {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1>Payment Successful</h1>
        <p class="subtitle">Your GCash payment has been processed</p>
        
        <div class="booking-details" id="booking-info">
            <div class="loading-state">
                <div class="loading"></div>
                <p class="loading-text">Verifying payment...</p>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('customer.bookings') }}" class="action-btn btn-primary">
                <i class="fas fa-calendar-check"></i>
                <span>View My Bookings</span>
            </a>
            <a href="{{ route('home') }}" class="action-btn btn-secondary">
                <i class="fas fa-home"></i>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
    
    <script>
        //  Get payment intent ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const paymentIntentId = urlParams.get('payment_intent_id');
        
        console.log('✅ Payment Success Page Loaded');
        console.log('Payment Intent ID:', paymentIntentId);
        
        if (paymentIntentId) {
            //  Call verify endpoint
            console.log('🔍 Calling verification endpoint...');
            
            fetch(`/payment/gcash/verify?payment_intent_id=${paymentIntentId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ Verification response:', data);
                
                if (data.success) {
                    console.log('✅ Payment verified - Booking created');
                    displayBookingDetails(data.booking, data.payment);
                    showNotification('Payment successful! Booking confirmed.', 'success');
                    
                } else {
                    console.error('❌ Verification failed:', data.message);
                    showNotification('Payment verification failed. Redirecting...', 'error');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/payment/gcash/failed';
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('❌ Error during verification:', error);
                showError('Error verifying payment. Please check your bookings.');
            });
        } else {
            console.error('❌ No payment intent ID found');
            showError('Invalid payment information.');
        }
        
        function displayBookingDetails(booking, payment) {
            const bookingInfo = document.getElementById('booking-info');
            bookingInfo.innerHTML = `
                <div class="detail-item">
                    <span class="detail-label">Reference Number</span>
                    <span class="detail-value">${payment.paymentReference || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#${booking.bookingID}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value amount-paid">₱${parseFloat(payment.amountPaid).toFixed(2)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">
                        <span class="payment-method-badge">
                            <i class="fab fa-google-pay gcash-logo"></i>
                            <span>GCash</span>
                        </span>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge">
                            <i class="fas fa-check-circle"></i>
                            <span>${booking.bookingStatus.toUpperCase()}</span>
                        </span>
                    </span>
                </div>
            `;
        }
        
        function showError(message) {
            const bookingInfo = document.getElementById('booking-info');
            bookingInfo.innerHTML = `
                <div style="text-align: center; padding: 1rem;">
                    <i class="fas fa-exclamation-circle" style="color: #EF4444; font-size: 2rem; margin-bottom: 0.75rem;"></i>
                    <p style="color: #EF4444; font-weight: 500; font-size: 0.9375rem;">${message}</p>
                </div>
            `;
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 0.875rem 1.5rem;
                border-radius: 10px;
                color: white;
                font-weight: 500;
                font-size: 0.9375rem;
                z-index: 1000;
                animation: slideInRight 0.3s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
            `;
            document.head.appendChild(style);
            
            if (type === 'success') {
                notification.style.backgroundColor = '#10b981';
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else {
                notification.style.backgroundColor = '#EF4444';
                notification.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>