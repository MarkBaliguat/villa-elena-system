<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            background-color: #000;
            color: #fff;
            padding: 15px 20px;
            margin: 20px -20px -20px -20px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #000;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 class="cursive-font">Villa Elena</h1>
            <p class="subtitle">Family Resort & Agri-Tourism Farm</p>
        </div>
        
        <div class="content">
            <h2>Booking Confirmation</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>Thank you for choosing Villa Elena! Your booking has been successfully created.</p>
            
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
            
            <p><strong>What's Next?</strong></p>
            <ul style="color: #555; line-height: 1.8;">
                <li>Please arrive at the resort at your scheduled check-in time</li>
                <li>Bring a valid ID for verification</li>
                <li>Contact us if you need to make any changes to your booking</li>
            </ul>
            
            <center>
                <a href="{{ config('app.url') }}/my-bookings" class="button">View My Bookings</a>
            </center>
            
            <p style="margin-top: 30px;">If you have any questions or need assistance, feel free to contact us:</p>
            
             <strong>The Villa Elena Team</strong><br>
                Email: <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a><br>
                Phone: 0917-301-0790
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>