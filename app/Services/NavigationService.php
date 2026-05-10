<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Navigation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NavigationService
{
    public function getByLocation(string $location): ?Navigation
    {
        return Cache::remember("navigation_{$location}", 3600, function () use ($location) {
            return Navigation::where('location', $location)->first();
        });
    }

    public function clearCache(string $location): void
    {
        Cache::forget("navigation_{$location}");
    }
}
