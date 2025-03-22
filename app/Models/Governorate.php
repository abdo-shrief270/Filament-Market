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
    protected $fillable =['name'];
}
