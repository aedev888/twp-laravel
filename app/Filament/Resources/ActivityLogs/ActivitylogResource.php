<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages;
use Filament\Resources\Resource;
use Spatie\Activitylog\Models\Activity;

class ActivitylogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static \UnitEnum | string | null $navigationGroup = 'Система';

    public static function getModelLabel(): string
    {
        return 'Лог активности';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Логи активности';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\ActivitylogSchema::make());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns(Tables\ActivitylogTable::columns())
            ->filters(Tables\ActivitylogTable::filters());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivitylogs::route('/'),
            'view' => Pages\ViewActivitylog::route('/{record}'),
        ];
    }
}
