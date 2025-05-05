<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Invoice;

class UploadPayment extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payment;
    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $payment, $invoice)
    {
        $this->booking = $booking->load('user');
        $this->invoice = $booking->invoice;
        $this->payment = $invoice->payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking #' . $this->booking->booking_number . ' Payment Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.upload-payment',
            with: [
                'booking' => $this->booking,
                'payment' => $this->payment,
                'invoice' => $this->invoice,
            ],
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
