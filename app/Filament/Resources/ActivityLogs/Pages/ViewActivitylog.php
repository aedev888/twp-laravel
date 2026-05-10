<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivitylogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewActivitylog extends ViewRecord
{
    protected static string $resource = ActivitylogResource::class;
}
