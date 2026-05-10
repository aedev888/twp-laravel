<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\SearchLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class FailedSearchesWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = '🔍 Неудачные поиски (0 результатов)';

    public function getTableRecordKey($record): string
    {
        return (string) ($record->query);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SearchLog::query()
                    ->where('results_count', 0)
                    ->select('query', DB::raw('count(*) as search_count'), DB::raw('max(created_at) as last_searched_at'), DB::raw('min(id) as id'))
                    ->groupBy('query')
            )
            ->columns([
                Tables\Columns\TextColumn::make('query')
                    ->label('Поисковый запрос')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('search_count')
                    ->label('Попытки')
                    ->sortable()
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('last_searched_at')
                    ->label('Последняя попытка')
                    ->dateTime()
                    ->since()
                    ->color('gray'),
            ])
            ->defaultSort('search_count', 'desc')
            ->paginated(false); // Disable pagination to avoid automatic ID sorting
    }
}
