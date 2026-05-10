<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ActivitylogTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Время'),
            TextColumn::make('log_name')
                ->label('Лог')
                    ->badge()
                    ->sortable(),
            TextColumn::make('event')
                ->label('Событие')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'created' => 'success',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'created' => 'Создано',
                    'updated' => 'Обновлено',
                    'deleted' => 'Удалено',
                    default => $state,
                }),
            TextColumn::make('subject_type')
                ->formatStateUsing(fn (string $state): string => str_replace('App\\Models\\', '', $state))
                ->label('Модель'),
            TextColumn::make('subject_id')
                ->label('ID'),
            TextColumn::make('causer.name')
                ->label('Пользователь'),
            TextColumn::make('description')
                ->label('Описание')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('event')
                ->label('Событие')
                ->options([
                    'created' => 'Создано',
                    'updated' => 'Обновлено',
                    'deleted' => 'Удалено',
                ]),
        ];
    }
}
