<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;
    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-photo';
    protected static \UnitEnum | string | null $navigationGroup = 'Система';
    protected static ?string $pluralLabel = 'Библиотека файлов';

    public static function getModelLabel(): string
    {
        return 'Файл';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Библиотека файлов';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('url')
                    ->label('Превью')
                    ->square()
                    ->getStateUsing(fn ($record) => $record->getUrl()),
                TextColumn::make('file_name')
                    ->label('Имя файла')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Тип MIME')
                    ->badge(),
                TextColumn::make('size')
                    ->label('Размер')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB'),
                TextColumn::make('collection_name')
                    ->label('Коллекция')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label('Загружен')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('collection_name')
                    ->label('Коллекция')
                    ->options([
                        'product-previews' => 'Превью товаров',
                        'product-files' => 'Файлы товаров',
                        'settings' => 'Настройки',
                    ]),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MediaResource\Pages\ListMedia::route('/'),
        ];
    }
}
