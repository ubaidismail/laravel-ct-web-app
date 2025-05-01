<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\InvoiceResource\Widgets\RevenueChart;
use App\Filament\Resources\InvoiceResource\Widgets\RevenueChartInPKR;
use App\Filament\Resources\InvoiceResource\Widgets\AnnualRevenue;
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


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        // access dashboard with admin user role only
       
        ->brandLogo(fn () => view('brand'))
        ->darkModeBrandLogo(fn () => view('brand-darkMode'))
        ->favicon(asset('images/fav.png'))
            ->default()
            ->id('')
            ->path('/')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => '#1abefe',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->registration(Register::class)
            ->pages([
                // UsersList::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                RevenueChart::class,
                RevenueChartInPKR::class,
                AnnualRevenue::class,
            ])
            // ->theme(asset('css/filament///theme.css')),
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
                \App\Http\Middleware\CheckUserRole::class . ':admin', // Apply the role check middleware for admin
            ])
            ;

            // NavigationItem::make('users')
            // ->icon('heroicon-o-user')
            // ->url(fn (): string => '/users/')
            // ->visible(fn (): bool => auth()->user()->role !== 'admin');
           
    }
}
