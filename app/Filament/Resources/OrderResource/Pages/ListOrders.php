<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{

    use ExposesTableToWidgets;
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All')->query(fn ($query) => $query),
            'new' => Tab::make()->query(fn ($query) => $query->where('order_status', 'new')),
            'processing' => Tab::make()->query(fn ($query) => $query->where('order_status', 'processing')),
            'shipped' => Tab::make()->query(fn ($query) => $query->where('order_status', 'shipped')),
            'delivered' => Tab::make()->query(fn ($query) => $query->where('order_status', 'delivered')),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('order_status', 'cancelled')),
//            'archived' => Tab::make()->query(fn ($query) => $query->where('deleted_at','!=',null)),
        ];
    }
}
