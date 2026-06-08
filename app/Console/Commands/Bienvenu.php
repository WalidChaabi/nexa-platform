<?php

namespace App\Console\Commands;

use App\Services\BienvenuService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Bienvenu extends Command
{

    protected $bienvenuService;

    public function __construct(
        BienvenuService $bienvenuService
    ) {
        $this->bienvenuService = $bienvenuService;
        parent::__construct();
    }

    protected $signature = 'bienvenu:campaign';

    protected $description = 'Command description';

    public function handle()
    {
        Log::info("Start of birthday campagne");
        $SmsBirthday = $this->bienvenuService->SmsBienvenu();
        Log::info($SmsBirthday);
        Log::info("End of birthday campagne");
    }
}
