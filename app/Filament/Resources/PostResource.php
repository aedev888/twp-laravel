<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static \BackedEnum | string | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static \UnitEnum | string | null $navigationGroup = 'Контент';

    public static function getModelLabel(): string
    {
        return 'Статья';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Блог';
    }

    public static function form(Schema $schema): Schema
    {
        return PostResource\Schemas\PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostResource\Tables\PostTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
