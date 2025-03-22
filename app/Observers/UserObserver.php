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
            $user->assignRole('admin');
        }elseif ($user->type=='courier')
        {
            $user->assignRole('courier');
        }elseif($user->type=='manager')
        {
            $user->assignRole('manager');
        }elseif($user->type=='sales')
        {
            $user->assignRole('sales');
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
}
