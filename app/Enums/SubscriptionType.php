<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubscriptionType: string implements HasLabel
{
    case Free    = 'free';
    case Premium = 'premium';

    public function getLabel(): ?string
    {
        return match($this) {
            self::Free    => 'Бесплатно',
            self::Premium => 'Премиум',
        };
    }

    public function isPremium(): bool
    {
        return $this === self::Premium;
    }
}
