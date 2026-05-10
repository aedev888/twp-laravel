<?php

declare(strict_types=1);

namespace App\Filament\Resources\Navigations;

use App\Filament\Resources\Navigations\Pages;
use App\Models\Navigation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;


class NavigationResource extends Resource
{
    protected static ?string $model = Navigation::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-bars-3';

    protected static \UnitEnum | string | null $navigationGroup = 'Система';

    public static function getModelLabel(): string
    {
        return 'Навигация';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Навигация';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(Schemas\NavigationForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Tables\NavigationTable::columns())
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigations::route('/'),
            'create' => Pages\CreateNavigation::route('/create'),
            'edit' => Pages\EditNavigation::route('/{record}/edit'),
        ];
    }
}
