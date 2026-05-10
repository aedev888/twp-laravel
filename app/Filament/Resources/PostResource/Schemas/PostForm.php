<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\Schemas;

use App\Filament\Schemas\SeoSchema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(12)
                ->schema([
                    Group::make([
                        Tabs::make('Post Content')
                            ->tabs([
                                Tabs\Tab::make('General')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Title')
                                            ->required()
                                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? array_values($state)[0] ?? '') : $state)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                        
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->prefix('blog/'),

                                        Textarea::make('excerpt')
                                            ->label('Excerpt')
                                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? array_values($state)[0] ?? '') : $state)
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        MarkdownEditor::make('content')
                                            ->label('Content')
                                            ->required()
                                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? array_values($state)[0] ?? '') : $state)
                                            ->columnSpanFull(),
                                    ]),
                                
                                Tabs\Tab::make('Media')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        FileUpload::make('featured_image')
                                            ->label('Featured Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('blog')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),

                                Tabs\Tab::make('SEO')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->schema([
                                        SeoSchema::make(),
                                    ]),

                                Tabs\Tab::make('Intelligence & GEO')
                                    ->icon('heroicon-o-cpu-chip')
                                    ->schema([
                                        \App\Filament\Schemas\GeoSchema::make(),
                                    ]),
                            ]),
                    ])->columnSpan(8),

                    Group::make([
                        Section::make('Status & Visibility')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->native(false),

                                DateTimePicker::make('published_at')
                                    ->label('Published Date')
                                    ->default(now())
                                    ->native(false),
                                
                                Select::make('author_id')
                                    ->relationship('author', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(auth()->id()),
                            ]),

                        Section::make('Categorization')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Select::make('taxonomies')
                                    ->multiple()
                                    ->relationship('taxonomies', 'name', fn ($query) => $query->where('type', 'blog_category'))
                                    ->label('Categories')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required(),
                                        TextInput::make('type')
                                            ->default('blog_category')
                                            ->hidden(),
                                    ])
                                    ->preload()
                                    ->native(false),
                            ]),
                    ])->columnSpan(4),
                ])
                ->columnSpanFull()
        ]);
    }
}
