<?php

namespace App\Filament\Resources\SalesManResource\Pages;

use App\Filament\Resources\SalesManResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesMen extends ListRecords
{
    protected static string $resource = SalesManResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
