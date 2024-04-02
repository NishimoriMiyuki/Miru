<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // URL強制HTTPS
        \URL::forceScheme('https');
        
        //リクエスト強制HTTPS 
        $this->app['request']->server->set('HTTPS','on');
    }
}
