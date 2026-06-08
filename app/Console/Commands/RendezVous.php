<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CampaignService;
use App\Services\RendezVousService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RendezVous extends Command
{
    protected $signature = 'rendez:vous';

    protected $description = 'RendezVous description';
    
    protected $RendezVousService;

    public function __construct()
    {
        parent::__construct();
        $this->RendezVousService = new RendezVousService;
    }

    public function handle()
    {
        Log::info("Start of RendezVous campagne");
        $SmsRendezVous = $this->RendezVousService->SmsRendezVous();
        Log::info($SmsRendezVous);
        Log::info("End of RendezVous campagne");
    }
}
