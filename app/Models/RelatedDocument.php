<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedDocument extends Model
{
    protected $fillable = [
        'booking_id',
        'document_name',
        'document_file',
        'document_number',
        'invoice_amount',
    ];

    protected $casts = [
        'invoice_amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
