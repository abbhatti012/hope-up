<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOPE UP - @yield('title')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1a73e8;
            padding: 25px 30px;
            text-align: center;
        }
        .header-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .header-logo img {
            height: 40px;
            margin-right: 10px;
        }
        .header-logo h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
            color: #444444;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1a73e8;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin: 15px 0;
        }
        .emergency-contact {
            display: inline-block;
            background-color: #28a745;
            color: white !important;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 10px;
        }
        .divider {
            border-top: 1px solid #e9ecef;
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="header-logo">
                <img src="{{ asset('assets/images/logo1.svg') }}" alt="HOPE UP Logo">
                <h1>HOPE UP</h1>
            </a>
        </div>
        
        <div class="content">
            @yield('content')
        </div>

        <div class="divider"></div>
        
        <div class="footer">
            <p>© {{ date('Y') }} HOPE UP. All rights reserved.</p>
            <p>If you didn't request this email, please ignore it or contact support.</p>
            <a href="tel:+1234567890" class="emergency-contact">
                Emergency: +1 (234) 567-890
            </a>
        </div>
    </div>
</body>
</html>
