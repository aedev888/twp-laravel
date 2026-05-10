<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\DownloadLog;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DownloadsChart extends ChartWidget
{
    protected ?string $heading = 'Тренды загрузок';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Using basic query if Trend package is not available, 
        // but assuming we can implement a simple version or check composer.json
        
        $data = DownloadLog::selectRaw('date(downloaded_at) as date, count(*) as aggregate')
            ->where('downloaded_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Загрузки',
                    'data' => $data->map(fn ($value) => $value->aggregate),
                    'fill' => 'start',
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
