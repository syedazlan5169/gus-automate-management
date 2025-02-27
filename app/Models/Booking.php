<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShippingInstruction;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

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

    protected $casts = [
        'booking_date' => 'date',
        'ets' => 'datetime',
        'eta' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingInstructions()
    {
        return $this->hasMany(ShippingInstruction::class);
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }
}
