<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Villa Elena</title>
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
        .features {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .features li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
        }
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #4CAF50;
            font-weight: bold;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
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
            <h2>Welcome, {{ $user->name }}! 🎉</h2>
            
            <p>Salamat sa pag-register sa Villa Elena Family Resort! Kami ay excited na maging bahagi ka ng aming community.</p>
            
            <p>Sa iyong account, makaka-access ka na ng:</p>
            
            <div class="features">
                <ul>
                    <li>Easy room and cottage booking</li>
                    <li>Real-time availability checking</li>
                    <li>Booking history and management</li>
                </ul>
            </div>
            
            <p>Ready na mag-book ng iyong next getaway?</p>
            
            <center>
                <a href="{{ config('app.url') }}/booking" class="button">Start Booking Now</a>
            </center>
            
            <p style="margin-top: 30px;">Kung may tanong ka, feel free to contact us anytime. We're here to help!</p>
            
            <p style="margin-top: 20px;">
                <strong>Villa Elena Team</strong><br>
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Villa Elena Family Resort. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>