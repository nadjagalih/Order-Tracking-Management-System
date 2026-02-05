<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Optimize for production
        if (env('APP_ENV') === 'production' || str_contains(env('APP_URL', ''), 'ngrok')) {
            // Disable debug bar in production
            $this->app['config']->set('app.debug', false);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS untuk ngrok
        if (str_contains(env('APP_URL', ''), 'ngrok') || str_contains(env('APP_URL', ''), 'https')) {
            URL::forceScheme('https');
        }

        // Optimize view caching
        if (!app()->environment('local') || str_contains(env('APP_URL', ''), 'ngrok')) {
            View::share('appVersion', Cache::remember('app_version', 3600, function () {
                return md5_file(base_path('composer.json'));
            }));
        }
    }
}
