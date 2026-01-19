<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled - Villa Elena</title>
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
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .cursive-font {
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #721c24;
            margin: 0;
        }
        .subtitle {
            color: #491217;
            font-size: 14px;
            margin-top: 10px;
        }
        .content {
            padding: 40px 30px;
            color: #333;
        }
        .content h2 {
            color: #721c24;
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
            border-left: 4px solid #dc3545;
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
        .refund-section {
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #6c757d;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 500;
        }
        .button-primary {
            background-color: #dc3545;
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
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .info-box {
            background-color: #e2e3e5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #6c757d;
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
            <h2>Booking Cancellation Notice</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>This email confirms that your booking at Villa Elena has been <strong>cancelled</strong>.</p>
            
            <div class="info-box">
                <p><strong>Please note:</strong> If you have made any payments, our team will process your refund according to our cancellation policy.</p>
            </div>
            
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><strong>#{{ $booking->bookingID }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-cancelled">Cancelled</span>
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
                
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value">
                        ₱{{ number_format($booking->totalPrice, 2) }}
                    </span>
                </div>
                
                @if($cancellationReason)
                <div class="detail-row">
                    <span class="detail-label">Cancellation Reason:</span>
                    <span class="detail-value">{{ $cancellationReason }}</span>
                </div>
                @endif
            </div>
            
            @if($refundAmount > 0)
            <div class="refund-section">
                <h3>Refund Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Refund Amount:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: 700;">
                        ₱{{ number_format($refundAmount, 2) }}
                    </span>
                </div>
                @if($refundMethod)
                <div class="detail-row">
                    <span class="detail-label">Refund Method:</span>
                    <span class="detail-value">{{ ucfirst($refundMethod) }}</span>
                </div>
                @endif
                <p style="margin-top: 15px; font-size: 14px;">
                    <strong>Note:</strong> Refunds typically take 3-5 business days to process. You will receive another email once the refund has been processed.
                </p>
            </div>
            @endif
            
            <p><strong>We're sorry to see you go!</strong></p>
            <p>If this cancellation was a mistake or if you'd like to reschedule, please contact us immediately.</p>
            
            <center>
                <a href="{{ config('app.url') }}/contact" class="button button-primary">Contact Us</a>
                <a href="{{ config('app.url') }}/booking" class="button">Book Again</a>
            </center>
            
            <p style="margin-top: 30px;">We hope to have the opportunity to serve you in the future.</p>
            
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