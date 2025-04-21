<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\City;
use App\Models\Location;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup= 'Orders Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')->label('Location Name'),
                Forms\Components\Select::make('city_id')
                    ->label('City')
                    ->options(fn()=>City::pluck('name','id'))
                    ->required(),
                Forms\Components\Textarea::make('address')->required(),
                Forms\Components\TextInput::make('location_link')
                    ->label('Location Link'),
                Forms\Components\Toggle::make('is_default')->label('Default Pickup Location'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_link')
                    ->label('Location')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('info')
                    ->tooltip('Click to view location')
                    ->state(fn (Location $record) => $record->location_link)
                    ->formatStateUsing(fn ($state) => $state ? 'Location' : 'No Link')
                    ->url(fn ($state) => $state?? null)
                    ->openUrlInNewTab()
                    ->alignLeft()
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
            'index' => Pages\ListLocation::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
