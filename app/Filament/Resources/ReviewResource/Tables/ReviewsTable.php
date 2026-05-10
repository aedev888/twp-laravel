<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReviewResource\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action as FilamentAction;
use App\Models\Review;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.title')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Рейтинг')
                    ->icon('heroicon-m-star')
                    ->iconColor('warning')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('content')
                    ->label('Комментарий')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->content),
                IconColumn::make('is_published')
                    ->label('Опубликовано')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Статус публикации'),
            ])
            ->actions([
                FilamentAction::make('toggle_publish')
                    ->label(fn (Review $record) => $record->is_published ? 'Снять с публикации' : 'Опубликовать')
                    ->icon(fn (Review $record) => $record->is_published ? 'heroicon-m-x-circle' : 'heroicon-m-check-circle')
                    ->color(fn (Review $record) => $record->is_published ? 'danger' : 'success')
                    ->action(fn (Review $record) => $record->update(['is_published' => !$record->is_published])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
