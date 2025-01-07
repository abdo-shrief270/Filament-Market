<?php

namespace App\Models;

use App\Observers\CityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(CityObserver::class)]

class City extends Model
{
    use SoftDeletes;
    protected $fillable =['name','governorate_id','shipping_cost'];
    public function governorate():BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
