<?php

namespace App\Models;

use App\Observers\ZoneObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(ZoneObserver::class)]

class Zone extends Model
{
    use SoftDeletes;

    protected $fillable =['name','itinerary_id'];

    public function itinerary():BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
}
