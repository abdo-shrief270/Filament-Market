<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup= 'Orders Management';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->default(Request::get('phone'))
                    ->required(),
                Forms\Components\TextInput::make('whatsapp')
                    ->default(Request::get('phone'))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('buy_count')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Customer Phone')
                    ->icon('heroicon-o-phone-arrow-up-right')
                    ->iconColor('primary')
                    ->state(fn (Customer $record) => $record->phone)
                    ->formatStateUsing(fn (Customer $record) => $record->phone ?'Call': 'No Phone')
                    ->url(fn (Customer $record) => $record->phone ? 'tel:' . $record->phone : null)
                    ->openUrlInNewTab()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('Customer Whatsapp')
                    ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                    ->iconColor('success')
                    ->state(fn (Customer $record) => $record->whatsapp)
                    ->formatStateUsing(fn (Customer $record) => $record->whatsapp ?'Chat': 'No whatsapp')
                    ->url(fn (Customer $record) => $record->whatsapp ? 'tel:' . $record->whatsapp : null)
                    ->openUrlInNewTab()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->url(fn ($state) =>'mailto:'.$state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('buy_count')
                    ->sortable(),
            ])
            ->defaultSort('updated_at','desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
            RelationManagers\LocationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
