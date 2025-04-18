<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\NotesRelationManager;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\City;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderNote;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                            ->disabled(auth()->user()->hasRole('courier'))
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
    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->striped()
            ->recordUrl(null)
//            ->recordUrl(
//                fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record]),
//            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                        Tables\Columns\Layout\Panel::make([
                            Tables\Columns\TextColumn::make('customer.name')
                                ->label('Customer Name')
                                ->sortable()
                                ->alignLeft()
                                ->alignJustify()
                                ->searchable(),
                            Tables\Columns\TextColumn::make('customer_phone')
                                ->label('Phone')
                                ->icon('heroicon-o-phone-arrow-up-right')
                                ->iconColor('primary')
                                ->state(fn (Order $record) => $record->customer->phone)
                                ->formatStateUsing(fn (Order $record) => $record->customer->phone ?'Call': 'No Phone')
                                ->url(fn (Order $record) => $record->customer->phone ? 'tel:' . $record->customer->phone : null)
                                ->openUrlInNewTab()
                                ->weight('bold'),

                            Tables\Columns\TextColumn::make('customer_whatsapp')
                                ->label('WhatsApp')
                                ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                                ->iconColor('success')
                                ->state(fn (Order $record) => $record->customer->phone)
                                ->formatStateUsing(fn (Order $record) => $record->customer->phone ?'Chat': 'No WhatsApp')
                                ->url(fn (Order $record) => $record->customer->phone ? 'https://wa.me/+2' . $record->customer->phone : null)
                                ->openUrlInNewTab()
                                ->weight('bold')
                                ->tooltip('Click to chat on WhatsApp'),
                        ]),

                        Tables\Columns\Layout\Panel::make([
                            Tables\Columns\TextColumn::make('courier.name')
                                ->label('Delivery Name')
                                ->sortable()
                                ->alignLeft()
                                ->searchable(),
                            Tables\Columns\TextColumn::make('courier_phone')
                                ->label('Phone')
                                ->icon('heroicon-o-phone-arrow-up-right')
                                ->iconColor('primary')
                                ->state(fn (Order $record) => $record->courier->phone)
                                ->formatStateUsing(fn (Order $record) => $record->courier->phone ?'Call': 'No Phone')
                                ->url(fn (Order $record) => $record->courier->phone ? 'tel:' . $record->courier->phone : null)
                                ->openUrlInNewTab()
                                ->weight('bold'),

                            Tables\Columns\TextColumn::make('courier_whatsapp')
                                ->label('WhatsApp')
                                ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                                ->iconColor('success')
                                ->state(fn (Order $record) => $record->courier->phone)
                                ->formatStateUsing(fn (Order $record) => $record->courier->phone ?'Chat': 'No WhatsApp')
                                ->url(fn (Order $record) => $record->courier->phone ? 'https://wa.me/+2' . $record->courier->phone : null)
                                ->openUrlInNewTab()
                                ->weight('bold')
                                ->tooltip('Click to chat on WhatsApp'),
                        ])->hidden(auth()->user()->hasRole('courier'))
                ]),

                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('order_price')
                            ->label('Order Price')
                            ->icon('heroicon-o-banknotes')
                            ->iconColor('info')
                            ->color('info')
                            ->money('egp')
                            ->sortable()
                            ->alignLeft()
                            ->searchable()
                            ->hidden(auth()->user()->hasRole('courier')),

                        Tables\Columns\TextColumn::make('location.city.shipping_cost')
                            ->label('Shipping Price')
                            ->icon('heroicon-o-truck')
                            ->iconColor('danger')
                            ->color('danger')
                            ->money('egp')
                            ->sortable()
                            ->alignLeft()
                            ->searchable()
                            ->hidden(auth()->user()->hasRole('courier')),
                        Tables\Columns\TextColumn::make('total_price')
                            ->label('Total Price')
                            ->icon('heroicon-o-calculator')
                            ->iconColor('success')
                            ->money('egp')
                            ->color('success')
                            ->sortable()
                            ->alignLeft()
                            ->searchable(),
                    ])->from('sm'),
                ]),

                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('location_link')
                            ->label('Location')
                            ->icon('heroicon-o-map-pin')
                            ->iconColor('info')
                            ->tooltip('Click to view location')
                            ->state(fn (Order $record) => $record->location->location_link)
                            ->formatStateUsing(fn ($state) => $state ? 'Location' : 'No Link')
                            ->url(fn ($state) => filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                            ->openUrlInNewTab()
                            ->alignLeft()
                            ->searchable(),

                        Tables\Columns\TextColumn::make('order_status')
                            ->badge()
                            ->label('Status')
                            ->icon(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getIcon() : '-')
                            ->color(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getColor() : '-')
                            ->formatStateUsing(fn ($state) => $state ? \App\Enums\OrderStatus::from($state)->getLabel() : '-')
                            ->sortable()
                            ->alignLeft(),

                        Tables\Columns\TextColumn::make('created_at')
                            ->badge()
                            ->color('danger')
                            ->label('Created')
                            ->since()
                            ->sortable()
                            ->alignLeft(),
                    ])->from('sm'),
                ]),

            ])->contentGrid([
                'md' => 1,
                'xl' => 3,
            ])
            ->actions([
                Tables\Actions\Action::make('Delivered')
                    ->label('Mark as Delivered')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->hidden(fn (Order $order) => !(auth()->user()->hasRole('courier') && $order->order_status!="delivered"))
                    ->action(function (Order $record) {
                        $record->update(['order_status' => 'delivered']);
                    }),

                Tables\Actions\Action::make('Return')
                    ->label('Return')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning') // Green color
                    ->hidden(fn (Order $order) => !(auth()->user()->hasRole('courier') && $order->order_status=="delivered"))
                    ->action(function (Order $record) {
                        $record->update(['order_status' => 'processing']);
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function(Order $record){
                    foreach($record->details as $detail){
                        $product = Product::find($detail->product_id);
                        if ($product) {
                            $product->increment('quantity', $detail->quantity);
                        }
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('change_courier')
                        ->label('Change Delivery Man')
                        ->icon('heroicon-o-pencil')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('courier_id')
                                ->label('New Courier')
                                ->options(\App\Models\User::where('type','courier')->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update(['courier_id' => $data['courier_id']]);
                            }

                            Notification::make()
                                ->title('The new courier is added')
                                ->success()
                                ->send();
                        }),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getWidgets(): array
    {
        if (Auth::check() && !Auth::user()->hasRole('courier')) {
            return [
                OrderStats::class,
            ];
        }
        return [];
    }
    public static function getEloquentQuery(): Builder
    {
        if (Auth::check() && !Auth::user()->hasRole('courier')) {
            return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
        }
        return parent::getEloquentQuery()->where('courier_id',auth()->user()->id);
    }
    public static function getNavigationBadge(): ?string
    {
        if (Auth::check() && !Auth::user()->hasRole('courier')) {
            return static::getModel()::count();
        }
        return static::getModel()::where('courier_id',auth()->user()->id)->count();

    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getItemsRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('details')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(
                        Product::query()
                            ->with('store') // Ensure the store relationship is loaded
                            ->get()
                            ->mapWithKeys(fn ($product) => [
                                $product->id => "{$product->name} ({$product->store?->name})"
                            ])
                    )
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
                    ->maxValue(function (Forms\Get $get, $state) {
                        $product = Product::find($get('product_id'));
                        $existingOrder = $get('id') ? OrderDetail::find($get('id')) : null; // Check if editing

                        $previousQuantity = $existingOrder?->quantity ?? 0; // Get previous quantity in the order
                        return ($product?->quantity ?? 0) + $previousQuantity; // Allow reallocation in edit mode
                    })// Ensures user doesn't exceed available stock
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Unit Price')
                    ->formatStateUsing(fn (Forms\Get $get)=> Product::find($get('product_id'))?->price ?? 0)
                    ->disabled()
                    ->hidden(fn () => auth()->user()->hasRole('courier')) // Show only for couriers
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
                    ->hidden(fn (array $arguments, Forms\Components\Repeater $component): bool => auth()->user()->hasRole('courier') || blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 10,
            ]);
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
                ->columnSpan(
                    [
                        'default' =>3,
                        'sm' => 2,
                    ]
                )
                ->unique(Order::class, 'number', ignoreRecord: true),

            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->disabled(auth()->user()->hasRole('courier'))
                ->required()
                ->live()
                ->reactive()
                ->columnSpan(
                    [
                        'default' =>3,
                        'sm' => 2,
                    ]
                )
//                ->afterStateUpdated(function ($state, callable $set){
//                    $defaultLocationId = \App\Models\Location::where('customer_id',$state)?->where('is_default',true)?->first()?->id;
////                    dd($defaultLocationId);
//                    if($defaultLocationId){
//                        $set('location_id', $defaultLocationId);
//                        $set('courier_id', Location::find($state)?->city?->delivery_man_id);
//                    }
//                })
                ->afterStateUpdated(function ($state, callable $set,callable $get) {

                    if (!$state) return;

                    // Get default location
                    $defaultLocation = \App\Models\Location::where('customer_id', $state)
                        ->where('is_default', true)
                        ->first()
                        ?? \App\Models\Location::where('customer_id', $state)->first();
                    if ($defaultLocation) {
                        $set('location_id', $defaultLocation->id);
//                        dd($get('location_id'));
                        // Set default courier from city of default location

                        if ($defaultLocation->city && $defaultLocation->city->delivery_man_id) {
                            $set('courier_id', $defaultLocation->city->delivery_man_id);
                        }
                    } else {
                        $set('location_id', null);
                        $set('courier_id', null);
                    }
                })
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
                        ->required(),

                    Forms\Components\TextInput::make('whatsapp')
                        ->maxLength(255)
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
            Forms\Components\Select::make('location_id')
                ->label('Location')
                ->options(function (callable $get) {
                    $customerId = $get('customer_id');

                    return $customerId
                        ? \App\Models\Location::where('customer_id', $customerId)->pluck('title', 'id')
                        : [];
                })
                ->visible(fn (callable $get) => $get('customer_id'))
                ->live()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if(is_string($state)){
                    $location=Location::find($state);
                    }
                    else{
                        $location=$state;
                    }

                    if ($location && $location->city && $location->city->delivery_man_id) {
                        $set('courier_id', $location->city->delivery_man_id);
                    }
                })
                ->columnSpan([
                    'default' => 3,
                    'sm' => 2,
                ])
                ->createOptionForm([
                    Forms\Components\TextInput::make('title')->label('Location Name'),
                    Forms\Components\Select::make('city_id')
                        ->label('City')
                        ->options(fn()=>City::pluck('name','id'))
                        ->required(),
                    Forms\Components\Textarea::make('address')->required(),
                    Forms\Components\TextInput::make('location_link')
                        ->label('Location Link'),
                    Forms\Components\Toggle::make('is_default')->label('Default Pickup Location'),
                ])
                ->createOptionUsing(function (array $data, callable $get, callable $set) {
                    $data['customer_id'] = $get('customer_id');
                    $location = \App\Models\Location::create($data);
                    // Auto-set location_id and courier_id after creation
                    $set('location_id', $location->id);
                    if ($location->city && $location->city->delivery_man_id) {
                        $set('courier_id', $location->city->delivery_man_id);
                    }
                    return $location;
                })
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Add New Location')
                        ->modalSubmitActionLabel('Create')
                        ->visible(!auth()->user()->hasRole('courier'))
                        ->modalWidth('lg');
                })
                ->required(),

            Forms\Components\Placeholder::make('address')
                ->label('Address')
                ->visibleOn('view')
                ->content(fn (Order $record): ?string => $record?->location?->address),

            Forms\Components\Select::make('courier_id')
                ->label('Delivery Man')
                ->options(function (callable $get) {
                    $location = $get('location_id');
                    if(is_int($location)||is_string($location)){
                        $location=Location::find($location);
                    }
                    return $location
                        ? \App\Models\User::where('type', 'courier')->where('governorate_id',$location->city?->governorate->id)->pluck('name', 'id')
                        : [];
                })
                ->disabled(auth()->user()->hasRole('sales'))
                ->visible(fn (callable $get) => ($get('customer_id')&&!auth()->user()->hasRole('courier')))
                ->live()
                ->columnSpan([
                    'default' => 3,
                    'sm' => 2,
                ])
                ->required(),

//            Forms\Components\Select::make('discount_type')
//                ->options([
//                    'percentage' => '%',
//                    'amount' => 'amount'
//                ])->default('amount')
//                ->live(),
//            Forms\Components\TextInput::make('discount_amount')
//                ->label('Discount Amount in EGP')
//                ->numeric()
//                ->disabled(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
//                ->hidden(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
//                ->required(fn (Forms\Get $get) => $get('discount_type') == 'amount')
//                ->rules('gte:0')
//                ->live(),
//
//            Forms\Components\TextInput::make('discount_per')
//                ->label('Discount Percentage 0 => 100')
//                ->numeric()
//                ->disabled(fn (Forms\Get $get) => $get('discount_type') == 'amount')
//                ->hidden(fn (Forms\Get $get) => $get('discount_type') == 'amount')
//                ->required(fn (Forms\Get $get) => $get('discount_type') !== 'amount')
//                ->rules('gte:0|lte:100')
//                ->live(),

            Forms\Components\ToggleButtons::make('order_status')
                ->inline()
                ->options(OrderStatus::class)
                ->default(OrderStatus::New)
                ->columnSpan(2)
                ->required(),
        ];
    }
}
