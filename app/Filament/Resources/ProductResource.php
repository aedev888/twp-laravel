<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    protected static \BackedEnum | string | null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static \UnitEnum | string | null $navigationGroup = 'Каталог';

    public static function getModelLabel(): string
    {
        return 'Товар';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Товары';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductResource\Schemas\ProductForm::configure($schema->columns(12));
    }

    public static function table(Table $table): Table
    {
        return ProductResource\Tables\ProductTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductResource\RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
