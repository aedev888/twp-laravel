<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CacheManagementWidget extends Widget
{
    protected string $view = 'filament.widgets.cache-management';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getSystemHealth(): array
    {
        $diskFree = disk_free_space(base_path());
        $diskTotal = disk_total_space(base_path());
        $diskUsage = round((($diskTotal - $diskFree) / $diskTotal) * 100, 1);

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_connection' => config('database.default'),
            'disk_usage' => $diskUsage,
            'disk_free' => round($diskFree / (1024 * 1024 * 1024), 2) . ' GB',
            'debug_mode' => config('app.debug') ? 'Включен' : 'Выключен',
        ];
    }

    public function clearCache(string $type): void
    {
        try {
            match ($type) {
                'all' => Artisan::call('optimize:clear'),
                'view' => Artisan::call('view:clear'),
                'config' => Artisan::call('config:clear'),
                'route' => Artisan::call('route:clear'),
                'cache' => Artisan::call('cache:clear'),
            };

            Notification::make()
                ->title('Кеш ' . $type . ' очищен!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Ошибка при очистке кеша')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
