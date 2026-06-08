<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CampaignDetail;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\MessageCampaign;
use App\Models\MessageCustomer;
use App\Models\Rdv;
use App\models\Store;
use App\Services\CampaignService;
use App\Services\MessageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CrossSellingController extends Controller
{
    protected $campaignService;
    protected $messageService;

    public function __construct()
    {
        $this->campaignService =  new CampaignService;
        $this->messageService =  new MessageService;
    }
    public function Bienvenu(Request $request)
    {
        $smsDetails = [];
        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'bienvenu')
            ->select('campaign_details.id','filter_id','store_id','campaign_details.product_id',
                DB::raw('count(*) as count'),'campaigns.type','nombre_client'
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
                $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
                    ->where('campaign_detail_id', '=',  $CampaignDetail->id)
                    ->select('messages.message', 'messages.date_start', 'message_campaigns.created_at')
                    ->orderByDesc('date_start')
                    ->get();

                foreach ($messages as $message) {
                    $customers =  $this->messageService->filterCustomer($filter, $store, $CampaignDetail['type'],[]);
                    foreach ($customers as $customer) {
                        return $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                        $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                    }
                }
            }
        }
        return $smsDetails;
    }
    public function Unique(Request $request)
    {
        $smsDetails = [];
        $count = 0;

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'unique')
            ->select(
                'campaign_details.id',
                'filter_id',
                'store_id',
                'campaign_details.product_id',
                DB::raw('count(*) as count'),
                'campaigns.type',
                'nombre_client'
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
                        $customers =  $this->messageService->filterCustomer($filter, $store, $CampaignDetail['type'],[]);
                        foreach ($customers as $customer) {
                            $count++;
                            if ($count < $CampaignDetail->nombre_client) {
                                $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                            }
                        }
                    }
                }
                $store->update(['active' => 0]);
            }
        }
        return $smsDetails;
    }
    public function RendezVous(Request $request)
    {
        $smsDetails = [];
        $msgs = [];

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'rdv')
            ->select('campaign_details.id', 'filter_id', 'store_id', 'campaign_details.product_id', 
                DB::raw('count(*) as count'), 'campaigns.type')
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
                    ->select('messages.message', 'messages.date_start')
                    ->orderByDesc('date_start')
                    ->get();
                $msgs[] = $messages;

                foreach ($messages as $message) {
                    $rdvs = Rdv::where('store_id', $store->store_id)
                        ->select('customer_id', 'start')
                        ->get();
                    foreach ($rdvs as $rdv) {
                        $now = Carbon::now()->format('y-m-d H:i:s');
                        $old = Carbon::now()->subMinutes(1)->format('y-m-d H:i:s');
                        $id = $rdv->customer_id;
                        if ($CampaignDetail['type'] == 'rdv') {
                            $test = Customer::join('rdvs','rdvs.customer_id','=','customers.id')
                            ->whereBetween('rdvs.start', [$old, $now])->select('customers.id')->first();
                            // dd($message);
                            if(!is_null($test)){
                                $customers =  $this->messageService->filterCustomer($filter, $store, $CampaignDetail['type'],$rdv);
                            };
                        } 
                        // $newDate = Carbon::parse($rdv->start)
                        //     ->addMinutes($message->date_start)
                        //     ->format('H:i');

                        // if ($newDate === Carbon::now()->format('H:i')) {
                            $customers =  $this->messageService->filterCustomer($filter, $store, $CampaignDetail['type'],$rdv);
                            foreach ($customers as $customer) {
                                // dd($customer);
                                $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                            }
                        // }
                    }
                }
            }
        }
        return $msgs;
        // return $smsDetails;
    }
    public function CrossSelling(Request $request)
    {
        $smsDetails = [];

        $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.type', '=', 'cross-selling')
            ->select('campaign_details.id', 'filter_id', 'campaign_details.product_id', DB::raw('count(*) as count'), 'campaigns.type')
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
                    ->select('messages.message', 'messages.date_start')
                    ->orderByDesc('date_start')
                    ->get();
                foreach ($messages as $message) {
                    $InvoiceProducts = InvoiceProduct::join('invoices', 'invoice_products.invoice_id', '=', 'invoices.id')
                        ->where('invoice_products.product_id', $CampaignDetail->product_id)
                        ->select('invoice_id')
                        ->get();
                    foreach ($InvoiceProducts as $InvoiceProduct) {
                        $invoices = Invoice::where('id', $InvoiceProduct->invoice_id)
                            ->select('customer_id', 'invoice_date')
                            ->get();
                        foreach ($invoices as $invoice) {
                            $newDate = Carbon::parse($invoice->invoice_date)
                                ->addDays($message->date_start)
                                ->format('Y-m-d');

                            if ($newDate === Carbon::now()->format('Y-m-d')) {
                                $customer = Customer::where('id', $invoice->customer_id)->first();
                                $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                            }
                        }
                    }
                }
            }
        }
        return $smsDetails;
    }
}
