<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderNote extends Model
{
    protected $fillable = ['order_id','user_id','content'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

