<?php

namespace App\Observers;

use App\Events\OrderUpdated;
use App\Models\Order;
use App\Models\OrderDetail;
use Filament\Notifications\Notification;

class OrderDetailObserver
{
    /**
     * Handle the OrderDetail "created" event.
     */
    public function created(OrderDetail $detail): void
    {
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body('OrderDetail Created successfully by :'.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the OrderDetail "updated" event.
     */
    public function updated(OrderDetail $detail): void
    {
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('OrderDetail updated successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the OrderDetail "deleted" event.
     */
    public function deleted(OrderDetail $detail): void
    {
        $detail->update([
            'last_order_id' => $detail->last_order
        ]);
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('OrderDetail deleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the OrderDetail "restored" event.
     */
    public function restored(OrderDetail $detail): void
    {
        $detail->update([
            'last_order_id' =>null,
            'order_id' => $detail->last_order_id
        ]);
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('OrderDetail restored successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the OrderDetail "force deleted" event.
     */
    public function forceDeleted(OrderDetail $detail): void
    {
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('OrderDetail forceDeleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
