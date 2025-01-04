<?php

namespace App\Observers;

use App\Models\Customer;
use Filament\Notifications\Notification;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        Notification::make()
            ->title('Customer : '.$customer->name)
            ->body('Customer Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        Notification::make()
            ->title('Customer : '.$customer->name)
            ->body('Customer updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        Notification::make()
            ->title('Customer : '.$customer->name)
            ->body('Customer deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        Notification::make()
            ->title('Customer : '.$customer->name)
            ->body('Customer restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        Notification::make()
            ->title('Customer : '.$customer->name)
            ->body('Customer forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
