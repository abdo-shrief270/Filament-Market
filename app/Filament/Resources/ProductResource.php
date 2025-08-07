<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Store;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup= 'Products Management';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('store_id')
                    ->relationship('store', 'name')
                    ->hidden(auth()->user()->hasRole('manager'))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required(),
                Forms\Components\TextInput::make('buy_price')
                    ->numeric()
                    ->gte(0)
                    ->required(),
                Forms\Components\TextInput::make('net_price')
                    ->numeric()
                    ->gte('buy_price')
                    ->required(),
                Forms\Components\Select::make('discount_type')
                    ->options([
                        'percentage' => '%',
                        'amount' => 'amount'
                    ])
                    ->live(),
                Forms\Components\TextInput::make('discount_amount')
                    ->numeric()
                    ->disabled(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                    ->hidden(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                    ->required(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                    ->rules('gte:0')
                    ->lte('net_price')
                    ->live(),

                Forms\Components\TextInput::make('discount_per')
                    ->numeric()
                    ->disabled(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                    ->hidden(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                    ->required(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                    ->rules('gte:0|lte:100')
                    ->live(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->gte(0)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('logs_count')
                    ->counts('logs')
                    ->label('Total Movements')
                    ->sortable(),

                Tables\Columns\TextColumn::make('in_logs_count')
                    ->label('Stock In')
                    ->getStateUsing(fn($record) => $record->logs()->where('type', 'in')->sum('quantity'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('out_logs_count')
                    ->label('Stock Out')
                    ->getStateUsing(fn($record) => $record->logs()->where('type', 'out')->sum('quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->sortable()
                    ->hidden(auth()->user()->hasRole('manager'))
                    ->searchable(),
            ])
            ->defaultSort('updated_at','desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('adjust_stock')
                    ->icon('heroicon-o-cog')
                    ->label('Adjust Stock')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options(['in' => 'In', 'out' => 'Out'])
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->rule('gte:1')
                            ->required(),
                        Forms\Components\TextInput::make('source')
                            ->required(),
                    ])
                    ->action(function (array $data, Product $record) {
                        if ($data['type'] === 'in') {
                            $record->increaseStock($data['quantity'], $data['source'], auth()->id());
                        } else {
                            $record->decreaseStock($data['quantity'], $data['source'], auth()->id());
                        }
                    })
                    ->modalHeading('Adjust Stock'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        if (Auth::check() && !Auth::user()->hasRole('manager')) {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('store_id',Store::where('manager_id',auth()->user()->id)->first()->id);
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getNavigationBadge(): ?string
    {
        if (Auth::check() && !Auth::user()->hasRole('manager')) {
            return static::getModel()::count();
        }
        return static::getModel()::where('store_id',Store::where('manager_id',auth()->user()->id)->first()->id)->count();

    }

    public static function getRelations(): array
    {
        return [
            ProductResource\RelationManagers\ProductLogsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
