<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['customer_id','last_customer_id','courier_id','last_courier_id','order_status','discount_type','discount','number','total_price'];

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function courier():BelongsTo
    {
        return $this->belongsTo(User::class,'courier_id','id')->where('type','courier');
    }

    public function getDiscountDisplayAttribute(): string
    {
        return $this->discount_type === 'amount' ? 'EGP' : '%';
    }

    public function getDiscountColorAttribute(): string
    {
        return $this->discount_type === 'amount' ? 'info' : 'danger';
    }

    public function getPriceAttribute(): string
    {
        return $this->product?->price;
    }

    public function getOrderPriceAttribute(): string
    {
        return $this->total_price - $this->customer->city->shipping_cost;
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class, 'order_id');
    }
}
