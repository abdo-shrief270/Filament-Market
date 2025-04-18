<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(auth()->user()->hasRole('manager')){
            $data['store_id']=Store::where('manager_id',auth()->user()->id)->first()->id;
        }
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

    protected function afterCreate(): void
    {
        $product = $this->record;
        $product->logs()->create([
            'type' => 'in',
            'quantity' => $product->quantity,
            'source' => 'Product Creation',
            'user_id' => auth()->user()->id,
        ]);
    }
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
