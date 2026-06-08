<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\MessageCampaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BienvenuService
{
    protected $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService;
    }
    public function SmsBienvenu($store_id,$customer_id)
    {
        $smsDetails = [];
        if(is_array($store_id)){
            $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', 'bienvenu')
            ->whereIn('campaign_details.store_id',$store_id)
            ->select('campaign_details.id','filter_id','store_id','campaign_details.product_id',
                DB::raw('count(*) as count'),'campaigns.type','nombre_client')
            ->groupBy('filter_id')
            ->get(); }
        else{
            $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where([['campaigns.type', 'bienvenu'],['campaign_details.store_id',$store_id]])
            ->select('campaign_details.id','filter_id','store_id','campaign_details.product_id',
                DB::raw('count(*) as count'),'campaigns.type','nombre_client')
            ->groupBy('filter_id')
            ->get(); 
        }
        foreach ($CampaignDetails as $CampaignDetail) {
            $type= $CampaignDetail['type'];
            $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
                ->first();
                $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                    ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                    ->select('messages.message', 'messages.date_start', 'message_campaigns.created_at')
                    ->orderByDesc('date_start')
                    ->get();

                foreach ($messages as $message) {
                    $customers =  $this->messageService->filterCustomer($filter, $store_id,$customer_id,$type);
                    foreach ($customers as $customer) {
                        $smsDetails[] = $this->messageService->MessageDetails($store_id, $customer, $CampaignDetail, $message,null);
                        Log::info($smsDetails[0]);
                        $bulkResult = json_decode($smsDetails[1][0], true); // Decode the JSON string
                        if ($bulkResult['success'] == 1) {
                            echo "SMS sent successfully";
                        } else {
                            echo "SMS sending failed";
                        }
                    }
                }
            // }
        }
        return $smsDetails;
        // foreach($smsDetails as $smsDetail){
        //     $this->sendSms($smsDetail['phone'],$smsDetail['messages']['0'],$smsDetail['sender_id']);
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
