<?php

namespace App\Models;

use App\Observers\OrderDetailObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(OrderDetailObserver::class)]
class OrderDetail extends Model
{
    use SoftDeletes;
    protected $fillable =['order_id','last_order_id','product_id','quantity'];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
