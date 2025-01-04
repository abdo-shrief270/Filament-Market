<?php

namespace App\Observers;

use App\Models\Store;
use Filament\Notifications\Notification;

class StoreObserver
{
    /**
     * Handle the Store "created" event.
     */
    public function created(Store $store): void
    {
        Notification::make()
            ->title('Store : '.$store->name)
            ->body('Store Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Store "updated" event.
     */
    public function updated(Store $store): void
    {
        Notification::make()
            ->title('Store : '.$store->name)
            ->body('Store updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store): void
    {
        Notification::make()
            ->title('Store : '.$store->name)
            ->body('Store deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Store "restored" event.
     */
    public function restored(Store $store): void
    {
        Notification::make()
            ->title('Store : '.$store->name)
            ->body('Store restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Store "force deleted" event.
     */
    public function forceDeleted(Store $store): void
    {
        Notification::make()
            ->title('Store : '.$store->name)
            ->body('Store forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
