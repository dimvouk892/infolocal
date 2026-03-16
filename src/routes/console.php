<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// When a subscription has ended (by date), set linked businesses with no active subscription to pending (daily)
Schedule::command('subscriptions:expire')->daily();
