<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Admin  = 'Admin';
    case Editor = 'Editor';
    case User   = 'User';

    public function getLabel(): ?string
    {
        return match($this) {
            self::Admin  => 'Администратор',
            self::Editor => 'Редактор',
            self::User   => 'Пользователь',
        };
    }

    public function canAccessPanel(): bool
    {
        return match($this) {
            self::Admin, self::Editor => true,
            self::User                => false,
        };
    }
}
