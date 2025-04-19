<?php

namespace App\Observers;

use App\Events\OrderUpdated;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Notifications\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $order->customer?->increment('buy_count');
        if(auth()->user()) {
            Notification::make()
                ->title(__('Order') . ' : ' . $order->id)
                ->body(__('Order Created successfully by : ') . auth()->user()->name)
                ->success()
                ->sendToDatabase(auth()->user());
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        OrderUpdated::dispatch($order);

        $originalStatus = $order->getOriginal('order_status');
        $newStatus = $order->order_status;

        if ($order->isDirty('order_status')) {
            // From non-shipped → shipped/delivered (decrease stock)
            if ($originalStatus !== 'shipped' && $originalStatus !== 'delivered' &&
                ($newStatus === 'shipped' || $newStatus === 'delivered')) {
                foreach ($order->details as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->decreaseStock($detail->quantity, 'Order shipped/delivered', auth()->id());
                    }
                }
            }

            // From shipped/delivered → cancelled/returned (increase stock)
            if (($originalStatus === 'shipped' || $originalStatus === 'delivered') &&
                ($newStatus !== 'shipped' && $newStatus !== 'delivered')) {
                foreach ($order->details as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->increaseStock($detail->quantity, 'Order cancelled/returned', auth()->id());
                    }
                }
            }
        }

        Notification::make()
            ->title(__('Order') . ' : ' . $order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order updated successfully by') . ' : ' . auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        foreach ($order->details as $detail) {
            $product = $detail->product;
            if ($product) {
                $product->increaseStock($detail->quantity, 'cancelled order', auth()->id());
            }
        }
        $order->customer?->decrement('buy_count');
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('Order') . ' : '.$order->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('Order deleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
