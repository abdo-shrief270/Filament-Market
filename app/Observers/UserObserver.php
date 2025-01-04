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
        Notification::make()
            ->title('User : '.$user->name)
            ->body('User Created successfully by :'.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Notification::make()
            ->title('User : '.$user->name)
            ->body('User updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Notification::make()
            ->title('User : '.$user->name)
            ->body('User deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Notification::make()
            ->title('User : '.$user->name)
            ->body('User restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Notification::make()
            ->title('User : '.$user->name)
            ->body('User forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
