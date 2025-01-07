<?php

namespace App\Observers;

use App\Models\Product;
use Filament\Notifications\Notification;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        Notification::make()
            ->title(__('Product') . ' : '.$product->name)
            ->icon('heroicon-o-square-3-stack-3d')
            ->body(__('Product Created successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        Notification::make()
            ->title(__('Product') . ' : '.$product->name)
            ->icon('heroicon-o-square-3-stack-3d')
            ->body(__('Product updated successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Notification::make()
            ->title(__('Product') . ' : '.$product->name)
            ->icon('heroicon-o-square-3-stack-3d')
            ->body(__('Product deleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        Notification::make()
            ->title(__('Product') . ' : '.$product->name)
            ->icon('heroicon-o-square-3-stack-3d')
            ->body(__('Product restored successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        Notification::make()
            ->title(__('Product') . ' : '.$product->name)
            ->icon('heroicon-o-square-3-stack-3d')
            ->body(__('Product forceDeleted successfully by').' : '.auth()->user()->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
