<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Summary')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->required()
                                ->disabled(),
                            Select::make('product_id')
                                ->relationship('product', 'title')
                                ->required()
                                ->disabled(),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'completed' => 'Completed',
                                    'failed' => 'Failed',
                                    'refunded' => 'Refunded',
                                ])
                                ->required(),
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('amount')
                                ->numeric()
                                ->required()
                                ->prefix('$'),
                            TextInput::make('payment_id')
                                ->label('Payment Transaction ID')
                                ->disabled(),
                        ]),
                    KeyValue::make('metadata')
                        ->label('Technical Metadata')
                        ->disabled(),
                ])
        ]);
    }
}
