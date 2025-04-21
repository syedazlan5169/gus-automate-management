<h2>Booking Status Updated</h2>

<p>Booking <strong>#{{ $booking->booking_number }}</strong> (Customer: {{ $booking->user->name }}) has changed status to:</p>

<h3>{{ \App\Models\BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown' }}</h3>

@switch($booking->status)
    @case($CANCELLED)
        <p>Booking has been cancelled.</p>
        @break

    @case($NEW)
        <p>New booking has been created. Please review and confirm the booking.</p>
        @break

    @case($BOOKING_CONFIRMED)
        <p>Booking has been confirmed. Waiting for customer to upload shipping instruction.</p>
        @break

    @case($BL_VERIFICATION)
        <p>Shipping instruction has been uploaded. Please verify and confirm the generated Bill of Lading.</p>
        @break

    @case($BL_CONFIRMED)
        <p>Bill of Lading has been confirmed. Please prepare all the documents for the shipment.</p>
        @break

    @case($SAILING)
        <p>Shipment has sailed. Please prepare for customs clearance.</p>
        @break

    @case($ARRIVED)
        <p>Shipment has arrived. Please prepare for customs clearance.</p>
        @break

    @case($COMPLETED)
        <p>Shipment has been completed. Please prepare for customs clearance.</p>
        @break
@endswitch


