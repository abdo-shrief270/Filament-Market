<?php

namespace App\Observers;

use App\Models\User;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if($user->type=='admin')
        {
            $user->assignRole('super-admin');
        }elseif ($user->type=='courier')
        {
            $user->assignRole('courier');
        }elseif($user->type=='manager')
        {
            $user->assignRole('manager');
        }
        if(auth()->user()) {
            Notification::make()
                ->title(__('User') . ' : ' . $user->name)
                ->icon('heroicon-o-user')
                ->body(__('User Created successfully by') . ' : ' . auth()->user()?->name)
                ->success()
                ->sendToDatabase(auth()->user());
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Notification::make()
            ->title(__('User') . ' : '.$user->name)
            ->icon('heroicon-o-user')
            ->body(__('User updated successfully by').' : '.auth()->user()->name)
            ->warning()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Notification::make()
            ->title(__('User') . ' : '.$user->name)
            ->icon('heroicon-o-user')
            ->body(__('User deleted successfully by').' : '.auth()->user()->name)
            ->danger()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Notification::make()
            ->title(__('User') . ' : '.$user->name)
            ->icon('heroicon-o-user')
            ->body(__('User restored successfully by').' :  '.auth()->user()->name)
            ->info()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Notification::make()
            ->title(__('User') . ' : '.$user->name)
            ->icon('heroicon-o-user')
            ->body(__('User forceDeleted successfully by').' : '.auth()->user()->name)
            ->danger()
            ->sendToDatabase(auth()->user());
    }
}
