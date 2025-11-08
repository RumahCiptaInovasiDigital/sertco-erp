<?php

namespace App\Providers;

use Detection\MobileDetect;
use Illuminate\Support\ServiceProvider;
use View;

class MobileDetectProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $browser = new MobileDetect();

        View::share('browser', $browser);
    }
}
