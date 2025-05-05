<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BL with Telex Release Reminder</title>
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
        .booking-details {
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
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BL with Telex Release Reminder</h2>
    </div>

    <p>Dear Admin,</p>

    <p>The following booking will be sailing less than 24 hours from now. Please upload the BL with Telex Release document as soon as possible.</p>

    <div class="booking-details">
        <h3>Booking Details:</h3>
        <p><strong>Booking Number:</strong> {{ $booking->booking_number }}</p>
        <p><strong>Customer:</strong> {{ $booking->user->name }}</p>
        <p><strong>Estimated Time of Sailing:</strong> {{ $booking->ets->format('F d, Y H:i') }}</p>
    </div>

    <p>The BL with Telex Release document must be uploaded before the vessel sails. Please ensure this document is uploaded as soon as possible.</p>

    <a href="{{ route('booking.show', $booking->id) }}" class="action-button">View Booking Details</a>

    <div class="footer">
        <p>This is an automated reminder from {{ config('app.name') }}.</p>
    </div>
</body>
</html> 