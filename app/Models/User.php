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
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser
{
    use HasFactory,Notifiable,HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'governorate_id',
        'active'

    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->active;
    }

    public function governorate():BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
