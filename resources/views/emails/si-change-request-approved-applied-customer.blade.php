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
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="alert">
        <strong>Changes Approved and Applied</strong> - Your request has been completed successfully
    </div>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="margin: 0;">Hello {{ $changeRequest->requester->name ?? 'Customer' }},</h2>
        <p style="margin-top: 10px;">Great news! Your Shipping Instruction change request has been approved and your changes have been successfully applied.</p>
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
                <span style="background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Approved & Applied</span>
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Applied Fields</p>
            <p style="font-weight: 500; margin-top: 0;">
                @if(!empty($changeRequest->draft_changes))
                    {{ implode(', ', array_keys($changeRequest->draft_changes)) }}
                @elseif(!empty($changeRequest->approved_fields))
                    {{ implode(', ', $changeRequest->approved_fields) }}
                @else
                    N/A
                @endif
            </p>
        </div>

        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Approved and Applied On</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->final_decision_at?->format('F d, Y \a\t H:i') ?? $changeRequest->updated_at->format('F d, Y \a\t H:i') }}</p>
        </div>

        @if($changeRequest->finalReviewer)
        <div style="margin-bottom: 12px;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Reviewed and Applied By</p>
            <p style="font-weight: 500; margin-top: 0;">{{ $changeRequest->finalReviewer->name }}</p>
        </div>
        @endif
    </div>

    <!-- Final Note -->
    @if($changeRequest->final_note)
    <div style="background-color: #ecfdf5; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 500; margin-bottom: 16px; color: #065f46;">Note from Reviewer</h3>
        <p style="white-space: pre-wrap; color: #047857;">{{ $changeRequest->final_note }}</p>
    </div>
    @endif

    <div style="background-color: #f0fdf4; padding: 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
        <p style="margin: 0; color: #065f46; font-weight: 500; margin-bottom: 8px;">What's Next?</p>
        <p style="margin: 0; color: #047857; font-size: 14px;">Your requested changes have been successfully applied to your Shipping Instruction. The updated information is now reflected in your booking.</p>
    </div>

    <div style="margin-bottom: 20px;">
        <p style="color: #6b7280; font-size: 14px;">Thank you for your patience throughout this process. If you have any questions or need further assistance, please don't hesitate to contact our support team.</p>
    </div>

    <a href="{{ route('booking.show', $changeRequest->booking->id) }}" class="btn">View Booking</a>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 14px; color: #6b7280;">If you have any questions, please contact our support team.</p>
    </div>
</body>
</html>

