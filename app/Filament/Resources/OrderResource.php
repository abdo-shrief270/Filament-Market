<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup= 'Orders Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),
                        Forms\Components\Section::make('Order items')
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.phone')
                    ->label('Customer Phone')
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courier.name')
                    ->label('Delivery Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courier.phone')
                    ->label('Delivery Phone')
                    ->copyable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Discount Type')
                    ->formatStateUsing(fn (string $state): string => $state === 'amount' ? 'EGP' : '%')
                    ->sortable()
                    ->color(fn (string $state): string => $state === 'amount' ? 'info' : 'danger')
                    ->searchable(),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Discount')
                    ->color(fn ($record): string => $record->discount_color)
                    ->sortable()
                    ->formatStateUsing(fn ($record): string => $record->discount_display === 'EGP'
                        ? number_format($record->discount, 2) . ' EGP'
                        : number_format($record->discount, 2) . ' %')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable()
                    ->color('success')
                    ->money('egp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->label('Order Status')
                    ->formatStateUsing(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getlabel() : '-')
                    ->color(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getColor() : '-')
                    ->icon(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getIcon() : '-')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($livewire) => $livewire->activeTab === 'archived'),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn ($livewire) => $livewire->activeTab === 'archived'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('details')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('price', Product::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->searchable(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Unit Price')
                    ->formatStateUsing(fn (Forms\Get $get)=> Product::find($get('product_id'))?->price ?? 0)
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])->default([])
            ->extraItemActions([
                Forms\Components\Actions\Action::make('openProduct')
                    ->tooltip('Open product')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Forms\Components\Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: false)
                    ->hidden(fn (array $arguments, Forms\Components\Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ])
            ->required();
    }
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('number')
                ->default('OR-' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(Order::class, 'number', ignoreRecord: true),

            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->maxLength(255)
                        ->unique(),

                    Forms\Components\TextInput::make('phone')
                        ->maxLength(255)
                        ->required()
                        ->unique(),

                    Forms\Components\Select::make('city_id')
                        ->relationship('city', 'name')
                        ->required(),

                    Forms\Components\Textarea::make('address')
                        ->rows(5)
                        ->autosize()


                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Create customer')
                        ->modalSubmitActionLabel('Create customer')
                        ->modalWidth('lg');
                }),

            Forms\Components\Select::make('courier_id')
                ->relationship('courier', 'name')
                ->required(),
            Forms\Components\Select::make('discount_type')
                ->options([
                    'percentage' => '%',
                    'amount' => 'amount'
                ])->default('amount')
                ->live(),
            Forms\Components\TextInput::make('discount_amount')
                ->label('Discount Amount in EGP')
                ->numeric()
                ->disabled(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                ->hidden(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                ->required(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                ->rules('gte:0')
                ->live(),

            Forms\Components\TextInput::make('discount_per')
                ->label('Discount Percentage 0 => 100')
                ->numeric()
                ->disabled(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                ->hidden(fn (Forms\Get $get) => $get('discount_type') == 'amount')
                ->required(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
                ->rules('gte:0|lte:100')
                ->live(),

            Forms\Components\ToggleButtons::make('order_status')
                ->inline()
                ->options(OrderStatus::class)
                ->required(),

            Forms\Components\MarkdownEditor::make('notes')
                ->columnSpan('full'),
        ];
    }
}
