<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;


    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($data['discount_type']=='amount')
        {
            $data['discount_amount'] = $data['discount'];
        }else{
            $data['discount_per'] = $data['discount'];
        }
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['discount_type']=='amount')
        {
            $data['discount'] = $data['discount_amount'];
        }else{
            $data['discount'] = $data['discount_per'];
        }
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
