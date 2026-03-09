<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Event Booking Confirmation - Villa Elena</title>
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
            background: linear-gradient(135deg, #FFF4D5 0%, #FFE5A0 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .cursive-font {
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #333;
            margin: 0;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }
        .content {
            padding: 40px 30px;
            color: #333;
        }
        .content h2 {
            color: #000;
            margin-bottom: 20px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }
        .booking-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
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
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            margin: 20px -20px -20px -20px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
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
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .event-highlight {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #007bff;
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
            <h2>🎉 Special Event Booking Confirmed!</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>We're thrilled to confirm your special event booking at Villa Elena Family Resort!</p>
            
            <div class="event-highlight">
                <h3>🎯 {{ $booking->eventType }}</h3>
                <p>Your special day is officially reserved!</p>
            </div>
            
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><strong>#{{ $booking->bookingID }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $booking->bookingStatus }}">
                            {{ ucfirst($booking->bookingStatus) }}
                        </span>
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
                
                @if($booking->specialRequirements)
                <div class="detail-row">
                    <span class="detail-label">Special Requirements:</span>
                    <span class="detail-value">{{ $booking->specialRequirements }}</span>
                </div>
                @endif
                
                <div class="total-row">
                    <div class="detail-row" style="border: none; color: #fff;">
                        <span class="detail-label" style="color: #fff;">Total Amount:</span>
                        <span class="detail-value" style="color: #fff; font-size: 20px; font-weight: 700;">
                            ₱{{ number_format($booking->totalPrice, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <p><strong>📋 Important Information:</strong></p>
            <ul style="color: #555; line-height: 1.8;">
                <li>Please arrive 30 minutes before your event start time</li>
                <li>Bring valid ID for verification</li>
                <li>Contact us for any special setup requirements</li>
            </ul>
            
            <center>
                <a href="{{ config('app.url') }}/special-bookings/{{ $booking->bookingID }}" class="button">View Booking Details</a>
            </center>
            
            <p style="margin-top: 30px;">For any questions or special arrangements, please contact our events team:</p>
            
            <p style="margin-top: 20px;">
                 <strong>The Villa Elena Team</strong><br>
                Email: <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a><br>
                Phone: 0917-301-0790
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>