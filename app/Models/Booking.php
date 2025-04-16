<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShippingInstruction;
use App\Models\Cargo;
use App\Models\User;
use App\Models\Invoice;
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

    public function scopeSearch($query, $term)
    {
        if($term)
        {
            $query->where('booking_number', 'like', '%'.$term.'%')
                ->orWhere('vessel', 'like', '%'.$term.'%')
                ->orWhere('voyage', 'like', '%'.$term.'%')
                ->orWhere('pol', 'like', '%'.$term.'%')
                ->orWhere('pod', 'like', '%'.$term.'%');
        }
        return $query;
    }

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

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
