<?php

namespace App\Console\Commands;

use App\Services\BirthdayCampaignAutomationService;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Console\Command;

class BirthdayCampaignCommand extends Command
{
    protected $signature = 'nexa:birthday-campaign';

    protected $description = 'Queue birthday SMS billing rows for customers with matching birth dates';

    public function handle(BirthdayCampaignAutomationService $automation): int
    {
        $result = $automation->runDaily();

        IntegrationLogger::info('scheduler.birthday_campaign.completed', $result);

        $this->info(sprintf(
            'Birthday campaign complete: %d messages created, %d skipped (%d campaigns).',
            $result['messages_created'],
            $result['skipped'],
            $result['campaigns_processed'],
        ));

        return self::SUCCESS;
    }
}
