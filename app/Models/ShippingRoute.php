<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRoute extends Model
{
    protected $fillable = [
        'route_name',
        'origin',
        'destination',
        'place_of_receipt',
        'pol',
        'pod',
        'place_of_delivery',
    ];

    public function scopeSearch($query, $term)
    {
        if($term)
        {
            $query->where('origin', 'like', '%'.$term.'%')
                ->orWhere('destination', 'like', '%'.$term.'%')
                ->orWhere('place_of_receipt', 'like', '%'.$term.'%')
                ->orWhere('place_of_delivery', 'like', '%'.$term.'%')
                ->orWhere('pol', 'like', '%'.$term.'%')
                ->orWhere('pod', 'like', '%'.$term.'%');
        }
        return $query;
    }
}
