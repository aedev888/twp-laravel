<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;
    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static \UnitEnum | string | null $navigationGroup = 'Система';
    protected string $view = 'filament.pages.manage-settings';

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Управление настройками';
    }

    public static function getNavigationLabel(): string
    {
        return 'Управление настройками';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Настройки')
                    ->tabs([
                        Tabs\Tab::make('Общие')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Название сайта')
                                            ->required(),
                                        TextInput::make('site_tagline')
                                            ->label('Слоган'),
                                        FileUpload::make('site_logo')
                                            ->label('Логотип')
                                            ->image()
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                        FileUpload::make('site_favicon')
                                            ->label('Фавикон')
                                            ->image()
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Мета заголовок'),
                                Textarea::make('seo_description')
                                    ->label('Мета описание'),
                                TextInput::make('seo_keywords')
                                    ->label('Ключевые слова'),
                            ]),
                        Tabs\Tab::make('Соцсети')
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('social_telegram')
                                    ->label('Telegram URL')
                                    ->prefix('https://t.me/'),
                                TextInput::make('social_twitter')
                                    ->label('X (Twitter) URL')
                                    ->prefix('https://x.com/'),
                                TextInput::make('social_github')
                                    ->label('GitHub URL')
                                    ->prefix('https://github.com/'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить изменения')
                ->action('saveSettings'),
        ];
    }

    public function saveSettings(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'string']
            );
        }

        Notification::make()
            ->title('Настройки успешно сохранены')
            ->success()
            ->send();
    }
}
