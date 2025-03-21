<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cargo;
use App\Models\ShippingInstruction;

class CargoContainer extends Model
{
    protected $guarded = [];

    /**
     * Get the cargo that owns the container
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    // Add shipping instruction relationship
    public function shippingInstruction(): BelongsTo
    {
        return $this->belongsTo(ShippingInstruction::class);
    }
} 