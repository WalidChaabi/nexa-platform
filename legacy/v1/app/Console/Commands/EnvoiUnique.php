<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CampaignService;
use App\Services\UniqueService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnvoiUnique extends Command
{
    protected $signature = 'envoi:unique';

    protected $description = 'EnvoiUnique description';
    
    protected $UniqueService;

    public function __construct()
    {
        parent::__construct();
        $this->UniqueService = new UniqueService;
    }

    public function handle()
    {
        Log::info("Start of EnvoiUnique campagne");
        $UniqueService = $this->UniqueService->SmsUnique();
        Log::info($UniqueService);
        Log::info("End of EnvoiUnique campagne");
    }
}
