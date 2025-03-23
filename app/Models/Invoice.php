<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\Payment;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
