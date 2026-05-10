<?php

declare(strict_types=1);

namespace App\Filament\Resources\Navigations\Pages;

use App\Filament\Resources\Navigations\NavigationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNavigation extends CreateRecord
{
    // use CreateRecord\Concerns\HasTranslatableForm;

    protected static string $resource = NavigationResource::class;
}
