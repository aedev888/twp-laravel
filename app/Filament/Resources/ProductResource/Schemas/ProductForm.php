<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Schemas;

use App\Filament\Schemas\SeoSchema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(12)
                ->schema([
                    Group::make([
                        Tabs::make('Контент товара')
                            ->tabs([
                                Tabs\Tab::make('Общие')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Заголовок')
                                            ->placeholder('Введите заголовок товара')
                                            ->required()
                                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? array_values($state)[0] ?? '') : $state)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                                $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->placeholder('product-url-slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->prefix('marketplace/'),
                                        MarkdownEditor::make('description')
                                            ->label('Описание')
                                            ->placeholder('Детальное описание вашего товара...')
                                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? array_values($state)[0] ?? '') : $state)
                                            ->columnSpanFull(),
                                    ]),
                                Tabs\Tab::make('Медиа')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                FileUpload::make('preview_path')
                                                    ->label('Главное изображение')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('product-previews')
                                                    ->visibility('public')
                                                    ->maxSize(51200) // 50MB
                                                    ->imageEditor()
                                                    ->columnSpan(1),
                                                FileUpload::make('file_path')
                                                    ->label('Файл ассета (ZIP)')
                                                    ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                                                    ->disk('public')
                                                    ->directory('product-files')
                                                    ->visibility('public')
                                                    ->maxSize(102400) // 100MB
                                                    ->columnSpan(1),
                                            ]),
                                    ]),
                                Tabs\Tab::make('SEO')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->schema([
                                        SeoSchema::make(),
                                    ]),
                                Tabs\Tab::make('Changelog')
                                    ->icon('heroicon-o-clock')
                                    ->schema([
                                        Repeater::make('changelog')
                                            ->label('Релизы')
                                            ->schema([
                                                TextInput::make('version')
                                                    ->placeholder('1.1.0')
                                                    ->required(),
                                                DateTimePicker::make('release_date')
                                                    ->default(now()),
                                                Repeater::make('changes')
                                                    ->schema([
                                                        TextInput::make('change')
                                                            ->placeholder('What\'s new in this version?')
                                                            ->required(),
                                                    ])
                                                    ->simple(TextInput::make('change'))
                                                    ->required(),
                                            ])
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string => $state['version'] ?? null),
                                    ]),
                                Tabs\Tab::make('FAQ')
                                    ->icon('heroicon-o-question-mark-circle')
                                    ->schema([
                                        Repeater::make('faq')
                                            ->label('Часто задаваемые вопросы')
                                            ->schema([
                                                TextInput::make('question')
                                                    ->required(),
                                                TextInput::make('answer')
                                                    ->required(),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null),
                                    ]),
                            ]),
                    ])->columnSpan(8),

                    Group::make([
                        Section::make('Статус и видимость')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('В топе')
                                    ->helperText('Отображать в топ-секциях')
                                    ->onIcon('heroicon-m-star')
                                    ->offIcon('heroicon-m-star')
                                    ->onColor('warning'),

                                Select::make('status')
                                    ->options(\App\Enums\ProductStatus::class)
                                    ->default('draft')
                                    ->required()
                                    ->native(false),

                                DateTimePicker::make('published_at')
                                    ->label('Дата публикации')
                                    ->native(false),
                            ]),

                        Section::make('Цена и ссылки')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                ToggleButtons::make('price_type')
                                    ->options(\App\Enums\ProductPriceType::class)
                                    ->icons([
                                        'free' => 'heroicon-o-gift',
                                        'premium' => 'heroicon-o-credit-card',
                                    ])
                                    ->default('free')
                                    ->required(),

                                TextInput::make('version')
                                    ->placeholder('1.0.0')
                                    ->prefix('v'),

                                TextInput::make('demo_url')
                                    ->url()
                                    ->placeholder('https://...')
                                    ->prefixIcon('heroicon-o-link'),
                            ]),

                        Section::make('Категории')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Select::make('taxonomies')
                                    ->multiple()
                                    ->relationship('taxonomies', 'name')
                                    ->preload()
                                    ->native(false),
                            ]),
                    ])->columnSpan(4),
                ])
                ->columnSpanFull()
        ]);
    }
}
