<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cargo extends Model
{
    protected $fillable = [
        'container_type',
        'container_count',
        'total_weight',
        'cargo_description',
        'booking_id',
    ];

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
} 