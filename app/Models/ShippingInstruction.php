<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Booking;
use App\Models\Cargo;

class ShippingInstruction extends Model
{
    protected $fillable = [
        'booking_id',
        'shipper',
        'contact_shipper',
        'consignee',
        'contact_consignee',
        'customer_instructions',
        'cargo_description',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }
}
