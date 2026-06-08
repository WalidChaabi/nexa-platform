<?php


namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\MessageCampaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CrossSellingService
{
    protected $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService;
    }
    public function SmsCrossSelling()
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
                                $cross_id =$invoice->customer_id;
                                $customers =  $this->messageService->filterCustomer($filter, $store, $CampaignDetail['type'],null,$cross_id);
                                foreach ($customers as $customer) {
                                    $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                                }
                                $customer = Customer::where('id', $invoice->customer_id)->first();
                                $smsDetails[] = $this->messageService->MessageDetails($store, $customer, $CampaignDetail, $message);
                            }
                        }
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
