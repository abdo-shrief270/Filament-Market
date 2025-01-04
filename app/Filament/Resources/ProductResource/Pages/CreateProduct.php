<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
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
