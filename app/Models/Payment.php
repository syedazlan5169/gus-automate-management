<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_file',
        'payment_amount',
        'payment_date',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
