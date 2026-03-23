<!DOCTYPE html>
<html>
<head>
    <title>Service Expiry Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; background-color: #ffffff; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
        .highlight { 
            background-color: #fff3cd; 
            padding: 15px; 
            border-left: 4px solid #ffc107; 
            margin: 20px 0; 
            border-radius: 4px;
        }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="text-align: center;"><img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;"></div>
            <h2>Service Expiry Reminder</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $userName }},</p>
            
            <p>This is a friendly reminder that your service is expiring soon.</p>
            
            <div class="highlight">
                <strong>📋 Service Details:</strong><br>
                <strong>Service:</strong> {{ $serviceName }}<br>
                <strong>Service Description:</strong> {{ $serviceDescription }}<br>
                <strong>Service Duration:</strong> {{ $serviceDuration }}<br>
                <strong>Renew Cost:</strong> $ {{ $renewCost }}<br>
                <strong>Expiry Date:</strong> {{ $endDate }}<br>
                <strong>Days Remaining:</strong> {{ $daysLeft }} days
            </div>
            
            <p>To continue with {{ $serviceName }} without interruption, please contact us or reply to this email to renew your service.</p>
            
            <div style="text-align: center;">
                {{-- <a href="{{ url('/contact') }}" class="btn">Renew Service</a> --}}
            </div>
            
            <p>Thank you for choosing our services!</p>
            
            <p>Best regards,<br>
            <strong>Cloudtach Support</strong></p>
        </div>
        
        <div class="footer">
            <!-- <p>This is an automated reminder. Please do not reply to this email.</p> -->
            <p>© {{ date('Y') }} Cloudtach. All rights reserved.</p>
        </div>
    </div>
</body>
</html>