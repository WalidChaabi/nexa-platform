<?php

namespace App\Console\Commands;

use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class CrossSellingCampaignCommand extends Command
{
    protected $signature = 'sherin:cross-selling';

    protected $description = 'Run cross-selling SMS scheduler (dispatches jobs when campaigns module is wired)';

    public function handle(): int
    {
        IntegrationLogger::info('scheduler.cross_selling.tick');

        $this->info('Cross-selling scheduler tick recorded.');

        return self::SUCCESS;
    }
}
