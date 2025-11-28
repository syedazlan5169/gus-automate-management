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
        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="alert">
        <strong>Change Request Rejected</strong> - Final Review Decision
    </div>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="margin: 0;">Hello {{ $changeRequest->requester->name ?? 'Customer' }},</h2>
        <p style="margin-top: 10px;">We regret to inform you that your Shipping Instruction change request has been rejected after final review of your submitted draft.</p>
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
                <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Rejected</span>
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Approved Fields</p>
            <p style="font-weight: 500; margin-top: 0;">
                @if(!empty($changeRequest->approved_fields))
                    {{ implode(', ', $changeRequest->approved_fields) }}
                @else
                    N/A
                @endif
            </p>
        </div>

        @if(!empty($changeRequest->draft_changes))
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Submitted Draft Fields</p>
            <p style="font-weight: 500; margin-top: 0;">
                {{ implode(', ', array_keys($changeRequest->draft_changes)) }}
            </p>
        </div>
        @endif

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Rejected On</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->final_decision_at?->format('F d, Y \a\t H:i') ?? $changeRequest->updated_at->format('F d, Y \a\t H:i') }}</p>
        </div>

        @if($changeRequest->finalReviewer)
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Reviewed By</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->finalReviewer->name }}</p>
        </div>
        @endif
    </div>

    <!-- Rejection Reason -->
    @if($changeRequest->final_note)
    <div style="background-color: #fef2f2; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px; color: #991b1b;">Rejection Reason</h3>
        <p style="white-space: pre-wrap; color: #7f1d1d;">{{ $changeRequest->final_note }}</p>
    </div>
    @endif

    <div style="margin-bottom: 20px;">
        <p style="color: #6b7280; font-size: 14px;">If you have any questions about this decision or would like to discuss alternative options, please contact our support team.</p>
    </div>

    <a href="{{ route('booking.show', $changeRequest->booking->id) }}" class="btn">View Booking</a>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 14px; color: #6b7280;">If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>

