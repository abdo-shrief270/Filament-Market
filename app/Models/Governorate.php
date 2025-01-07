<?php

namespace App\Models;

use App\Observers\GovernorateObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(GovernorateObserver::class)]
class Governorate extends Model
{
    use SoftDeletes;
    protected $fillable =['name','country_id'];
    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
