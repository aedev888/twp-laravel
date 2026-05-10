<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReviewResource\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Review Details')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->required()
                                ->searchable(),
                            Select::make('product_id')
                                ->relationship('product', 'title')
                                ->required()
                                ->searchable(),
                            TextInput::make('rating')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(5)
                                ->required(),
                            Toggle::make('is_published')
                                ->label('Published')
                                ->default(true),
                        ]),
                    Textarea::make('content')
                        ->label('Review Content')
                        ->required()
                        ->columnSpanFull(),
                ])
        ]);
    }
}
