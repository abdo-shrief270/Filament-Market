<?php

namespace App\Observers;

use App\Models\Zone;
use Filament\Notifications\Notification;

class ZoneObserver
{
    /**
     * Handle the Zone "created" event.
     */
    public function created(Zone $zone): void
    {
        Notification::make()
            ->title('Zone : '.$zone->name)
            ->body('Zone Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Zone "updated" event.
     */
    public function updated(Zone $zone): void
    {
        Notification::make()
            ->title('Zone : '.$zone->name)
            ->body('Zone updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Zone "deleted" event.
     */
    public function deleted(Zone $zone): void
    {
        Notification::make()
            ->title('Zone : '.$zone->name)
            ->body('Zone deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Zone "restored" event.
     */
    public function restored(Zone $zone): void
    {
        Notification::make()
            ->title('Zone : '.$zone->name)
            ->body('Zone restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Zone "force deleted" event.
     */
    public function forceDeleted(Zone $zone): void
    {
        Notification::make()
            ->title('Zone : '.$zone->name)
            ->body('Zone forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
