<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        'App\Console\Commands\BirthdayCampaign',
        'App\Console\Commands\CrosSelling',
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('bienvenu:campaign')->everyMinute();
        // $schedule->command('cross:selling')->everyMinute();
        // $schedule->command('rendez:vous')->everyMinute();

        // $schedule->command('birthday:campaign')->dailyAt('12:11');
        // $schedule->command('command:name')->dailyAt('12:12')->timezone('Africa/Casablanca');
        
        // $schedule->command('envoi:unique')->dailyAt('13:18')->timezone('Africa/Casablanca');
        // $schedule->command('cross-selling:campaign')->daily();

        // $schedule->command('command:name')->dailyAt('10:10');
        // $schedule->command('backup:clean')->twiceDaily();
        // $schedule->command('backup:run --only-db')->twiceDaily();

    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
