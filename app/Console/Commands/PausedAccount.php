<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use App\Services\StoreService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class PausedAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paused:account';
    protected $storeService;
    protected $userService;
    protected $smsService;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService;
        $this->storeService = new StoreService;
        $this->smsService = new SmsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         $owners = $this->userService->getOwners();
       
         foreach($owners as $owner){
                $stores = $owner->stores;
                $storesId = $stores->pluck('id')->toArray();
                $countSms = $this->smsService->countSmsOfThisMonth($storesId);
                $countSmsCrossSelling = $this->smsService->getCrossSellingOfThisMonth($storesId);
                $day = Carbon::now()->format('d');
                if($countSms * config('constant.sms_price') + $countSmsCrossSelling * config('constant.sms_cross_selling')  > config('constant.balance') && $day === "11" ){
                    $this->userService->desactivateAccounts($storesId);
                    $this->userService->desactivateOwnerAccount($owner->id);
                }
         }
    }
}
