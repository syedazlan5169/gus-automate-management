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
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="alert">
        <strong>Draft Submitted</strong> - Final Review Required
    </div>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="margin: 0;">New Draft Submission for Final Review</h2>
        <p style="margin-top: 10px;">A customer has submitted draft changes that require your final review.</p>
    </div>

    <!-- Request Details -->
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px;">Request Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
            <div>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Request ID</p>
                <p style="font-weight: 500; margin-top: 0;">#{{ $changeRequest->id }}</p>
            </div>
            <div>
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Status</p>
                <p style="font-weight: 500; margin-top: 0;">
                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Pending Final Review</span>
                </p>
            </div>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Customer</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->requester->name ?? 'N/A' }} ({{ $changeRequest->requester->email ?? 'N/A' }})</p>
        </div>

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
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Approved Fields</p>
            <p style="font-weight: 500; margin-top: 0;">
                @if(!empty($changeRequest->approved_fields))
                    {{ implode(', ', $changeRequest->approved_fields) }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Submitted Fields in Draft</p>
            <p style="font-weight: 500; margin-top: 0;">
                @if(!empty($changeRequest->draft_changes))
                    {{ implode(', ', array_keys($changeRequest->draft_changes)) }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Submitted On</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->submitted_at?->format('F d, Y \a\t H:i') ?? $changeRequest->updated_at->format('F d, Y \a\t H:i') }}</p>
        </div>

        @if($changeRequest->approver)
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Initially Approved By</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->approver->name }}</p>
        </div>
        @endif
    </div>

    <div style="background-color: #eff6ff; padding: 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
        <p style="margin: 0; color: #1e40af; font-weight: 500; margin-bottom: 8px;">Action Required:</p>
        <p style="margin: 0; color: #1e3a8a; font-size: 14px;">Please review the submitted draft changes and make a final decision to either approve and apply the changes, or reject them.</p>
    </div>

    <a href="{{ route('booking.show', $changeRequest->booking->id) }}" class="btn">Review Draft Submission</a>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 14px; color: #6b7280;">This is an automated notification. Please review the draft submission in the admin panel.</p>
    </div>
</body>
</html>

