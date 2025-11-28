<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiChangeRequest;

class SiChangeRequestApprovedApplied extends Mailable
{
    use Queueable, SerializesModels;

    public $changeRequest;

    /**
     * Create a new message instance.
     */
    public function __construct($changeRequest)
    {
        $this->changeRequest = $changeRequest->load(['booking', 'shippingInstruction', 'requester', 'finalReviewer']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Shipping Instruction Changes Approved and Applied',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.si-change-request-approved-applied-customer',
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

