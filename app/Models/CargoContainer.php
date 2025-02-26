<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoContainer extends Model
{
    protected $fillable = [
        'cargo_id',
        'container_number',
        'seal_number',
    ];

    /**
     * Get the cargo that owns the container
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
} 