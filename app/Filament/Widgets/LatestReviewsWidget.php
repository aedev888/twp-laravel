<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Review;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestReviewsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Последние отзывы';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::latest()->limit(5)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Пользователь'),
                TextColumn::make('product.title')
                    ->label('Товар')
                    ->limit(30),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state === 3 => 'warning',
                        default => 'danger',
                    }),
                IconColumn::make('is_published')
                    ->label('Статус')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->since(),
            ])
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('Смотреть')
                    ->url(fn (Review $record): string => \App\Filament\Resources\ReviewResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
