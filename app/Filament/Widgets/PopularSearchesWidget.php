<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\SearchLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PopularSearchesWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = '📈 Популярные запросы';

    public function getTableRecordKey($record): string
    {
        return (string) ($record->query);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SearchLog::query()
                    ->where('results_count', '>', 0)
                    ->select('query', DB::raw('count(*) as search_count'), DB::raw('avg(results_count) as avg_results'), DB::raw('min(id) as id'))
                    ->groupBy('query')
            )
            ->columns([
                Tables\Columns\TextColumn::make('query')
                    ->label('Поисковый запрос')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('search_count')
                    ->label('Частота')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('avg_results')
                    ->label('Среднее кол-во результатов')
                    ->numeric(1)
                    ->color('gray'),
            ])
            ->defaultSort('search_count', 'desc')
            ->paginated(false);
    }
}
