<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\CausesActivity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity, CausesActivity;

    /**
     * Default model attributes.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'role'              => 'User',
        'subscription_type' => 'free',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription_type',
        'subscription_ends_at',
        'telegram_id',
        'telegram_username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'subscription_ends_at'=> 'datetime',
            'role'                => UserRole::class,
            'subscription_type'   => SubscriptionType::class,
            'telegram_id'         => 'integer',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role?->canAccessPanel() ?? false;
    }

    /**
     * Check if user has an active premium subscription.
     */
    public function isPremium(): bool
    {
        return $this->subscription_type?->isPremium() ?? false;
    }

    /**
     * Check if user registered via Telegram (no password).
     */
    public function isTelegramUser(): bool
    {
        return $this->telegram_id !== null;
    }

    public function wishlist(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function downloadLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    public function collections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->logExcept(['password', 'remember_token']);
    }
}
