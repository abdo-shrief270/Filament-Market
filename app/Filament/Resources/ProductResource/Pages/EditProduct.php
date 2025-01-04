<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
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
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['discount_type']=='amount')
        {
            $data['discount'] = $data['discount_amount'];
            $data['price'] = $data['net_price'] - $data['discount_amount'];
        }else{
            $data['discount'] = $data['discount_per'];
            $data['price'] = $data['net_price'] - ($data['net_price'] * ($data['discount_per'] / 100));
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
