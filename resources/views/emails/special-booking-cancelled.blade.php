<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Event Cancelled - Villa Elena</title>
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
            color: #856404;
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
        .total-row {
            background-color: #dc3545;
            color: #fff;
            padding: 15px 20px;
            margin: 20px -20px -20px -20px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #dc3545;
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
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .cancellation-notice {
            background-color: #f8d7da;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .refund-info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
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
            <h2>❌ Special Event Booking Cancelled</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>This email confirms that your special event booking has been cancelled as requested.</p>
            
            <div class="cancellation-notice">
                <h3>⚠️ Booking Cancelled</h3>
                <p>Your special event "{{ $booking->eventType }}" has been cancelled.</p>
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
                    <span class="detail-label">Event Type:</span>
                    <span class="detail-value">{{ $booking->eventType }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($cart->checkInDate)->format('F d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Venue:</span>
                    <span class="detail-value">
                        @foreach($units as $unit)
                            {{ $unit->unitName }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </div>
                
                <div class="total-row">
                    <div class="detail-row" style="border: none; color: #fff;">
                        <span class="detail-label" style="color: #fff;">Total Amount:</span>
                        <span class="detail-value" style="color: #fff; font-size: 20px; font-weight: 700;">
                            ₱{{ number_format($booking->totalPrice, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            
            @if($refundAmount > 0)
            <div class="refund-info">
                <h4>💰 Refund Information</h4>
                <p>Refund Amount: <strong>₱{{ number_format($refundAmount, 2) }}</strong></p>
                <p>Refund Method: {{ $refundMethod ?? 'To be determined' }}</p>
                <p>Expected Processing Time: 7-14 business days</p>
            </div>
            @endif
            
            <p><strong>📝 Important Notes:</strong></p>
            <ul style="color: #555; line-height: 1.8;">
                <li>All event services have been stopped</li>
                <li>Any payments made are being processed for refund (if applicable)</li>
                <li>You will receive separate communication regarding refunds</li>
                <li>If this was a mistake, please contact us immediately</li>
            </ul>
            
            <center>
                <a href="{{ config('app.url') }}/special-bookings/{{ $booking->bookingID }}" class="button">View Booking Details</a>
            </center>
            
            <p style="margin-top: 30px;">We're sorry to see you go and hope to host your future events!</p>
            
            <p style="margin-top: 20px;">
                <strong>The Villa Elena Team</strong><br>
                📧 <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a><br>
                📞 0917-301-0790
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>