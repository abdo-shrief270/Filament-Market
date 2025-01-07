<?php

namespace App\Observers;

use App\Models\Category;
use Filament\Notifications\Notification;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        Notification::make()
            ->title(__('Category') . ' : '.$category->name)
            ->icon('heroicon-o-tag')
            ->body(__('Category Created successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        Notification::make()
            ->title(__('Category') . ' : '.$category->name)
            ->icon('heroicon-o-tag')
            ->body(__('Category updated successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        Notification::make()
            ->title(__('Category') . ' : '.$category->name)
            ->icon('heroicon-o-tag')
            ->body(__('Category deleted successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        Notification::make()
            ->title(__('Category') . ' : '.$category->name)
            ->icon('heroicon-o-tag')
            ->body(__('Category restored successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        Notification::make()
            ->title(__('Category') . ' : '.$category->name)
            ->icon('heroicon-o-tag')
            ->body(__('Category forceDeleted successfully by').' : '.auth()->user()?->name)
            ->success()
            ->sendToDatabase(auth()->user());
    }
}
