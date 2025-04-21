<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Uploaded</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .invoice-details {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice Issued</h2>
    </div>

    <p>Dear {{ $booking->user->name }},</p>

    <p>This email is to inform you that your invoice for booking #{{ $booking->booking_number }} has been issued and uploaded to our system.</p>

    <div class="invoice-details">
        <h3>Invoice Details:</h3>
        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</p>
        <p><strong>Amount:</strong> ${{ number_format($invoice->invoice_amount, 2) }}</p>
    </div>

    <p>You can view the full details of your invoice by logging into your account.</p>

    <div class="footer">
        <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>
        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>
</body>
</html>
