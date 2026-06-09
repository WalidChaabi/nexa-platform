<?php

namespace App\Console\Commands;

use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class BirthdayCampaignCommand extends Command
{
    protected $signature = 'nexa:birthday-campaign';

    protected $description = 'Run birthday SMS campaign scheduler (dispatches jobs when campaigns module is wired)';

    public function handle(): int
    {
        IntegrationLogger::info('scheduler.birthday_campaign.tick');

        $this->info('Birthday campaign scheduler tick recorded.');

        return self::SUCCESS;
    }
}
