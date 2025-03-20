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

    protected $guarded = [];

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
