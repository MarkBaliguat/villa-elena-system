<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Completed - Villa Elena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .cursive-font {
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #155724;
            margin: 0;
        }
        .subtitle {
            color: #0c4128;
            font-size: 14px;
            margin-top: 10px;
        }
        .content {
            padding: 40px 30px;
            color: #333;
        }
        .content h2 {
            color: #155724;
            margin-bottom: 20px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
            text-align: right;
        }
        .total-row {
            background-color: #28a745;
            color: #fff;
            padding: 15px 20px;
            margin: 20px -20px -20px -20px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #28a745;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 500;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed {
            background-color: #28a745;
            color: #fff;
        }
        .thank-you {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 1px dashed #28a745;
        }
        .rating-section {
            text-align: center;
            margin: 30px 0;
        }
        .stars {
            font-size: 24px;
            color: #ffc107;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 class="cursive-font">Villa Elena</h1>
            <p class="subtitle">Family Resort & Agri-Tourism Farm</p>
        </div>
        
        <div class="content">
            <h2>Your Stay Has Been Completed! ✨</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>We hope you had a wonderful stay at Villa Elena Family Resort! Your booking has been marked as <strong>completed</strong>.</p>
            
            <div class="thank-you">
                <h3>Thank You for Choosing Villa Elena! ❤️</h3>
                <p>We truly appreciate your trust in us and hope you enjoyed every moment of your stay.</p>
            </div>
            
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><strong>#{{ $booking->bookingID }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-completed">Completed</span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Type:</span>
                    <span class="detail-value">{{ ucfirst(str_replace('-', ' ', $booking->bookingType)) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-in Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($cart->checkInDate)->format('F d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-out Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($cart->checkOutDate)->format('F d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Number of Guests:</span>
                    <span class="detail-value">{{ $cart->numGuests }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Unit(s):</span>
                    <span class="detail-value">
                        @foreach($units as $unit)
                            {{ $unit->unitName }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </div>
                
                <div class="total-row">
                    <div class="detail-row" style="border: none; color: #fff;">
                        <span class="detail-label" style="color: #fff;">Total Amount Paid:</span>
                        <span class="detail-value" style="color: #fff; font-size: 20px; font-weight: 700;">
                            ₱{{ number_format($booking->totalPrice, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="rating-section">
                <p><strong>How was your experience?</strong></p>
                <p>We would love to hear your feedback!</p>
                <div class="stars">
                    ★ ★ ★ ★ ★
                </div>
                <a href="{{ config('app.url') }}/feedback?booking={{ $booking->bookingID }}" class="button">Share Your Feedback</a>
            </div>
            
            <p><strong>Looking forward to your next visit!</strong></p>
            <p>Book your next getaway with us and enjoy special returning guest discounts.</p>
            
            <center>
                <a href="{{ config('app.url') }}/booking" class="button">Book Your Next Stay</a>
            </center>
            
            <p style="margin-top: 30px;">We hope to welcome you back soon!</p>
            
            <p style="margin-top: 20px;">
                <strong>The Villa Elena Team</strong><br>
                Email: <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a><br>
                Phone: {{ config('app.contact_phone') }}
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>