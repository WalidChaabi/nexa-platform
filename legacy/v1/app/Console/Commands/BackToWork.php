<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use App\Services\CustomerService;
use App\Services\StoreService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BackToWork extends Command
{

    protected $signature = 'back:toWork';

    protected $description = 'Command description';
    protected $campaignService;
    protected $customerService;
    protected $storeService;
    protected $storesId ;

    public function __construct()
    {
        $this->storesId = [19];
        $this->customerService = new CustomerService;
        $this->campaignService = new CampaignService;
        $this->storeService = new StoreService;
        parent::__construct();
    }

    public function handle()
    {
        $stores =  $this->storeService->getStoresByIds($this->storesId);
        foreach($stores as $store){
                $customers =  $this->customerService->getCustomersLimitByStoreId($store->id);
                foreach($customers as $customer){
                    $messagePath = 'messages.'.$store->sender_id.'.AFTERAID';
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
