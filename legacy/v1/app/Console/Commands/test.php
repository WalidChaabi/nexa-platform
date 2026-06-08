<?php

namespace App\Console\Commands;

use App\Models\Customer;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

class test extends Command
{
    protected $signature = 'command:name';

    protected $description = 'Command description';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    { 
        // Customer::where('created_at', '<', Carbon::now()->subWeek())->delete();
        Log::info('This is a test.');
    }
}
