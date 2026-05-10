<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;

class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\IconColumn::make('is_featured')
                    ->label('В топе')
                    ->boolean()
                    ->sortable(),
                \Filament\Tables\Columns\ImageColumn::make('preview')
                    ->label('Превью')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_type')
                    ->label('Тип цены')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'free' => 'success',
                        'premium' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state?->value ?? $state) {
                        'free' => 'Бесплатно',
                        'premium' => 'Премиум',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state?->value ?? $state) {
                        'published' => 'Опубликован',
                        'draft' => 'Черновик',
                        default => $state,
                    }),
                TextColumn::make('version')
                    ->label('Версия')
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Просмотры')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('downloads_count')
                    ->label('Продажи')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('average_rating')
                    ->label('Рейтинг')
                    ->numeric(1)
                    ->icon('heroicon-m-star')
                    ->iconColor('warning')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('published_at')
                    ->label('Дата публикации')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликован',
                    ]),
                SelectFilter::make('price_type')
                    ->label('Тип цены')
                    ->options([
                        'free' => 'Бесплатно',
                        'premium' => 'Премиум',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    EditAction::make(),
                    \Filament\Actions\Action::make('send_to_telegram')
                        ->label('В Telegram')
                        ->icon('heroicon-m-paper-airplane')
                        ->color('info')
                        ->action(function (\App\Models\Product $record, \App\Services\TelegramService $telegramService) {
                            $success = $telegramService->sendProductToChannel($record);
                            
                            if ($success) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Отправлено в Telegram')
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Ошибка отправки')
                                    ->body('Проверьте логи Laravel для деталей.')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Отправить в Telegram')
                        ->modalDescription('Вы уверены, что хотите опубликовать этот продукт в Telegram канале?'),
                    \Filament\Actions\Action::make('view_on_site')
                        ->label('Смотреть на сайте')
                        ->icon('heroicon-m-arrow-top-right-on-square')
                        ->url(fn (\App\Models\Product $record): string => route('products.show', $record))
                        ->openUrlInNewTab(),
                    \Filament\Actions\Action::make('toggle_publish')
                        ->label(fn (\App\Models\Product $record): string => $record->status->value === 'published' ? 'В черновики' : 'Опубликовать')
                        ->icon(fn (\App\Models\Product $record): string => $record->status->value === 'published' ? 'heroicon-m-x-circle' : 'heroicon-m-check-circle')
                        ->color(fn (\App\Models\Product $record): string => $record->status->value === 'published' ? 'danger' : 'success')
                        ->action(function (\App\Models\Product $record) {
                            $newStatus = $record->status->value === 'published' ? 'draft' : 'published';
                            $record->update([
                                'status' => $newStatus,
                                'published_at' => $newStatus === 'published' ? now() : $record->published_at,
                            ]);
                        })
                        ->requiresConfirmation(),
                    \Filament\Actions\Action::make('toggle_featured')
                        ->label(fn (\App\Models\Product $record): string => $record->is_featured ? 'Убрать из топа' : 'В топ')
                        ->icon('heroicon-m-star')
                        ->color(fn (\App\Models\Product $record): string => $record->is_featured ? 'danger' : 'warning')
                        ->action(fn (\App\Models\Product $record) => $record->update(['is_featured' => !$record->is_featured])),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('publish_all')
                        ->label('Опубликовать выбранные')
                        ->icon('heroicon-m-check-circle')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['status' => 'published', 'published_at' => now()]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
