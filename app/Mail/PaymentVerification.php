<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;

class PaymentVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payment;
    public $invoice;
    public $payment_response;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $invoice, $payment, $payment_response)
    {
        $this->booking = $booking->load('user');
        $this->invoice = $booking->invoice;
        $this->payment = $invoice->payment;
        $this->payment_response = $payment_response;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-verification',
            with: [
                'booking' => $this->booking,
                'payment' => $this->payment,
                'invoice' => $this->invoice,
                'payment_response' => $this->payment_response,
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
