<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class,
        );



        Paginator::useBootstrap();

        // Dynamically set session lifetime based on database setting
        try {
            if (class_exists('App\Models\Setting')) {
                $sessionDuration = \App\Models\Setting::get('session_duration');
                if ($sessionDuration && is_numeric($sessionDuration)) {
                    config(['session.lifetime' => (int) $sessionDuration]);
                }
            }
        } catch (\Exception $e) {
            // Avoid breaking during migrations or if table doesn't exist
        }
    }
}
