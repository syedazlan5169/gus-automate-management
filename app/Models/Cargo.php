<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Booking;
use App\Models\ShippingInstruction;
use App\Models\CargoContainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cargo extends Model
{
    protected $guarded = [];

    /**
     * Get the booking that owns the cargo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
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

    public function shippingInstructions(): BelongsToMany
    {
        return $this->belongsToMany(ShippingInstruction::class, 'cargo_containers')
                    ->withPivot(['container_number', 'seal_number']);
    }
}
