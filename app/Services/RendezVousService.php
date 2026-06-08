<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\MessageCampaign;
use App\Models\Rdv;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RendezVousService
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
    public function SmsRendezVous()
    {
        $smsDetails = [];

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'rdv')
            ->select('campaign_details.id', 'filter_id', 'store_id', 'campaign_details.product_id', DB::raw('count(*) as count'), 'campaigns.type')
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
                $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                    ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                    ->select('messages.message', 'messages.date_start', 'messages.type_envoi')
                    ->orderByDesc('date_start')
                    ->get();
                $store_id = $store->store_id;
                foreach ($messages as $message) {
                    $rdvs = Rdv::where('store_id', $store_id)
                        ->select('customer_id', 'start')
                        ->get();
                    foreach ($rdvs as $rdv) {
                        $rdv_id = $rdv->customer_id;
                        $now = Carbon::now()->addSecond($message->date_start)->format('y-m-d H:i:s');
                        $old = Carbon::now()->addSecond($message->date_start)->subMinutes(1)->format('y-m-d H:i:s');
                        $test = Customer::join('rdvs', 'rdvs.customer_id', '=', 'customers.id')
                            ->whereBetween('rdvs.start', [$old, $now])->select('customers.id')->first();
                        if ($message->type_envoi == 1) {
                            if (!is_null($test)) {
                                $customers =  $this->messageService->filterCustomer($filter, $store_id, $rdv_id,null);
                                foreach ($customers as $customer) {
                                    $smsDetails[] = $this->messageService->MessageDetails($store_id, $customer, $CampaignDetail, $message, null);
                                }
                            };
                        }
                    }
                }
            }
        }
        foreach ($smsDetails as $smsDetail) {
            $bulk[] = $this->sendSms($smsDetail['phone'], $smsDetail['messages']['0'], $smsDetail['sender_id']);
        }
        return [$smsDetails, $bulk];
    }
}
