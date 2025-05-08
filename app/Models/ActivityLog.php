<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\User;
class ActivityLog extends Model
{
    protected $guarded = [];

    public static function logBookingCreated($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Booking Created',
            'description' => $user->name . ' created a new booking ' . $booking->booking_number,
        ]);
    }

    public static function logBookingCancelled($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Booking Cancelled',
            'description' => $user->name . ' has cancelled booking ' . $booking->booking_number,
        ]);
    }

    public static function logBookingDeleted($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Booking Deleted',
            'description' => $user->name . ' has deleted booking ' . $booking->booking_number,
        ]);
    }

    public static function logBookingEdited($user, $booking)
    {
        $changedFields = collect($booking->getChanges())
            ->except(['updated_at']) // skip timestamp
            ->keys()
            ->implode(', ');

        $description = $user->name . ' edited booking ' . $booking->booking_number;

        if ($changedFields) {
            $description .= ' — Fields changed: ' . $changedFields;
        }

        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Booking Edited',
            'description' => $description,
        ]);
    }

    public static function logBookingConfirmed($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Booking Confirmed',
            'description' => $user->name . ' has confirmed booking ' . $booking->booking_number,
        ]);
    }

    public static function logShippingInstructionCreated($user, $shippingInstruction)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $shippingInstruction->booking_id,
            'action' => 'SI Created',
            'description' => $user->name . ' has created a new shipping instruction (' . $shippingInstruction->sub_booking_number . ') for booking ' . $shippingInstruction->booking->booking_number,
        ]);
    }

    public static function logShippingInstructionEdited($user, $shippingInstruction)
    {
        $changedFields = collect($shippingInstruction->getChanges())
            ->except(['updated_at']) // skip timestamp
            ->keys()
            ->implode(', ');

        $description = $user->name . ' edited shipping instruction (' . $shippingInstruction->sub_booking_number . ') for booking ' . $shippingInstruction->booking->booking_number;

        if ($changedFields) {
            $description .= ' — Fields changed: ' . $changedFields;
        }
        
        self::create([
            'user_id' => $user->id,
            'booking_id' => $shippingInstruction->booking_id,
            'action' => 'SI Edited',
            'description' => $description,
        ]);
    }

    public static function logShippingInstructionDeleted($user, $shippingInstruction)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $shippingInstruction->booking_id,
            'action' => 'SI Deleted',
            'description' => $user->name . ' has deleted shipping instruction (' . $shippingInstruction->sub_booking_number . ') for booking ' . $shippingInstruction->booking->booking_number,
        ]);
    }
    
    public static function logShippingInstructionSubmitted($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'SI Submitted',
            'description' => $user->name . ' has submitted shipping instruction for booking ' . $booking->booking_number,
        ]);
    }

    public static function logBLConfirmed($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'BL Confirmed',
            'description' => $user->name . ' has confirmed BL for booking ' . $booking->booking_number,
        ]);
    }

    public static function logInvoiceUploaded($user, $booking, $invoice)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Invoice Uploaded',
            'description' => $user->name . ' has uploaded ' . $invoice->invoice_name . ' invoice for booking ' . $booking->booking_number,
        ]);
    }

    public static function logInvoiceDeleted($user, $booking, $invoice)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Invoice Deleted',
            'description' => $user->name . ' has deleted ' . $invoice->invoice_name . ' invoice for booking ' . $booking->booking_number,
        ]);
    }

    public static function logDocumentUploaded($user, $booking, $document_name)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Document Uploaded',
            'description' => $user->name . ' has uploaded ' . $document_name . ' document for booking ' . $booking->booking_number,
        ]);
    }

    public static function logDocumentDeleted($user, $booking, $document)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Document Deleted',
            'description' => $user->name . ' has deleted ' . $document->document_name . ' document for booking ' . $booking->booking_number,
        ]);
    }

    public static function logSailing($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Sailing',
            'description' => $user->name . ' has changed booking ' . $booking->booking_number . ' status to Sailing',
        ]);
    }

    public static function logArrived($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Arrived',
            'description' => $user->name . ' has changed booking ' . $booking->booking_number . ' status to Arrived',
        ]);
    }

    public static function logCompleted($user, $booking)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Completed',
            'description' => $user->name . ' has changed booking ' . $booking->booking_number . ' status to Completed',
        ]);
    }

    public static function logPaymentUploaded($user, $booking, $payment)
    {
        self::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'action' => 'Payment Uploaded',
            'description' => $user->name . ' has uploaded ' . $booking->invoice->invoice_name . ' payment slip for booking ' . $booking->booking_number,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    } 
}
