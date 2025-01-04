<?php

namespace App\Observers;

use App\Models\Itinerary;
use Filament\Notifications\Notification;

class ItineraryObserver
{
    /**
     * Handle the Itinerary "created" event.
     */
    public function created(Itinerary $itinerary): void
    {
        Notification::make()
            ->title('Itinerary : '.$itinerary->name)
            ->body('Itinerary Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Itinerary "updated" event.
     */
    public function updated(Itinerary $itinerary): void
    {
        Notification::make()
            ->title('Itinerary : '.$itinerary->name)
            ->body('Itinerary updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Itinerary "deleted" event.
     */
    public function deleted(Itinerary $itinerary): void
    {
        Notification::make()
            ->title('Itinerary : '.$itinerary->name)
            ->body('Itinerary deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Itinerary "restored" event.
     */
    public function restored(Itinerary $itinerary): void
    {
        Notification::make()
            ->title('Itinerary : '.$itinerary->name)
            ->body('Itinerary restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Itinerary "force deleted" event.
     */
    public function forceDeleted(Itinerary $itinerary): void
    {
        Notification::make()
            ->title('Itinerary : '.$itinerary->name)
            ->body('Itinerary forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
