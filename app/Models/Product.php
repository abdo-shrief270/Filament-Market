<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(ProductObserver::class)]

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','code','buy_price','net_price','discount_type','discount','price','quantity','category_id','store_id'];

    public function store() :BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function category() :BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
