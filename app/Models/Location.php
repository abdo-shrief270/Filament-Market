<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = ['customer_id', 'title','city_id','location_link', 'address', 'is_default'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected static function booted()
    {
        static::saving(function ($location) {
            if ($location->is_default) {
                static::where('customer_id', $location->customer_id)
                    ->where('id', '!=', $location->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
