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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MarcelWeidum\Passkeys\PasskeysPlugin;
use Rarq\FilamentQuickNotes\FilamentQuickNotesPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->brandLogo(fn () => view('filament.custom.brand-logo'))
            ->favicon(asset('img/symbol.png'))
            ->profile()
            ->passwordReset()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
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
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_START,
                fn (): string => \Illuminate\Support\Facades\Blade::render(<<<'HTML'
                    <meta property="og:type" content="website">
                    <meta property="og:url" content="{{ url()->current() }}">
                    <meta property="og:title" content="BroadKaster — Admin Panel">
                    <meta property="og:description" content="BroadKaster is a professional real-time tournament broadcasting platform.">
                    <meta property="og:image" content="{{ asset('img/symbol-preview.jpg') }}">
                    <meta property="og:image:secure_url" content="{{ asset('img/symbol-preview.jpg') }}">
                    <meta property="og:image:width" content="800">
                    <meta property="og:image:height" content="800">
                    <meta name="twitter:card" content="summary_large_image">
                    <meta name="twitter:title" content="BroadKaster — Admin Panel">
                    <meta name="twitter:description" content="BroadKaster is a professional real-time tournament broadcasting platform.">
                    <meta name="twitter:image" content="{{ asset('img/symbol-preview.jpg') }}">
                HTML)
            )
            ->plugins([
                PasskeysPlugin::make(),
                FilamentQuickNotesPlugin::make()
            ])

            ->resourceEditPageRedirect('index')
            ->resourceCreatePageRedirect('index');
    }
}

