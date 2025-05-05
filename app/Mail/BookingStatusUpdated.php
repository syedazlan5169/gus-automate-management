<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingStatus;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $recipientType = 'customer')
    {
        $this->booking = $booking->load('user');
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking #' . $this->booking->booking_number . ' Status Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->recipientType == 'customer' 
            ? 'emails.booking-status-updated-customer' 
            : 'emails.booking-status-updated-admin';
            
        return new Content(
            view: $view,
            with: [
                'booking' => $this->booking,
                'CANCELLED' => BookingStatus::CANCELLED,
                'NEW' => BookingStatus::NEW,
                'BOOKING_CONFIRMED' => BookingStatus::BOOKING_CONFIRMED,
                'BL_VERIFICATION' => BookingStatus::BL_VERIFICATION,
                'BL_CONFIRMED' => BookingStatus::BL_CONFIRMED,
                'SAILING' => BookingStatus::SAILING,
                'ARRIVED' => BookingStatus::ARRIVED,
                'COMPLETED' => BookingStatus::COMPLETED,
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
