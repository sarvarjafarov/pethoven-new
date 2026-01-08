<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Facades\Telemetry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Telemetry::optOut();

        if ($this->app->bound('lunar-panel')) {
            $this->app->get('lunar-panel')->register();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
