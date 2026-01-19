<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Event Completed - Villa Elena</title>
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
        .event-recap {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
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
            <h2>✨ Special Event Successfully Completed!</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>Thank you for hosting your special event at Villa Elena Family Resort! We hope "{{ $booking->eventType }}" was everything you dreamed of and more.</p>
            
            <div class="thank-you">
                <h3>🎉 Event Successfully Completed!</h3>
                <p>It was our pleasure to be part of your special day. We hope every moment was memorable!</p>
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
                    <span class="detail-label">Event Type:</span>
                    <span class="detail-value">{{ $booking->eventType }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($cart->checkInDate)->format('F d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Event Time:</span>
                    <span class="detail-value">
                        {{ \Carbon\Carbon::parse($booking->eventStartTime)->format('g:i A') }} - 
                        {{ \Carbon\Carbon::parse($booking->eventEndTime)->format('g:i A') }}
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Number of Guests:</span>
                    <span class="detail-value">{{ $cart->numGuests }}</span>
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
            
            <div class="event-recap">
                <h4>📸 Event Recap</h4>
                <p>We hope you captured beautiful moments during your event at {{ $units->first()->unitName ?? 'our venue' }}.</p>
                @if($booking->specialRequirements)
                <p><strong>Special Requirements Met:</strong> {{ $booking->specialRequirements }}</p>
                @endif
            </div>
            
            <div class="rating-section">
                <p><strong>How was your event experience?</strong></p>
                <p>We would love to hear your feedback and see your photos!</p>
                <div class="stars">
                    ★ ★ ★ ★ ★
                </div>
                <a href="{{ config('app.url') }}/feedback/special-event?booking={{ $booking->bookingID }}" class="button">Share Your Experience</a>
            </div>
            
            <p><strong>Looking forward to hosting your next special occasion!</strong></p>
            <p>As a valued client, you'll receive priority booking for future events.</p>
            
            <center>
                <a href="{{ config('app.url') }}/special-events" class="button">Plan Your Next Event</a>
            </center>
            
            <p style="margin-top: 30px;">Thank you for choosing Villa Elena for your special day!</p>
            
            <p style="margin-top: 20px;">
                <strong>The Villa Elena Events Team</strong><br>
                📧 <a href="mailto:events@villa-elena.com">events@villa-elena.com</a><br>
                📞 {{ config('app.contact_phone') }}
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>