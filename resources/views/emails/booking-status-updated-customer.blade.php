<!DOCTYPE html>
<html>
<head>
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            font-weight: bold;
            margin: 10px 0;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .booking-details p {
            margin: 5px 0;
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


    <div class="content">
        @switch($booking->status)
            @case($CANCELLED)
                <p>Your booking is now cancelled.</p>
                @break

            @case($NEW)
                <div class="header">
                    <h2>Hello {{ $booking->user->name ?? 'Customer' }},</h2>
                </div>
                <p>Your booking has been created. Please wait for confirmation from Liner.</p>
                <div class="booking-details">
                <p><strong>Booking Number:</strong> #{{ $booking->booking_number }}</p>
                <p><strong>Customer:</strong> {{ $booking->user->name }}</p>
                <p><strong>Booking Details:</strong></p>
                <p>Place of Receipt: {{ $booking->place_of_receipt }}</p>
                <p>POL: {{ $booking->pol }}</p>
                <p>POD: {{ $booking->pod }}</p>
                <p>Place of Delivery: {{ $booking->place_of_delivery }}</p>
                <p>ETS: {{ $booking->ets }}</p>
                </div>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($BOOKING_CONFIRMED)
                <div class="header">
                    <h2>Booking Confirmed</h2>
                </div>
                <p>Booking {{ $booking->booking_number }} has been confirmed. Please upload the shipping instruction to proceed.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($BL_VERIFICATION)
                <div class="header">
                    <h2>Bill of Lading Verification</h2>
                </div>
                <p>Shipping instruction has been uploaded. Please verify and confirm the generated Bill of Lading.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($BL_CONFIRMED)
                <div class="header">
                    <h2>Bill of Lading Confirmed</h2>
                </div>
                <p>Thanks for the BL confirmation. We will prepare all the documents for your shipment. We'll inform you once the shipment is sailed.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($SAILING)
                <div class="header">
                    <h2>Sailing</h2>
                </div>
                <p>Your shipment for booking #{{ $booking->booking_number }} has sailed. Your will be notified once the shipment arrives at the destination.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($ARRIVED)
                <div class="header">
                    <h2>Arrived</h2>
                </div>
                <p>Your shipment for booking #{{ $booking->booking_number }} has arrived at the destination.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break

            @case($COMPLETED)
                <div class="header">
                    <h2>Completed</h2>
                </div>
                <p>Your shipment for booking #{{ $booking->booking_number }} has marked as completed. For any queries, please contact our support team.</p>
                <a href="{{ route('booking.show', $booking->id) }}" class="btn">View Booking</a>
                @break
        @endswitch
    </div>

    <div class="footer">
        <p>If you have any questions, contact our support team.</p>
    </div>
</body>
</html>



