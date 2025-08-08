<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditAfterTelex extends Model
{
    protected $guarded = [];
    protected $table = 'edit_after_telex';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
