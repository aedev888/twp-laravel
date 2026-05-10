<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\DownloadLog;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalDownloads = DownloadLog::count();
        $recentDownloads = DownloadLog::where('downloaded_at', '>=', now()->subDays(7))->count();
        $prevDownloads = DownloadLog::where('downloaded_at', '<', now()->subDays(7))
            ->where('downloaded_at', '>=', now()->subDays(14))
            ->count();
        
        $downloadDiff = $totalDownloads > 0 ? (($recentDownloads - $prevDownloads) / max(1, $prevDownloads)) * 100 : 0;

        return [
            Stat::make('Всего загрузок', number_format($totalDownloads))
                ->description($recentDownloads . ' за последние 7 дней')
                ->descriptionIcon($downloadDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 10, 5, 2, 10, 12, 15])
                ->color($downloadDiff >= 0 ? 'success' : 'danger'),

            Stat::make('Премиум ассеты', Product::where('price_type', 'premium')->count())
                ->description('Размер активного каталога')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Конверсия', $this->getConversionRate() . '%')
                ->description('Загрузок на пользователя')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Использовано хранилища', $this->getStorageUsage())
                ->description('Общий размер файлов')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('gray'),

            Stat::make('Общий доход', '$' . number_format((float) Order::where('status', 'paid')->sum('amount'), 2))
                ->description('Доход за все время')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([15, 20, 18, 25, 30, 28, 35])
                ->color('success'),
        ];
    }

    protected function getConversionRate(): float
    {
        $users = User::count();
        $downloads = DownloadLog::distinct('user_id')->count();
        
        return $users > 0 ? round(($downloads / $users) * 100, 1) : 0;
    }

    protected function getStorageUsage(): string
    {
        // Simple mock for now, or we could sum up file sizes in S3/Local
        return '4.2 GB';
    }
}
