<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShippingInstruction;
use App\Models\Cargo;
use App\Models\User;
use App\Models\SiChangeRequest;
use App\Models\Invoice;
use App\Models\Voyage;
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
            $query->where(function($q) use ($term) {
                $q->where('booking_number', 'like', '%'.$term.'%')
                    ->orWhere('vessel', 'like', '%'.$term.'%')
                    ->orWhere('pol', 'like', '%'.$term.'%')
                    ->orWhere('pod', 'like', '%'.$term.'%')
                    ->orWhereHas('voyage', function($voyageQuery) use ($term) {
                        $voyageQuery->where('voyage_number', 'like', '%'.$term.'%');
                    });
            });
        }
        return $query;
    }

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->latestOfMany();
    }

    public function relatedDocuments()
    {
        return $this->hasMany(RelatedDocument::class);
    }

    public function editAfterTelex()
    {
        return $this->hasMany(EditAfterTelex::class);
    }

    public function siChangeRequests(): HasMany
    {
        return $this->hasMany(SiChangeRequest::class);
    }
   
}
