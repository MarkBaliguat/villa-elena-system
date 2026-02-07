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
        
        .failed-container {
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
        
        .failed-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #EF4444, #DC2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.4s ease;
        }
        
        .failed-icon i {
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
        
        .error-details {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .error-details p {
            color: #111827;
            font-weight: 500;
            margin-bottom: 1rem;
            font-size: 0.875rem;
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
            font-size: 0.875rem;
        }
        
        .error-details li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #EF4444;
            font-weight: bold;
            font-size: 1.125rem;
        }
        
        .info-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            text-align: left;
        }
        
        .info-box i {
            color: #F59E0B;
            font-size: 1.125rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        
        .info-box p {
            color: #78350F;
            font-weight: 400;
            margin: 0;
            font-size: 0.875rem;
            line-height: 1.5;
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
        
        .btn-retry {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000000;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.2);
        }
        
        .btn-retry:hover {
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
        
        .support-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }
        
        .support-section p {
            color: #6B7280;
            font-size: 0.875rem;
            margin: 0;
        }
        
        .support-section a {
            color: #F59E0B;
            font-weight: 600;
            text-decoration: none;
        }
        
        .support-section a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .failed-container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.375rem;
            }
            
            .subtitle {
                font-size: 0.875rem;
            }
            
            .failed-icon {
                width: 64px;
                height: 64px;
            }
            
            .failed-icon i {
                font-size: 1.75rem;
            }
            
            .error-details {
                padding: 1.25rem;
            }
            
            .info-box {
                padding: 0.875rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <div class="failed-icon">
            <i class="fas fa-times"></i>
        </div>
        
        <h1>Payment Failed</h1>
        <p class="subtitle">We couldn't process your GCash payment</p>
        
        <div class="error-details">
            <p>What could have gone wrong?</p>
            <ul>
                <li>Payment was cancelled or timed out</li>
                <li>Insufficient GCash balance</li>
                <li>Network connection issues</li>
                <li>GCash service temporarily unavailable</li>
            </ul>
        </div>
        
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Your booking is still saved. You can try paying again or choose a different payment method.</p>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('booking.page') }}" class="action-btn btn-retry">
                <i class="fas fa-redo"></i>
                <span>Try Again</span>
            </a>
            <a href="{{ route('customer.bookings') }}" class="action-btn btn-secondary">
                <i class="fas fa-calendar-check"></i>
                <span>View Bookings</span>
            </a>
        </div>
        
        <div class="support-section">
            <p>
                Need help? Contact us at 
                <a href="mailto:support@villaelena.com">support@villaelena.com</a>
            </p>
        </div>
    </div>
</body>
</html>