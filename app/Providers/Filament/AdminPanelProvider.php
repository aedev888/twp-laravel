<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->font('Outfit')
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->maxContentWidth('full')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => new HtmlString('
                    <style>
                        :root { 
                            --content-max-width: 100% !important; 
                            --p-primary-500: #6366f1 !important;
                        }
                        .fi-main-ctn, .fi-main, .fi-layout, .fi-page, .fi-topbar-ctn, .fi-section, .fi-sc-component { 
                            max-width: none !important; 
                            width: 100% !important; 
                        }
                        .fi-main-ctn { 
                            padding-left: 2rem !important; 
                            padding-right: 2rem !important; 
                            margin: 0 !important;
                        }
                        .fi-sidebar {
                            background: rgba(15, 23, 42, 0.02) !important;
                            border-right: 1px solid rgba(0, 0, 0, 0.05) !important;
                        }
                        .fi-btn.fi-color-primary {
                            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);
                            transition: all 0.2s ease-in-out;
                            border-radius: 0.75rem !important;
                        }
                        .fi-btn.fi-color-primary:hover {
                            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.23);
                            transform: translateY(-1px);
                        }
                        .fi-card {
                            border-radius: 1.25rem !important;
                            border: 1px solid rgba(0, 0, 0, 0.05) !important;
                            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05) !important;
                        }
                    </style>
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetAdminLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

