<?php
namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\CreateAction;

class LocationRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Location Name'),
            Forms\Components\Select::make('city_id')
                ->relationship('city', 'name')
                ->required(),
            Forms\Components\Textarea::make('address')->required(),
            Forms\Components\TextInput::make('location_link')
                ->label('Location Link'),
            Forms\Components\Toggle::make('is_default')->label('Default Pickup Location'),
        ]);
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('address')->limit(40),
                Tables\Columns\TextColumn::make('location_link')
                    ->label('Location')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('info')
                    ->tooltip('Click to view location')
                    ->formatStateUsing(fn ($state) => $state ? 'Location' : 'No Link')
                    ->url(fn ($state) => filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                    ->openUrlInNewTab()
                    ->alignLeft()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_default')
                    ->label('Default'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
