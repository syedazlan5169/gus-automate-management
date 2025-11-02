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
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 15px;
        }
        .btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="margin: 0;">Hello {{ $changeRequest->requester->name ?? 'Customer' }},</h2>
        <p style="margin-top: 10px;">Your Shipping Instruction change request has been submitted and is now under review.</p>
    </div>

    <!-- Request Details -->
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Request Details</h3>
        
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Booking Number</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->booking->booking_number ?? 'N/A' }}</p>
        </div>

        @if($changeRequest->shippingInstruction)
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Shipping Instruction</p>
            <p style="font-weight: 500; margin-top: 0;">SI #{{ $changeRequest->shippingInstruction->sub_booking_number }}</p>
        </div>
        @endif

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Status</p>
            <p style="font-weight: 500; margin-top: 0;">
                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Under Review</span>
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Requested Fields</p>
            <p style="font-weight: 500; margin-top: 0;">
                @if(!empty($changeRequest->requested_fields))
                    {{ implode(', ', $changeRequest->requested_fields) }}
                @else
                    N/A
                @endif
            </p>
        </div>

        @if($changeRequest->reason)
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Reason for Change</p>
            <p style="font-weight: 500; margin-top: 0; white-space: pre-wrap;">{{ $changeRequest->reason }}</p>
        </div>
        @endif

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Submitted On</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <p style="color: #6b7280; font-size: 14px;">Our team will review your request and notify you of the decision shortly. You will receive an email once the review is complete.</p>
    </div>

    <a href="{{ route('booking.show', $changeRequest->booking->id) }}" class="btn">View Booking</a>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 14px; color: #6b7280;">If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>

