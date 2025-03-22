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
    protected $fillable = ['name','code','buy_price','net_price','discount_type','discount','price','quantity','store_id'];

    public function store() :BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    public function increaseStock(int $quantity, string $source, ?int $userId = null)
    {
        $this->increment('quantity', $quantity);
        $this->logs()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'source' => $source,
            'user_id' => $userId,
        ]);
    }

    public function decreaseStock(int $quantity, string $source, ?int $userId = null)
    {
        $this->decrement('quantity', $quantity);
        $this->logs()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'source' => $source,
            'user_id' => $userId,
        ]);
    }
}
