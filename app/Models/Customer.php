<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(CustomerObserver::class)]

class Customer extends Model
{
    protected $fillable =['name','phone','whatsapp','email','buy_count'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function defaultLocation()
    {
        return $this->hasOne(Location::class)->where('is_default', true);
    }
}
