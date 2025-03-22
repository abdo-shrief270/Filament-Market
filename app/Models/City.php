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
    protected $fillable =['name','governorate_id','shipping_cost','default_dMan_id'];
    public function governorate():BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
    public function delivery_man():BelongsTo
    {
        return $this->belongsTo(User::class)->where('type','courier');
    }
}
