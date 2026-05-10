<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function getPublicSettings(): array
    {
        return Cache::remember('settings.public', 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return Cache::remember("settings.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
    
    public function setSetting(string $key, mixed $value, string $type = 'string'): Setting
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
        
        Cache::forget('settings.public');
        Cache::forget("settings.{$key}");
        
        return $setting;
    }
}
