<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFFBF0 0%, #FFF8E1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .success-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: scaleIn 0.5s ease 0.2s both;
        }
        
        .success-icon i {
            color: white;
            font-size: 4rem;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .checkmark {
            animation: checkmark 0.8s ease 0.4s both;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(0deg);
            }
            50% {
                transform: scale(1.2) rotate(180deg);
            }
            100% {
                transform: scale(1) rotate(360deg);
            }
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 1rem;
        }
        
        .booking-details {
            background: #F9FAFB;
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #E5E7EB;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #D1D5DB;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #6B7280;
            font-weight: 500;
        }
        
        .detail-value {
            color: #1F2937;
            font-weight: 600;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #1F2937, #374151);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check checkmark"></i>
        </div>
        
        <h1>Payment Successful!</h1>
        <p style="color: #6B7280; font-size: 1.1rem; margin-bottom: 2rem;">
            Your GCash payment has been processed successfully.
        </p>
        
        <div class="booking-details" id="booking-info">
            <div style="text-align: center; padding: 2rem;">
                <div class="loading"></div>
                <p style="color: #6B7280; margin-top: 1rem;">Verifying your payment and creating booking...</p>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="{{ route('customer.bookings') }}" class="action-btn btn-primary">
                <i class="fas fa-calendar-check"></i>
                View My Bookings
            </a>
            <a href="{{ route('home') }}" class="action-btn btn-secondary">
                <i class="fas fa-home"></i>
                Back to Home
            </a>
        </div>
    </div>
    
    <script>
        // ✅ UPDATED: Get payment intent ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const paymentIntentId = urlParams.get('payment_intent_id');
        
        console.log('✅ Payment Success Page Loaded');
        console.log('Payment Intent ID:', paymentIntentId);
        
        if (paymentIntentId) {
            // ✅ Call verify endpoint - This is where booking gets created in DB
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
                    // ✅ Payment verified and booking created successfully
                    console.log('✅ Payment verified - Booking created');
                    displayBookingDetails(data.booking, data.payment);
                    showNotification('✅ Payment successful! Booking confirmed.', 'success');
                    
                } else {
                    // ❌ Payment failed or verification error
                    console.error('❌ Verification failed:', data.message);
                    showNotification('❌ Payment verification failed. Redirecting...', 'error');
                    
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
                    <span class="detail-label">Booking Reference</span>
                    <span class="detail-value">${payment.paymentReference || 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#${booking.bookingID}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value" style="color: #10b981; font-size: 1.25rem;">
                        ₱${parseFloat(payment.amountPaid).toFixed(2)}
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">
                        <i class="fab fa-google-pay" style="color: #007DFF;"></i> GCash
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #10b981;">
                        <i class="fas fa-check-circle"></i> ${booking.bookingStatus.toUpperCase()}
                    </span>
                </div>
            `;
        }
        
        function showError(message) {
            const bookingInfo = document.getElementById('booking-info');
            bookingInfo.innerHTML = `
                <div style="text-align: center; padding: 1rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #EF4444; font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p style="color: #EF4444; font-weight: 600;">${message}</p>
                </div>
            `;
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 2rem;
                border-radius: 10px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;
            
            if (type === 'success') {
                notification.style.backgroundColor = '#10b981';
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else {
                notification.style.backgroundColor = '#EF4444';
                notification.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>