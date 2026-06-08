<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use Illuminate\Console\Command;
use App\Services\CronOffreService;
use App\Services\SmsService;
use App\Services\BirthdayService;
use Illuminate\Support\Facades\Log;

class BirthdayCampaign extends Command
{

    protected $birthayService;
    protected $cronOffreService;
    protected $campaignService;
    protected $smsService;

    public function __construct(
        BirthdayService $birthayService,
        CronOffreService $cronOffreService,
        CampaignService $campaignService,
        SmsService $smsService
    ) {
        $this->birthayService = $birthayService;
        $this->cronOffreService = $cronOffreService;
        $this->campaignService = $campaignService;
        $this->smsService = $smsService;
        parent::__construct();
    }

    protected $signature = 'birthday:campaign';

    protected $description = 'Command description';

    public function handle()
    {
        Log::info("Start of birthday campagne");
        // $customers = $this->cronOffreService->getCustomerByBirthday();
        $SmsBirthday = $this->birthayService->SmsBirthday();
        Log::info($SmsBirthday);
        // Log::info("Customers fetched");
        // Log::info("Historique Campaign will be created now");
        //  $this->campaignService->createCampaign($customers);
        // Log::info("Historique Campaign will be created now");
        Log::info("End of birthday campagne");
    }
}
