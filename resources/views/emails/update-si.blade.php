<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BL info updated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">BL info updated</h2>
        
        <p>Dear Valued Customer,</p>
        
        <p>This email is to inform you that customer has made some changes to the BL info.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Booking Number:</strong> {{ $booking->booking_number }}</p>
            <p style="margin: 5px 0;"><strong>SI Number:</strong> {{ $shippingInstruction->sub_booking_number }}</p>
        </div>
        
        <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>Your Shipping Team</p>
    </div>
</body>
</html>
