<?php
namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ]),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('source')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Performed By')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->sortable()
                    ->dateTime('M j, Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([
                Tables\Actions\Action::make('adjust_stock')
                    ->icon('heroicon-o-cog')
                    ->label('Adjust Stock')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options(['in' => 'In', 'out' => 'Out'])
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\TextInput::make('source')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Get the product associated with the logs relation
                        $product = $this->getOwnerRecord();

                        if ($data['type'] === 'in') {
                            $product->increaseStock($data['quantity'], $data['source'], auth()->id());
                        } else {
                            $product->decreaseStock($data['quantity'], $data['source'], auth()->id());
                        }
                    })
                    ->modalHeading('Adjust Stock'),
            ]);
    }
}
