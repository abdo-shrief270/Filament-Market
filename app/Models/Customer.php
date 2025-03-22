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
    protected $fillable =['name','phone','email','buy_count','city_id','address'];

    public function city():BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
