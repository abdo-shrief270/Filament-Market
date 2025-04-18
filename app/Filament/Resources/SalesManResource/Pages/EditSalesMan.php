<?php

namespace App\Filament\Resources\SalesManResource\Pages;

use App\Filament\Resources\SalesManResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditSalesMan extends EditRecord
{
    protected static string $resource = SalesManResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['type'] = 'sales';
        $data['password'] = null;
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['type'] = 'sales';
        if($data['password']==null){
            unset($data['password']);
        }
        if(isset($data['password']))
        {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
