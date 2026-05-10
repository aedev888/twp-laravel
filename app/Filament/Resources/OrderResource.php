<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static \BackedEnum | string | null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static \UnitEnum | string | null $navigationGroup = 'Продажи';

    public static function getModelLabel(): string
    {
        return 'Заказ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Заказы';
    }

    public static function form(Schema $schema): Schema
    {
        return OrderResource\Schemas\OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderResource\Tables\OrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
