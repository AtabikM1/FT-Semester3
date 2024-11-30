<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            color: #333333;
        }
        .message {
            font-size: 16px;
            color: #555555;
            margin: 20px 0;
        }
        .cta-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaaaaa;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">Reset Your Password</div>
        <div class="message">
            Hello, <br><br>
            We received a request to reset your password. Click the button below to reset it. If you didn’t make this request, you can ignore this email.
        </div>
        <a href="{{ reset_link }}" class="cta-button">Reset Password</a>
        <div class="footer">
            If the button doesn't work, copy and paste the following link into your browser: <br>
            <a href="{{ reset_link }}">{{ reset_link }}</a>
        </div>
    </div>
</body>
</html>
