<?php

namespace App\Models;

use App\Observers\ItineraryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(ItineraryObserver::class)]

class Itinerary extends Model
{
    use SoftDeletes;
    protected $fillable =['name','country_id'];
    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
