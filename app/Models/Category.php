<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(CategoryObserver::class)]
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];
}
