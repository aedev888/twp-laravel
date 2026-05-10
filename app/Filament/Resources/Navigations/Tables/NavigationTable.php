<?php

declare(strict_types=1);

namespace App\Filament\Resources\Navigations\Tables;

use Filament\Tables\Columns\TextColumn;

class NavigationTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            TextColumn::make('location')
                ->badge()
                ->color('gray')
                ->searchable()
                ->sortable(),
            TextColumn::make('items_count')
                ->label('Items')
                ->state(fn ($record): int => count($record->items ?? [])),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
