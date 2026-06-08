<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CrossSellingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NewCrossSelling extends Command
{
    protected $signature = 'cross:selling';

    protected $description = 'CrossSelling description';
    
    protected $crossSellingService;

    public function __construct()
    {
        parent::__construct();
        $this->crossSellingService = new CrossSellingService;
    }

    public function handle()
    {
        Log::info("Start of CrossSelling campagne");
        $SmsCrossSelling = $this->crossSellingService->SmsCrossSelling();
        Log::info($SmsCrossSelling);
        Log::info("End of CrossSelling campagne");
    }
}
