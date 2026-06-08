<?php


namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\MessageCampaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UniqueService
{
    protected $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService;
    }
    public function sendSms($num, $texte, $emetteur)
    {
        return (new BulkSmsClient())->send($num, $texte, $emetteur);
    }
    public function SmsUnique()
    {
        $smsDetails = [];
        $count = 0;

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'unique')
            ->select('campaign_details.id','filter_id','store_id','campaign_details.product_id',DB::raw('count(*) as count'),'campaigns.type','nombre_client'
            )
            ->groupBy('filter_id')
            ->get();
        foreach ($CampaignDetails as $CampaignDetail) {
            $stores = CampaignDetail::where('filter_id', '=', $CampaignDetail['filter_id'])
                ->select('id', 'store_id')
                ->groupBy('store_id')
                ->get();
            $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
                ->first();
            foreach ($stores as $store) {
                $store_id = $store->store_id;
                $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                    ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                    ->select('messages.message', 'messages.date_start', 'message_campaigns.created_at')
                    ->orderByDesc('date_start')
                    ->get();

                foreach ($messages as $message) {
                    $newDate = Carbon::parse($message->created_at)
                        ->addDays($message->date_start)
                        ->format('Y-m-d');
                    // dd($newDate);
                    if ($newDate === Carbon::now()->format('Y-m-d')) {
                        $customers =  $this->messageService->filterCustomer($filter, $store_id,null);
                        foreach ($customers as $customer) {
                            $count++;
                            if ($count < $CampaignDetail->nombre_client) {
                                $smsDetails = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message,null);
                            }
                        }
                    }
                }
                $store->update(['active' => 0]);
            }
        }
        foreach($smsDetails as $smsDetail){
          $bulk[]= $this->sendSms($smsDetail['phone'],$smsDetail['messages']['0'],$smsDetail['sender_id']);
        }
        return [$smsDetails,$bulk];
    }
}
