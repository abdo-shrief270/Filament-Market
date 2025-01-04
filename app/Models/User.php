<?php

namespace App\Models;

use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#[ObservedBy(UserObserver::class)]

class User extends Authenticatable implements FilamentUser
{
    use SoftDeletes,HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'id_number',
        'type',
        'itinerary_id',
        'password',
        'active'

    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->active;
    }


    public function itinerary():BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
}
