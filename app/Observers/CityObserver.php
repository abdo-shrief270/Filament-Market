<?php

namespace App\Observers;

use App\Models\City;
use Filament\Notifications\Notification;

class CityObserver
{
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        if(auth()->user()) {
            Notification::make()
                ->title(__('City') . ' : ' . $city->name)
                ->icon('heroicon-o-map-pin')
                ->body(__('City Created successfully by') . ' : ' . auth()->user()?->name)
                ->success()
                ->sendToDatabase(auth()->user());
        }
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        Notification::make()
            ->title(__('City') . ' : '.$city->name)
            ->icon('heroicon-o-map-pin')
            ->body(__('City updated successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        Notification::make()
            ->title(__('City') . ' : '.$city->name)
            ->icon('heroicon-o-map-pin')
            ->body(__('City deleted successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
