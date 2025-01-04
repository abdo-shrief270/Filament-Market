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
        Notification::make()
            ->title('City : '.$city->name)
            ->body('City Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        Notification::make()
            ->title('City : '.$city->name)
            ->body('City updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        Notification::make()
            ->title('City : '.$city->name)
            ->body('City deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        Notification::make()
            ->title('City : '.$city->name)
            ->body('City restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        Notification::make()
            ->title('City : '.$city->name)
            ->body('City forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
