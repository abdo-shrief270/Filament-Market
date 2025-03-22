<?php

namespace App\Filament\Resources\DeliveryManResource\Pages;

use App\Filament\Resources\DeliveryManResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateDeliveryMan extends CreateRecord
{
    protected static string $resource = DeliveryManResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'courier';
        if(!isset($data['password']))
        {
            $data['password'] = Hash::make('12345678');
        }else{
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
