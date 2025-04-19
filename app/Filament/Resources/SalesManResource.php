<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesManResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesManResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Sales Men';
    protected static ?string $modelLabel = 'Sales Man';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup= 'Delivery Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->minLength(8),
                Forms\Components\Toggle::make('active'),
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
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active'),


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
        return static::getModel()::where('type','sales')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('type','sales')->count() > 10 ? 'warning' : 'primary';
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
            'index' => Pages\ListSalesMen::route('/'),
            'create' => Pages\CreateSalesMan::route('/create'),
            'edit' => Pages\EditSalesMan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type','sales');
    }
}
