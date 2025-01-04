<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($data['discount_type']=='amount')
        {
            $data['discount_amount'] = $data['net_price'] - $data['price'];
        }else{
            $data['discount_per'] = (($data['net_price'] - $data['price'])/$data['net_price'])*100;
        }
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->url(static::getResource()::getUrl())
                ->button()
                ->color('info'),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
