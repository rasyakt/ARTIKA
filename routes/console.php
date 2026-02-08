<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Automatically deactivate expired promos.
 */
\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Models\Promo::expired()->update(['is_active' => false]);
})->daily();
