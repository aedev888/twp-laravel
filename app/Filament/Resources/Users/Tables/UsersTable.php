<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('role')
                    ->label('Роль')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'Admin' => 'danger',
                        'Editor' => 'warning',
                        'User' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state?->value ?? $state) {
                        'Admin' => 'Админ',
                        'Editor' => 'Редактор',
                        'User' => 'Пользователь',
                        default => $state,
                    })
                    ->sortable(),
                
                TextColumn::make('subscription_type')
                    ->label('Подписка')
                    ->badge()
                    ->color(fn ($state): string => match ($state?->value ?? $state) {
                        'premium' => 'info',
                        'free' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state?->value ?? $state) {
                        'premium' => 'Премиум',
                        'free' => 'Бесплатно',
                        default => $state,
                    })
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

