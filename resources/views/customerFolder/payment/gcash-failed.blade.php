<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Villa Elena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/sunflower1.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .failed-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(239, 68, 68, 0.2);
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
        
        .failed-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #EF4444, #DC2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: shake 0.5s ease;
        }
        
        .failed-icon i {
            color: white;
            font-size: 4rem;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #DC2626;
            margin-bottom: 1rem;
        }
        
        .error-details {
            background: #FEF2F2;
            border: 2px solid #FEE2E2;
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .error-details p {
            color: #991B1B;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .error-details ul {
            text-align: left;
            list-style: none;
            padding: 0;
        }
        
        .error-details li {
            color: #6B7280;
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .error-details li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #DC2626;
            font-weight: bold;
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
        
        .btn-retry {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000;
        }
        
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6B7280, #4B5563);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <div class="failed-icon">
            <i class="fas fa-times"></i>
        </div>
        
        <h1>Payment Failed</h1>
        <p style="color: #6B7280; font-size: 1.1rem; margin-bottom: 2rem;">
            We couldn't process your GCash payment. Don't worry, no charges were made.
        </p>
        
        <div class="error-details">
            <p><strong>What could have gone wrong?</strong></p>
            <ul>
                <li>Payment was cancelled or timed out</li>
                <li>Insufficient GCash balance</li>
                <li>Network connection issues</li>
                <li>GCash service temporarily unavailable</li>
            </ul>
        </div>
        
        <div style="background: #FEF3C7; border: 2px solid #FDE047; border-radius: 12px; padding: 1rem; margin: 1.5rem 0;">
            <p style="color: #854D0E; font-weight: 500; margin: 0;">
                <i class="fas fa-info-circle"></i>
                Your booking is still saved. You can try paying again or choose a different payment method.
            </p>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="{{ route('booking.page') }}" class="action-btn btn-retry">
                <i class="fas fa-redo"></i>
                Try Again
            </a>
            <a href="{{ route('customer.bookings') }}" class="action-btn btn-secondary">
                <i class="fas fa-calendar-check"></i>
                View Bookings
            </a>
        </div>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #E5E7EB;">
            <p style="color: #6B7280; font-size: 0.9rem;">
                Need help? Contact us at <a href="mailto:support@villaelena.com" style="color: #FFD700; font-weight: 600;">support@villaelena.com</a>
            </p>
        </div>
    </div>
</body>
</html>