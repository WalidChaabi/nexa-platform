<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('nexa:birthday-campaign')
    ->dailyAt('12:00')
    ->timezone('Africa/Casablanca');

Schedule::command('nexa:cross-selling')
    ->dailyAt('09:00')
    ->timezone('Africa/Casablanca');

Schedule::command('nexa:rendez-vous')
    ->hourly()
    ->timezone('Africa/Casablanca');
