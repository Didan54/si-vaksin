<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            if (
                request()->header('X-Forwarded-Proto') === 'https' ||
                str_contains(request()->header('cf-visitor', ''), 'https') ||
                str_contains(request()->getHost(), 'trycloudflare.com')
            ) {
                URL::forceScheme('https');
            }
        }
    }
}