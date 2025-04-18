<?php

namespace App\Filament\Resources\SalesManResource\Pages;

use App\Filament\Resources\SalesManResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateSalesMan extends CreateRecord
{
    protected static string $resource = SalesManResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'sales';
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
