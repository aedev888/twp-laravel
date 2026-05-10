<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Services\SettingService;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.site-settings';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Система';

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Настройки сайта';
    }

    public static function getNavigationLabel(): string
    {
        return 'Настройки сайта';
    }
    
    public ?array $data = [];

    public function mount(): void
    {
        $settingService = app(SettingService::class);
        $this->form->fill([
            'site_name' => $settingService->getSetting('site_name'),
            'contact_email' => $settingService->getSetting('contact_email'),
            'social_links' => json_decode($settingService->getSetting('social_links') ?? '[]', true),
            'seo_title_default' => $settingService->getSetting('seo_title_default'),
            'seo_description_default' => $settingService->getSetting('seo_description_default'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Настройки')
                    ->tabs([
                        Tabs\Tab::make('Общие')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Название сайта')
                                    ->required(),
                                TextInput::make('contact_email')
                                    ->label('Контактный Email')
                                    ->email()
                                    ->required(),
                            ]),
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-m-magnifying-glass')
                            ->schema([
                                TextInput::make('seo_title_default')
                                    ->label('Заголовок по умолчанию'),
                                Textarea::make('seo_description_default')
                                    ->label('Мета описание по умолчанию')
                                    ->rows(3),
                            ]),
                        Tabs\Tab::make('Соцсети')
                            ->icon('heroicon-m-share')
                            ->schema([
                                Repeater::make('social_links')
                                    ->schema([
                                        Select::make('platform')
                                            ->label('Платформа')
                                            ->options([
                                                'telegram' => 'Telegram',
                                                'twitter' => 'Twitter/X',
                                                'github' => 'GitHub',
                                                'youtube' => 'YouTube',
                                            ])
                                            ->required(),
                                        TextInput::make('url')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->createItemButtonLabel('Добавить соцсеть'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settingService = app(SettingService::class);
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $saveValue = $key === 'social_links' ? json_encode($value) : $value;
            $settingService->setSetting($key, $saveValue);
        }
        
        Notification::make()
            ->title('Настройки успешно сохранены.')
            ->success()
            ->send();
    }
}

