<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\MessageCampaign;
use App\Models\Rdv;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateRdvService
{
    protected $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService;
    }
    public function SmsUpdateRdv($rdv_id,$store_id, $customer_id,$start_date)
    {
        $smsDetails = [];
        $start_date = (Carbon::parse($start_date))->format('y-m-d H:i');

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'rdv')
            ->select(
                'campaign_details.id','filter_id','store_id','campaign_details.product_id',DB::raw('count(*) as count'),'campaigns.type'
            )
            ->groupBy('filter_id')
            ->get();
        foreach ($CampaignDetails as $CampaignDetail) {
            $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
                ->first();
            $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                ->select('messages.message', 'messages.date_start', 'messages.type_envoi')
                ->orderByDesc('date_start')
                ->get();

            foreach ($messages as $message) {
                // $rdvs = Rdv::where('store_id', $store_id)
                //     ->select('customer_id', 'start')
                //     ->get();
                // foreach ($rdvs as $rdv) {
                    if ($message->type_envoi == 2) {
                        $customers =  $this->messageService->filterCustomer($filter, $store_id, $CampaignDetail['type'], $rdv_id, null, $customer_id);
                        foreach($customers as $customer){
                            $smsDetails[] = $this->messageService->MessageDetails($store_id, $customer, $CampaignDetail, $message, $start_date);
                        }
                    }
                }
            // }
        }
        return $smsDetails;
    }
}
