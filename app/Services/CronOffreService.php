<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignHistorique;

class CronOffreService
{
 
    public function getCustomerByBirthday()
    {
        //foreach  day we will fetch all the users where the birthday is today + 4
        //after getting this users we will create an sms campagne
        $data = [];
        $stores =  Store::where('allow_camp', 1)->get();
        foreach ($stores as $store) {
            $customers['store_id'] = $store->id;
            $customers['start_date'] = date("Y-m-d", strtotime("+3 days"));
            $customers['customers']  = Customer::where('store_id', $store->id)
                ->whereMonth('birth', '=', Carbon::now()->addDay(1)->format('m'))
                ->whereDay('birth', '=', Carbon::now()->addDay(1)->format('d'))
                ->select(['id', 'phone'])->get();
            array_push($data, $customers);
        }
        //write a service for creating campaigns
        return $data;
    }
}
