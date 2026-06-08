<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Customer;
use App\models\Store;
use App\Services\CampaignService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ramadan extends Command
{

    protected $signature = 'ramadan:karem';


    protected $description = 'Command description';
    protected $campaignService;


    public function __construct()
    {
        $this->campaignService = new CampaignService;

        parent::__construct();
    }

    public function handle()
    {
        $customers = Customer::where('store_id', '=', '19')->where('points', '>=', '11')->select('phone')->get();
        $store= Store::where('id','=','19')->get();
        foreach($customers as $customer){
            $message="O'THAI TARGA vous souhaite un joyeux mois de Ramadan! Profitez aussi de nos offres promotionnelles.RDV sur notre page Instagram.Tél: 06 00 04 15 06";
            $data = $this->setData($store,$customer,$message);
            $this->campaignService->singleCampaign($data);
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
