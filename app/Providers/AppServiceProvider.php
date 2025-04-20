<?php

namespace App\Providers;

use App\Auth\DualHashUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
// filament color
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Navigation\NavigationItem;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // Register any application services here

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::provider('dual-hash', function ($app, array $config) {
            return new DualHashUserProvider($app['hash'], $config['model']);
        });
        
            

    }
}
