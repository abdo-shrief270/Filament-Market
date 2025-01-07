<?php

namespace App\Observers;

use App\Models\Country;
use Filament\Notifications\Notification;

class CountryObserver
{
    /**
     * Handle the Country "created" event.
     */
    public function created(Country $country): void
    {
        Notification::make()
            ->title(__('Country') . ' : '.$country->name)
            ->icon('heroicon-o-flag')
            ->body(__('Country Created successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Country "updated" event.
     */
    public function updated(Country $country): void
    {
        Notification::make()
            ->title(__('Country') . ' : '.$country->name)
            ->icon('heroicon-o-flag')
            ->body(__('Country updated successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Country "deleted" event.
     */
    public function deleted(Country $country): void
    {
        Notification::make()
            ->title(__('Country') . ' : '.$country->name)
            ->icon('heroicon-o-flag')
            ->body(__('Country deleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Country "restored" event.
     */
    public function restored(Country $country): void
    {
        Notification::make()
            ->title(__('Country') . ' : '.$country->name)
            ->icon('heroicon-o-flag')
            ->body(__('Country restored successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Country "force deleted" event.
     */
    public function forceDeleted(Country $country): void
    {
        Notification::make()
            ->title(__('Country') . ' : '.$country->name)
            ->icon('heroicon-o-flag')
            ->body(__('Country forceDeleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
