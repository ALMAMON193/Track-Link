<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Hire Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            background-color: #1d4ed8;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Hello {{ $notifiable->name }},</h2>

        <p>You have received a new hire request from <strong>{{ $shipperName }}</strong>.</p>

        <p><strong>Requested Date:</strong> {{ $date }}</p>
        <p><strong>Reason:</strong> {{ $reason ?? 'No reason provided' }}</p>

        <p>Please respond within 24 hours to avoid automatic expiration.</p>

        <p>Thank you for using our platform!</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Your Company. All rights reserved.
    </div>
</div>
</body>
</html>
