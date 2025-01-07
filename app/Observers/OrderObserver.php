<?php

namespace App\Observers;

use App\Events\OrderUpdated;
use App\Filament\Resources\OrderResource;
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
            ->title(__('Order').' : '.$order->id)
            ->body(__('Order Created successfully by : ').auth()->user()->name)
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
            ->title(__('Order') . ' : '.$order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order updated successfully by').' : '.auth()->user()->name)
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
            ->title(__('Order') . ' : '.$order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order deleted successfully by').' : '.auth()->user()->name)
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
            ->title(__('Order') . ' : '.$order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order restored successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        Notification::make()
            ->title(__('Order') . ' : '.$order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order forceDeleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
