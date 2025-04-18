<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($data['discount_type']=='amount')
        {
            $data['discount_amount'] = $data['net_price'] - $data['price'];
        }else{
            $data['discount_per'] = $data['net_price'] != 0
                ? (($data['net_price'] - $data['price']) / $data['net_price']) * 100
                : 0;
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
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $original = $record->quantity;
        $new = $data['quantity'];

        if ($new > $original) {
            $diff = $new - $original;
            $record->logs()->create([
                'type' => 'in',
                'quantity' => $diff,
                'source' => 'Stock Increased on Edit',
                'user_id' => auth()->user()->id,
            ]);
        }elseif ($new < $original) {
            $diff = $original - $new;
            $record->logs()->create([
                'type' => 'out',
                'quantity' => $diff,
                'source' => 'Stock Decreased on Edit',
                'user_id' => auth()->user()->id,
            ]);
        }
        $record->update($data);
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
