<?php

namespace App\Console\Commands;

use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class RendezVousReminderCommand extends Command
{
    protected $signature = 'sherin:rendez-vous';

    protected $description = 'Run RDV reminder scheduler (dispatches jobs when calendar module is wired)';

    public function handle(): int
    {
        IntegrationLogger::info('scheduler.rendez_vous.tick');

        $this->info('RDV reminder scheduler tick recorded.');

        return self::SUCCESS;
    }
}
