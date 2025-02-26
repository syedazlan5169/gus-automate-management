<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_date',
        'booking_number',
        'quotation_number',
        'service',
        'liner_address',
        'shipper',
        'contact_shipper',
        'consignee',
        'contact_consignee',
        'vessel',
        'place_of_receipt',
        'pol',
        'pod',
        'voyage',
        'place_of_delivery',
        'ets',
        'eta',
        'booking_item',
        'status',
        'total_weight',
        'total_volume',
        'container_type',
        'container_count',
        'remarks',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
