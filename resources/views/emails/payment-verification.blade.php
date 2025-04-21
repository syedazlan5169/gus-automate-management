<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .status.confirmed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 2px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payment Verification Status</h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->user->name }},</p>

        <p>This email is to inform you about the status of your payment verification.</p>

        @if($payment_response === 'confirmed')
            <div class="status confirmed">
                <h3>Payment Confirmed</h3>
                <p>Your payment has been successfully verified and confirmed.</p>
            </div>
            <p>Transaction Details:</p>
            <ul>
                <li>Invoice Number: {{ $invoice->invoice_number }}</li>
                <li>Amount: {{ $payment->payment_amount }}</li>
                <li>Date: {{ $payment->payment_date }}</li>
            </ul>
        @else
            <div class="status rejected">
                <h3>Payment Rejected</h3>
                <p>We're sorry, but your payment has been rejected.</p>
            </div>
            <p>Transaction Details:</p>
            <ul>
                <li>Invoice Number: {{ $invoice->invoice_number }}</li>
                <li>Amount: {{ $payment->payment_amount }}</li>
                <li>Date: {{ $payment->payment_date }}</li>
            </ul>
            <p>If you believe this is an error, please contact our support team for assistance.</p>
        @endif

        <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>
    </div>

    <div class="footer">
        <p>Best regards,<br>{{ config('app.name') }} Team</p>
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</body>
</html>
