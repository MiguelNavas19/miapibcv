<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


$schedules = [
    '00:00',
    '00:30',
    '02:00',
    '03:00',
    '03:30',
    '04:00',
    '05:00',
    '05:30',
    '06:30',
    '07:00',
    '07:30'
];

foreach ($schedules as $time) {
    Schedule::command('rates:update')->dailyAt($time);
}
