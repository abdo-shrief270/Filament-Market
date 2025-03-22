<?php

namespace App\Observers;

use App\Models\Governorate;
use Filament\Notifications\Notification;

class GovernorateObserver
{
    public function created(Governorate $Governorate): void
    {
        if(auth()->user()) {
            Notification::make()
                ->title(__('Governorate') . ' : ' . $Governorate->name)
                ->icon('heroicon-o-map')
                ->body(__('Governorate Created successfully by') . ' : ' . auth()->user()?->name)
                ->success()
                ->sendToDatabase(auth()->user());
        }
    }

    /**
     * Handle the Governorate "updated" event.
     */
    public function updated(Governorate $Governorate): void
    {
        Notification::make()
            ->title(__('Governorate') . ' : ' . $Governorate->name)
            ->icon('heroicon-o-map')
            ->body(__('Governorate updated successfully by') . ' : ' . auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Governorate "deleted" event.
     */
    public function deleted(Governorate $Governorate): void
    {
        Notification::make()
            ->title(__('Governorate') . ' : ' . $Governorate->name)
            ->icon('heroicon-o-map')
            ->body(__('Governorate deleted successfully by') . ' : ' . auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

}
