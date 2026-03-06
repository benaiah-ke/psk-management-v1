<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('membership:send-renewal-reminders')->dailyAt('08:00');
Schedule::command('membership:expire-overdue')->dailyAt('00:00');
Schedule::command('events:send-reminders')->dailyAt('09:00');
Schedule::command('cpd:send-alerts')->weeklyOn(1, '08:00');
