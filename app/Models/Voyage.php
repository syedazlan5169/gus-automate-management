<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Booking;

class Voyage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
