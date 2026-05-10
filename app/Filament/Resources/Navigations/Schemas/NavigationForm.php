<?php

declare(strict_types=1);

namespace App\Filament\Resources\Navigations\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class NavigationForm
{
    public static function make(): array
    {
        return [
            Section::make('Menu Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('location')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g. main-menu, footer-links'),
                ])->columns(2),

            Section::make('Menu Items')
                ->schema([
                    Repeater::make('items')
                        ->label('Items')
                        ->schema([
                            TextInput::make('label')
                                ->required(),
                            TextInput::make('url')
                                ->required(),
                            Toggle::make('is_external')
                                ->label('Open in new tab'),
                        ])
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->reorderableWithButtons()
                ]),
        ];
    }
}
