<?php

namespace App\Services;

use App\Models\Store;
use App\Models\CampaignDetail;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\MessageCampaign;
use App\Models\MessageCustomer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BirthdayService
{
    protected $campaignService;

    public function __construct()
    {
        $this->campaignService = new CampaignService;
    }
    public function SmsBirthday()
    {
        $smsDetails = [];

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'birthday')
            ->select('campaign_details.id', 'filter_id', DB::raw('count(*) as count'), 'campaigns.type')
            ->groupBy('filter_id')
            // ->having('count', '>', 1)
            ->get();
        // dd($CampaignDetails);
        foreach ($CampaignDetails as $CampaignDetail) {
            // dd($CampaignDetail['type']);
            $stores = CampaignDetail::where('filter_id', '=', $CampaignDetail['filter_id'])
                ->select('id', 'store_id')
                ->groupBy('store_id')
                ->get();
            $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
                ->first();

            foreach ($stores as $store) {
                $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                    ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                    ->select('messages.message', 'messages.date_start')
                    ->orderByDesc('date_start')
                    ->get();
                // return $message_campaigns;
                foreach ($messages as $message) {
                    // $customers = DB::table('invoices')
                    // ->join('customers', 'invoices.customer_id', '=', 'customers.id')
                    $customers = Customer::where([
                        ['customers.points', '<=', $filter['points']],
                        ['customers.store_id', '=', $store->store_id],
                    ])
                        ->where(function ($query) use ($filter) {
                            if ($filter['sexe'] == 0) {
                                $query->where('customers.sexe', '=', 0);
                            } elseif ($filter['sexe'] == 1) {
                                $query->where('customers.sexe', '=', 1);
                            } elseif ($filter['sexe'] == 2) {
                                $query->where('customers.sexe', '=', 0)
                                    ->orWhere('customers.sexe', '=', 1);
                            }
                        })
                        // ->whereBetween('invoices.invoice_date', [Carbon::now()->subMonth($filter['last_visit']), Carbon::now()])
                        // ->whereRaw('TIME(invoices.invoice_date) BETWEEN ? AND ?', [
                        //     Carbon::parse($filter['heure_start'])->format('H:i:s'),
                        //     Carbon::parse($filter['heure_end'])->format('H:i:s'),
                        // ])
                        ->whereNotNull('customers.birth')
                        ->whereMonth('customers.birth', '=', Carbon::now()->addDay(1)->format('m'))
                        ->whereDay('customers.birth', '=', Carbon::now()->addDay($message->date_start)->format('d'))
                        ->whereBetween('customers.birth', [Carbon::now()->subYears($filter['age_max']), Carbon::now()->subYears($filter['age_min'])])
                        ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
                        ->distinct('customers.id')
                        ->get();
                    $customerApi[] = $customers;
                    foreach ($customers as $customer) {
                        // $smsDetails[$customer->firstname]['date'] = Carbon::now()->addDay($message->date_start)->format('y-m-d');
                        $sender_id = Store::where('id', '=', $store->store_id)->value('name');
                        $smsDetails[$customer->firstname]['id'] = $customer->id;
                        $smsDetails[$customer->firstname]['phone'] = $customer->phone;
                        $smsDetails[$customer->firstname]['sender_id'] = $sender_id;


                        $code_red = 0;
                        // dd($customer->id);
                        $code = Code::where('customer_id', $customer->id)
                            ->whereNotNull('code_red')
                            ->first();
                        if (is_null($code)) {
                            $code_red = $this->campaignService->generateCode(6);
                            $code = Code::create([
                                'customer_id' => $customer->id,
                                'code_red' => $code_red,
                                'status' => 0,
                            ]);
                            $smsDetails[$customer->firstname]['code'] = $code_red;
                        } else {
                            $code_red = $code->code_red;
                            $smsDetails[$customer->firstname]['code'] = $code_red;
                        }

                        $messageCustomer = $this->campaignService->Message($message->message, $customer->firstname, $customer->lastname, $code_red);
                        $smsDetails[$customer->firstname]['messages'][] = $messageCustomer;

                        $msg = MessageCustomer::create([
                            'store_id' => $store->store_id,
                            'customer_id' => $customer->id,
                            'message' => $messageCustomer,
                            'type' => $CampaignDetail['type'],
                        ]);
                        $smsDetails[$customer->firstname]['type'] = $CampaignDetail['type'];
                    }
                }
            }
        }
        return $smsDetails;
        // foreach($smsDetails as $smsDetail){
        //     $this->sendSms($smsDetail['phone'],['messages']['0'],$smsDetail['sender_id']);
        // }
    }
    // public function sendSms($num, $texte, $emetteur) {
    //     $url = 'https://bulksms.ma/developer/sms/send';
    
    //     $fields_string = 'token=test_gshuPaZoeEG6ovbc8M79w0Qy&tel=' . $num . '&message=' . urlencode($texte). '&shortcode=' . $emetteur;
        
    //     $ch = curl_init();
    
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    
    //     $result = curl_exec($ch);
    
    //     curl_close($ch);
    //     return $result;
    // }
}
