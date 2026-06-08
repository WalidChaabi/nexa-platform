<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\MessageCampaign;
use Illuminate\Support\Facades\DB;

class RdvInstantaneService
{
    protected $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService;
    }
    
    // public function sendSms($num, $texte, $emetteur) {
    //     $url = 'https://bulksms.ma/developer/sms/send';
    
    //     $fields_string = 'token=a4B2EZpCjP72JwA33JRTwYQ1e&tel=' . $num . '&message=' . urlencode($texte). '&shortcode=' . $emetteur;
        
    //     $ch = curl_init();
    
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    
    //     $result = curl_exec($ch);
    
    //     curl_close($ch);
    //     return $result;
    // }
    
    public function SmsRdvInstantane($rdv_id,$store_id, $customer_id,$start_date)
    {
        $smsDetails=[];
        $bulk = [];

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where([['campaigns.type', '=', 'rdv'],['campaign_details.store_id','=',$store_id]])
            ->select(
                'campaign_details.id','filter_id','store_id','campaign_details.product_id',DB::raw('count(*) as count'),'campaigns.type'
            )
            ->groupBy('filter_id')
            ->get();
        foreach ($CampaignDetails as $CampaignDetail) {
            $type=$CampaignDetail['type'];

            $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
                ->first();
            $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                ->select('messages.message', 'messages.date_start', 'messages.type_envoi')
                ->orderByDesc('date_start')
                ->get();
            foreach ($messages as $message) {
                if ($message->type_envoi == 1) {
                    return $customers =  $this->messageService->filterCustomer($filter, $store_id, $customer_id,$type);
                    // return $customers;
                    foreach($customers as $customer){
                        $smsDetails = $this->messageService->MessageDetails($store_id, $customer, $CampaignDetail, $message ,$start_date);
                    }
                }
            }
        }
        // foreach($smsDetails as $smsDetail){
        //   $bulk[]= $this->sendSms($smsDetail['phone'],$smsDetail['messages']['0'],$smsDetail['sender_id']);
        // }
        // return [$smsDetails,$bulk];
        return $smsDetails;
    }
}
