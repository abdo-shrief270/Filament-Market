<?php

namespace App\Observers;

use App\Events\OrderUpdated;
use App\Models\Order;
use App\Models\OrderDetail;
use Filament\Notifications\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        Notification::make()
            ->title('Order : '.$order->id)
            ->body('Order Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title('Order : '.$order->id)
            ->body('Order updated successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title('Order : '.$order->id)
            ->body('Order deleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title('Order : '.$order->id)
            ->body('Order restored successfully by : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        Notification::make()
            ->title('Order : '.$order->id)
            ->body('Order forceDeleted successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
