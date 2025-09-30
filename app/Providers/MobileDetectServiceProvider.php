<?php

namespace App\Providers;

use Detection\MobileDetect;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class MobileDetectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $browser = new MobileDetect();

        View::share('browser', $browser);
    }
}
