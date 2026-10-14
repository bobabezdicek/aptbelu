<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        CarbonImmutable::setLocale(config('app.locale'));
    }
}
