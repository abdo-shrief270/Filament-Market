<?php

namespace App\Models;

use App\Observers\StoreObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(StoreObserver::class)]

class Store extends Model
{
    protected $fillable = ['name','manager_id'];


    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class,'manager_id','id')->where('type','manager');
    }
}
