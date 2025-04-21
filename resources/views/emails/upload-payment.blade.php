<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt Uploaded</title>
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
        .payment-details {
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payment Receipt Uploaded</h2>
    </div>

    <p>Hello Admin,</p>

    <p>A customer has uploaded a payment receipt for their invoice. Please review the details below:</p>

    <div class="payment-details">
        <h3>Payment Details:</h3>
        <p><strong>Customer Name:</strong> {{ $booking->user->name }}</p>
        <p><strong>Booking Number:</strong> {{ $booking->booking_number }}</p>
        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Payment Amount:</strong> ${{ number_format($payment->payment_amount, 2) }}</p>
        <p><strong>Payment Date:</strong> {{ $payment->payment_date->format('F d, Y H:i:s') }}</p>
        <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
    </div>

    <p>Please verify the payment receipt and update the payment status accordingly.</p>

    <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>

    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}.</p>
    </div>
</body>
</html>