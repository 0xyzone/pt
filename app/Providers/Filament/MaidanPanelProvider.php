<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MarcelWeidum\Passkeys\PasskeysPlugin;
use Rarq\FilamentQuickNotes\FilamentQuickNotesPlugin;

class MaidanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('maidan')
            ->path('maidan')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            ->profile()
            ->passwordReset()
            ->registration()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/Maidan/Resources'), for: 'App\Filament\Maidan\Resources')
            ->discoverPages(in: app_path('Filament/Maidan/Pages'), for: 'App\Filament\Maidan\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Maidan/Widgets'), for: 'App\Filament\Maidan\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                PasskeysPlugin::make(),
                FilamentQuickNotesPlugin::make()
            ])
            ->resourceEditPageRedirect('index')
            ->resourceCreatePageRedirect('index');
    }
}
