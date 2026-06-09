<?php

namespace App\Console\Commands;

use App\Services\CrossSellingAutomationService;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class CrossSellingCampaignCommand extends Command
{
    protected $signature = 'nexa:cross-selling';

    protected $description = 'Queue cross-selling SMS for customers who bought linked products N days ago';

    public function handle(CrossSellingAutomationService $automation): int
    {
        $result = $automation->runDaily();

        IntegrationLogger::info('scheduler.cross_selling.completed', $result);

        $this->info(sprintf(
            'Cross-selling run complete: %d queued, %d skipped (%d rules).',
            $result['sms_queued'],
            $result['skipped'],
            $result['rules_processed'],
        ));

        return self::SUCCESS;
    }
}
