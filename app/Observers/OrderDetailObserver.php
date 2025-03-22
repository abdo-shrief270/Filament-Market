<?php

namespace App\Observers;

use App\Events\OrderUpdated;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Notifications\Notification;

class OrderDetailObserver
{
    /**
     * Handle the OrderDetail "created" event.
     */
    public function created(OrderDetail $detail): void
    {
//        $product = Product::find($detail->product_id);
//
//        if (!$product || $product->quantity < $detail->quantity) {
//            throw new \Exception("Not enough stock available.");
//        }
//
//        // Reduce stock
//        $product->decrement('quantity', $detail->quantity);


        if(auth()->user()) {
            $order = Order::find($detail->order_id);
            OrderUpdated::dispatch($order);
            Notification::make()
                ->title(__('OrderDetail') . ' : ' . $detail->id)
                ->icon('heroicon-o-shopping-bag')
                ->body('OrderDetail Created successfully by :' . auth()->user()->name)
                ->success()
                ->sendToDatabase(auth()->user());
        }
    }

    /**
     * Handle the OrderDetail "updated" event.
     */
    public function updated(OrderDetail $detail): void
    {
//        $product = Product::find($detail->product_id);
//        $originalQuantity = $detail->getOriginal('quantity'); // Previous quantity
//        $newQuantity = $detail->quantity; // New quantity
//
//        if (!$product) {
//            throw new \Exception("Product not found.");
//        }
//
//        $stockAdjustment = $newQuantity - $originalQuantity; // Difference
//
//        if ($stockAdjustment > 0 && $product->quantity < $stockAdjustment) {
//            throw new \Exception("Not enough stock available.");
//        }
//
//        // Adjust stock based on the update
//        $product->decrement('quantity', $stockAdjustment);

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
//        $product = Product::find($detail->product_id);
//        if ($product) {
//            $product->increment('quantity', $detail->quantity);
//        }
        $order = Order::find($detail->order_id);
        OrderUpdated::dispatch($order);
        Notification::make()
            ->title(__('OrderDetail') . ' : '.$detail->id)
            ->icon('heroicon-o-shopping-bag')
            ->body(__('OrderDetail deleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

}
