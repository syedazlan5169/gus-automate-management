<h2>Hello {{ $booking->user->name ?? 'Customer' }},</h2>

<p>Your booking <strong>#{{ $booking->booking_number }}</strong> has been updated to:</p>

<h3>{{ \App\Models\BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown' }}</h3>

@switch($booking->status)
    @case($CANCELLED)
        <p>Your booking is now cancelled.</p>
        @break

    @case($NEW)
        <p>New booking succesfully created. Please wait for confirmation from Liner.</p>
        @break

    @case($BOOKING_CONFIRMED)
        <p>Booking has been confirmed. Please upload the shipping instruction.</p>
        @break

    @case($BL_VERIFICATION)
        <p>Shipping instruction has been uploaded. Please verify and confirm the generated Bill of Lading.</p>
        @break

    @case($BL_CONFIRMED)
        <p>Thanks for the BL confirmation. We will prepare all the documents for your shipment. We'll inform you once the shipment is sailed.</p>
        @break

    @case($SAILING)
        <p>Your shipment has sailed. Please check the status of your shipment.</p>
        @break

    @case($ARRIVED)
        <p>Your shipment has arrived. Please check the status of your shipment.</p>
        @break

    @case($COMPLETED)
        <p>Your shipment has been completed. Please check the status of your shipment.</p>
        @break
@endswitch

<p>If you have any questions, contact our support team.</p>



