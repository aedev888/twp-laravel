<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action as FilamentAction;
use App\Models\Order;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID заказа')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Покупатель')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.title')
                    ->label('Товар')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency)
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed', 'refunded' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'Завершен',
                        'pending' => 'Ожидает',
                        'failed' => 'Ошибка',
                        'refunded' => 'Возврат',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('payment_gateway')
                    ->label('Шлюз')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'pending' => 'Ожидает',
                        'completed' => 'Завершен',
                        'failed' => 'Ошибка',
                        'refunded' => 'Возврат',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('payment_gateway')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                    ]),
            ])
            ->actions([
                FilamentAction::make('mark_completed')
                    ->label('Завершить')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(fn (Order $record) => $record->update(['status' => 'completed'])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
