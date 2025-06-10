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
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                    <h2 style="margin: 0;">Hello {{ $booking->user->name ?? 'Customer' }},</h2>
                    <p style="margin-top: 10px;">Your booking has been created. Please wait for confirmation from Liner.</p>
                </div>

                <!-- Shipping Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Shipping Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Vessel Name</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->vessel))
                                    {{ $booking->vessel }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Voyage Number</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->voyage->voyage_number))
                                    {{ $booking->voyage->voyage_number }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Tug</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->tug))
                                    {{ $booking->tug }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Delivery Terms</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->delivery_terms))
                                    {{ $booking->delivery_terms }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Route Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Route Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Place of Receipt</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->place_of_receipt }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Port of Loading</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->pol }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Port of Discharge</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->pod }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Place of Delivery</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->place_of_delivery }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Schedule</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Estimated Time of Sailing</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->ets))
                                    {{ $booking->ets->format('Y-m-d H:i') }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Estimated Time of Arrival</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->eta))
                                    {{ $booking->eta->format('Y-m-d H:i') }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('booking.show', $booking->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">View Booking</a>
                @break

            @case($BOOKING_CONFIRMED)
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                    <h2 style="margin: 0;">Hello {{ $booking->user->name ?? 'Customer' }},</h2>
                    <p style="margin-top: 10px;">Your booking has been confirmed.</p>
                </div>

                <!-- Shipping Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Shipping Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Vessel Name</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->vessel))
                                    {{ $booking->vessel }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Voyage Number</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->voyage->voyage_number))
                                    {{ $booking->voyage->voyage_number }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Tug</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->tug))
                                    {{ $booking->tug }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Delivery Terms</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->delivery_terms))
                                    {{ $booking->delivery_terms }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Route Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Route Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Place of Receipt</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->place_of_receipt }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Port of Loading</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->pol }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Port of Discharge</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->pod }}</p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Place of Delivery</p>
                            <p style="font-weight: 500; margin-top: 0;">{{ $booking->place_of_delivery }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Schedule</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Estimated Time of Sailing</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->ets))
                                    {{ $booking->ets->format('Y-m-d H:i') }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Estimated Time of Arrival</p>
                            <p style="font-weight: 500; margin-top: 0;">
                                @if (!empty($booking->eta))
                                    {{ $booking->eta->format('Y-m-d H:i') }}
                                @else
                                    <span style="font-size: 14px; font-style: italic; color: #ef4444;">Assigned by GUS</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('booking.show', $booking->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">View Booking</a>
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



