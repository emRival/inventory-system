<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        });
        // Tambahkan script saat Filament sedang dilayani
        Filament::serving(function () {
            FilamentAsset::register([
                Js::make('geo-prompt', asset('js/geo-prompt.js')),
            ]);
        });
    }
}
