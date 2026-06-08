<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LaunchCampaign extends Command
{



    protected $signature = 'launch:campaign';

    protected $description = 'Command description';

    protected $campaignService;
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
        parent::__construct();
    }

    public function handle()
    {
        Log::info('********************************************************************');

         $this->campaignService->getLastDayFinishedCampaigns();//at this line we set all the last campigns to finished 4
        Log::info('Set last campaigns to finshed status 4') ;
        
        $dataLastDay = $this->campaignService->getLastDayCampaigns();
       
        //at this line we set all the last campaigns to 3
        if(count($dataLastDay)>0)
        {
              $this->campaignService->launchLastDayCampaigns($dataLastDay);//at this line we set all the last campaigns to 3
              Log::info('from seconde day to third day ') ;
        }
      
        $datasecondeDay = $this->campaignService->getSecondeDayCampaigns();
       
        //at this line we set all the seconde day campaigns to 1
        if(count($datasecondeDay)>0)
        {
        $this->campaignService->launchSecondeDayCampaigns($datasecondeDay);//at this line we set all the last campaigns to 3
        Log::info('from first  day to seconde day ') ;
        }
        $dataFirstDay = $this->campaignService->getFirstDayCampaigns();//at this line we set all the seconde day campaigns to 1
      
        if(count( $dataFirstDay)>0)
        {
             $this->campaignService->launchFirstDayCampaigns($dataFirstDay);//at this line we set all the last campaigns to 3
             Log::info('from initial  day to first day  ') ;
        }
    }
}
