<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;

class CustomerPanelPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->NavigationItems([
                NavigationItem::make('Submit Project')
                    ->icon('heroicon-m-plus-circle')
                    ->label('New Project')
                    ->url("javascript: window.dispatchEvent(new CustomEvent('open-customer-modal'))")
            ])
            ->brandLogo(fn() => view('brand'))
            ->darkModeBrandLogo(fn() => view('brand-darkMode'))
            ->favicon(asset('images/fav.png'))
            ->id('customer')
            ->path('customer')
            ->login()
            ->authGuard('web')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => '#1abefe',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])


            ->discoverResources(in: app_path('Filament/CustomerPanel/Resources'), for: 'App\\Filament\\CustomerPanel\\Resources')
            ->discoverPages(in: app_path('Filament/CustomerPanel/Pages'), for: 'App\\Filament\\CustomerPanel\\Pages')
            ->pages([
                \App\Filament\CustomerPanel\Pages\CustomerDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/CustomerPanel/Widgets'), for: 'App\\Filament\\CustomerPanel\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\CheckUserRole::class . ':customer', // Apply the role check middleware

            ]);
    }
}
