<?php

use App\Console\Commands\NotificationBookAvailability;
use App\Models\NotificationBook;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:notification-book-availability')->twiceDaily(10, 18);
