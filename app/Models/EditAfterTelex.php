<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditAfterTelex extends Model
{
    protected $guarded = [];
    protected $table = 'edit_after_telex';

    protected $casts = [
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
        'changes' => 'array',
        'finalized_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
