<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        AuditLog::log(
            'login',
            'User',
            $event->user->getAuthIdentifier(),
            null,
            null,
            null,
            'User logged in'
        );

        // Sustainability & Performance: Auto-Optimize if enabled
        try {
            if (\App\Models\Setting::get('auto_optimize', false) === 'true') {
                \Illuminate\Support\Facades\Artisan::call('optimize');
            }
        } catch (\Exception $e) {
            // Silently fail to not block login
        }
    }
}
