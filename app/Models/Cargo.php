<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Booking;
use App\Models\ShippingInstruction;
use App\Models\CargoContainer;
use App\Models\User;

class Cargo extends Model
{
    protected $fillable = [
        'booking_id',
        'shipping_instruction_id',
        'container_type',
        'container_count',
        'total_weight',
        'cargo_description'
    ];

    /**
     * Get the booking that owns the cargo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the shipping instruction that owns the cargo
     */
    public function shippingInstruction(): BelongsTo
    {
        return $this->belongsTo(ShippingInstruction::class);
    }

    /**
     * Get the containers for this cargo
     */
    public function containers(): HasMany
    {
        return $this->hasMany(CargoContainer::class);
    }

    /**
     * Get only the allocated containers for this cargo
     */
    public function allocatedContainers(): HasMany
    {
        return $this->hasMany(CargoContainer::class)->whereNotNull('shipping_instruction_id');
    }
}
