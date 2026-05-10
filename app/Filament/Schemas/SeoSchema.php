<?php

declare(strict_types=1);

namespace App\Filament\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;

class SeoSchema
{
    public static function make(string $field = 'seo'): Section
    {
        return Section::make('Search Engine Optimization')
            ->description('Refine how your content appears in search engines and social media.')
            ->icon('heroicon-o-magnifying-glass')
            ->collapsed()
            ->schema([
                Group::make()
                    ->statePath($field)
                    ->schema([
                        Tabs::make('SEO Options')
                            ->tabs([
                                Tabs\Tab::make('General')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('SEO Title')
                                            ->helperText('Defaults to the product title. Keep it under 60 characters.')
                                            ->maxLength(60)
                                            ->live(onBlur: true),
                                        
                                        Textarea::make('description')
                                            ->label('Meta Description')
                                            ->helperText('A brief summary of the page. Recommended: 150-160 characters.')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->live(onBlur: true),

                                        TextInput::make('keywords')
                                            ->label('Keywords')
                                            ->placeholder('comma, separated, keywords'),
                                        
                                        TextInput::make('focus_keyword')
                                            ->label('Focus Keyword')
                                            ->placeholder('e.g., laravel cms')
                                            ->helperText('The main keyword you want to rank for.'),
                                    ]),

                                Tabs\Tab::make('Social')
                                    ->icon('heroicon-o-share')
                                    ->schema([
                                        FileUpload::make('og_image')
                                            ->label('Social Share Image (OG Image)')
                                            ->image()
                                            ->directory('seo')
                                            ->helperText('Recommended size: 1200x630px.'),
                                        
                                        TextInput::make('og_title')
                                            ->label('Social Title')
                                            ->placeholder('Optional override for Facebook/Twitter'),

                                        Textarea::make('og_description')
                                            ->label('Social Description')
                                            ->rows(2),
                                    ]),

                                Tabs\Tab::make('Advanced')
                                    ->icon('heroicon-o-cog')
                                    ->schema([
                                        Toggle::make('is_noindex')
                                            ->label('Hide from search engines (noindex)')
                                            ->onColor('danger')
                                            ->default(false),
                                        
                                        Toggle::make('is_nofollow')
                                            ->label('Do not follow links (nofollow)')
                                            ->default(false),

                                        TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url()
                                            ->placeholder('https://example.com/original-page')
                                            ->helperText('Used to prevent duplicate content issues.'),
                                    ]),
                            ])
                            ->persistTabInQueryString(),
                    ])
            ]);
    }
}
