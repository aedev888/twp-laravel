<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class ActivitylogSchema
{
    public static function make(): array
    {
        return [
            Section::make('Общая информация')
                ->schema([
                    TextInput::make('event')
                        ->label('Событие'),
                    TextInput::make('subject_type')
                        ->label('Тип модели'),
                    TextInput::make('subject_id')
                        ->label('ID модели'),
                    TextInput::make('causer.name')
                        ->label('Пользователь'),
                ])->columns(2),
            Section::make('Изменения')
                ->schema([
                    KeyValue::make('properties.attributes')
                        ->label('Новые значения'),
                    KeyValue::make('properties.old')
                        ->label('Старые значения'),
                ]),
        ];
    }
}
