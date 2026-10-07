<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Pastikan ini di-import

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa semua asset() dan route() memakai https jika bukan di localhost murni
        if (request()->server->has('HTTP_X_FORWARDED_PROTO') || str_contains(request()->url(), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }
    }
}