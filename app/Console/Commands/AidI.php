<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use App\Services\CustomerService;
use App\Services\StoreService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AidI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aid:lkbir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $campaignService;
    protected $customerService;
    protected $storeService;
    protected $storesId ;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->storesId = [6,19,18];
        $this->customerService = new CustomerService;
        $this->campaignService = new CampaignService;
        $this->storeService = new StoreService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stores =  $this->storeService->getStoresByIds($this->storesId);
        foreach($stores as $store){
                $customers =  $this->customerService->getCustomersLimitByStoreId($store->id);
                foreach($customers as $customer){
                    $messagePath = 'messages.'.$store->sender_id.'.AID';
                    $message = config($messagePath);
                    $data = $this->setData($store,$customer,$message);
                    $this->campaignService->singleCampaign($data);
                }
        }
    }

    private function setData($store,$customer,$message){
        return [
            'camp_id'     => null,
            'store_id'    => $store->id,
            'customer_id' => $customer->id, //nullable
            'phone'       => $customer->phone,
            'message'     => $message,
            'sender'      => $store->sender_id,
            'start_date'  => Carbon::now(),
        ];
    }
}
