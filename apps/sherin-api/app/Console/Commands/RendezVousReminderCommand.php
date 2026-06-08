<?php

namespace App\Console\Commands;

use App\Services\RdvReminderService;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class RendezVousReminderCommand extends Command
{
    protected $signature = 'sherin:rendez-vous';

    protected $description = 'Send SMS reminders for appointments starting within 30 minutes';

    public function handle(RdvReminderService $reminders): int
    {
        $result = $reminders->sendUpcomingReminders();

        IntegrationLogger::info('scheduler.rendez_vous.completed', $result);

        $this->info(sprintf(
            'RDV reminders: %d queued, %d skipped (%d checked).',
            $result['queued'],
            $result['skipped'],
            $result['checked'],
        ));

        return self::SUCCESS;
    }
}
