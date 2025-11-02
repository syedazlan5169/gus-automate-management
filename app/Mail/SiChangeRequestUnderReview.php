<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiChangeRequest;

class SiChangeRequestUnderReview extends Mailable
{
    use Queueable, SerializesModels;

    public $changeRequest;
    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct($changeRequest, $recipientType = 'customer')
    {
        $this->changeRequest = $changeRequest->load(['booking', 'shippingInstruction', 'requester']);
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'customer'
            ? 'Shipping Instruction Change Request Submitted'
            : 'New Shipping Instruction Change Request - Under Review';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->recipientType === 'customer'
            ? 'emails.si-change-request-under-review-customer'
            : 'emails.si-change-request-under-review-admin';

        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

