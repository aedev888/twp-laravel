<?php

declare(strict_types=1);

namespace App\Filament\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Grid;

class GeoSchema
{
    public static function make(): Section
    {
        return Section::make('GEO & AI Intelligence')
            ->description('Optimization for Generative Search Engines (ChatGPT, Perplexity, etc.)')
            ->icon('heroicon-o-cpu-chip')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Textarea::make('geo_data.ai_summary')
                            ->label('AI Targeted Summary')
                            ->helperText('A fact-dense summary optimized for LLM extraction.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Repeater::make('geo_data.key_takeaways')
                            ->label('Key Takeaways')
                            ->schema([
                                TextInput::make('point')
                                    ->placeholder('e.g., Laravel 13 improves performance by 20%')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['point'] ?? null)
                            ->columnSpanFull(),

                        TagsInput::make('geo_data.entities')
                            ->label('Primary Entities')
                            ->placeholder('Add concepts, people, or products')
                            ->helperText('Helps AI build a knowledge graph around this post.')
                            ->columnSpanFull(),

                        Repeater::make('geo_data.faqs')
                            ->label('Semantic FAQ')
                            ->schema([
                                TextInput::make('question')
                                    ->required(),
                                Textarea::make('answer')
                                    ->required()
                                    ->rows(2),
                            ])
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
