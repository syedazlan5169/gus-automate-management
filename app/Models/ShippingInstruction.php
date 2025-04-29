<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Booking;
use App\Models\Cargo;
use App\Models\CargoContainer;

class ShippingInstruction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'shipper_address' => 'array',
        'consignee_address' => 'array',
        'notify_party_address' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function containers(): HasMany
    {
        return $this->hasMany(CargoContainer::class);
    }

    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'cargo_containers')
                    ->withPivot(['container_number', 'seal_number']);
    }
}
