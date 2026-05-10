<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static \BackedEnum | string | null $navigationIcon = Heroicon::OutlinedStar;

    protected static \UnitEnum | string | null $navigationGroup = 'Каталог';

    public static function getModelLabel(): string
    {
        return 'Отзыв';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Отзывы';
    }

    public static function form(Schema $schema): Schema
    {
        return ReviewResource\Schemas\ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewResource\Tables\ReviewsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
