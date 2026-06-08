<?php
namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\CampaignHistorique;
use App\Models\MessageCustomer;
use App\Models\SmsCrossSelling;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SmsService
{
    public static function getCountSms($storesId)
    {        
        $campaignsCount = [];

        $messages = MessageCustomer::where('messages_customers.payed', '=', '0')    
            ->when(is_array($storesId), function ($query) use ($storesId) {
                return $query->whereIn('messages_customers.store_id', $storesId);
            }, function ($query) use ($storesId) {
                return $query->where('messages_customers.store_id', $storesId);
            })
            ->select('messages_customers.type', DB::raw('count(*) as count'))
            ->groupBy('messages_customers.type')
            ->distinct()
            ->get();
            
        foreach($messages as $message){
            $campaignsCount[$message['type']] = [$message['type'],$message['count']];
        }

        return $campaignsCount;
        // $campaignDetails = CampaignDetail::select('campaigns.type', 'campaigns.name', 'campaign_details.*')
        //     ->join('campaigns', 'campaigns.id', '=', 'campaign_details.campaign_id')
        //     ->when(is_array($storesId), function ($query) use ($storesId) {
        //         return $query->whereIn('campaign_details.store_id', $storesId);
        //     }, function ($query) use ($storesId) {
        //         return $query->where('campaign_details.store_id', $storesId);
        //     })
        //     ->get();
        // $campaignsCount = [];
    
        // foreach ($campaignDetails as $campaignDetail) {

        //     $messages = DB::table('message_campaigns')
        //     ->join('messages', 'message_campaigns.message_id', '=', 'messages.id')
        //     ->where([
        //         ['message_campaigns.campaign_details_id', '=', $campaignDetail->id],
        //         ['messages.payed', '=', '0']
        //         ])
        //     ->select('message_id', DB::raw('count(*) as count'))
        //     ->groupBy('message_id')
        //     ->get();
        //     $campaignType = $campaignDetail->type;
        //     $campaignName = $campaignDetail->name;
        //     if (!isset($campaignsCount[$campaignType])) {
        //         $campaignsCount[$campaignType] = [$campaignName,0];
        //     }
        //     foreach ($messages as $message) {
        //         $messageCount = $message->count;    
        //         $campaignsCount[$campaignType][1] += $messageCount;
        //     }
        // }
        // $result = [];
        // foreach ($campaignsCount as $key => $value) {
        //     $result[$key] = $value;
        // }
        // // dd($result);
        // return $result;
    }

    public function sendSms($data)
    {
        $client = new BulkSmsClient();

        foreach ($data as $el) {
            $client->send('0663423733', 'hello ', 'Morino');
        }
    }
    
    
    
    public static function countSms($stores_id)
    {
        $sum = 0;
        $camps = CampaignHistorique::whereIn('store_id',$stores_id)->where('is_payed',0)->get();
        // $camps = CampaignDetail::whereIn('store_id',$stores_id)->where('payed',0)->get();
        foreach($camps->where('camp_id','!=',null) as $camp)
        {
        // if(!in_array($camp->status,[0,4]))
        // {
            //     $sum = $sum + $camp->status;
            // }
            if($camp->status==4){
                $sum = $sum + 1;
            }
        }
        $sum = $sum + $camps->where('camp_id','=',null)->count();
        return $sum;
            // dd($camps);
        
    }

    public function countSmsOfThisMonth($storesId){
        return $this->countSms($storesId);
    }

    public function getCrossSellingOfThisMonth($storesId){
        return SmsCrossSelling::whereIn('store_id',$storesId)->where('status',0)->count();
    }

    public function customResetCampaignCross($store,$number){
        return SmsCrossSelling::whereIn('store_id',[$store])->where('status',0)->limit($number)->update(['status' => 1]);
    }
}
