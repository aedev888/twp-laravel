<?php

declare(strict_types=1);

namespace App\Filament\Resources\Navigations\Pages;

use App\Filament\Resources\Navigations\NavigationResource;
use Filament\Resources\Pages\EditRecord;

class EditNavigation extends EditRecord
{
    // use EditRecord\Concerns\HasTranslatableForm;

    protected static string $resource = NavigationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\LocaleSwitcher::make(),
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
