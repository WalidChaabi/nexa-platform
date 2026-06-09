<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AuditsAdminPaymentOverrides;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\MessageCustomer;
use App\Models\Product;
use App\models\Store;
use Carbon\Carbon;
use App\Services\CampaignService;
use App\Services\CheckoutService;
use App\Services\HistoriquePaiementService;
use App\Services\SmsService;
use App\Services\StoreService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;


class CampaignController extends Controller
{
    use AuditsAdminPaymentOverrides;

    protected $campaignService;
    protected $storeService;
    protected $smsService;
    protected $ownerService;
    protected $checkoutService;
    protected $historiquePaiementService;
    public function __construct()
    {
        $this->campaignService = new CampaignService;
        $this->storeService = new StoreService;
        $this->smsService = new SmsService;
        $this->ownerService = new UserService;
        $this->checkoutService = new CheckoutService;
        $this->historiquePaiementService = new HistoriquePaiementService;
    }

    public function index()
    {
        $stores = $this->storeService->getAll();
        $owners = $this->ownerService->getOwners();
        return view('admin.campaign.list', compact('stores', 'owners'));
    }

    public function productStore(Request $request)
    {
        $products = Product::where('store_id', '=', $request->store_id)->get();
        return response()->json($products);
    }
    public function create()
    {
        $stores = $this->storeService->getAll();
        $owners = $this->ownerService->getOwners();
        $campaigns = Campaign::where('type', '!=', 'credit-sup')->orderBy('id', 'ASC')->get();
        $campaign_details = $this->campaignService->getAdminCampaignDetail();

        return view('admin.campaign.create', compact('stores', 'owners', 'campaigns', 'campaign_details'));
    }
    public function test()
    {
        $stores = $this->storeService->getAll();
        $owners = $this->ownerService->getOwners();
        $campaigns = $this->campaignService->getall();
        $campaign_details = $this->campaignService->getAdminCampaignDetail();

        return view('admin.campaign.total', compact('stores', 'owners', 'campaigns', 'campaign_details'));
    }
    public function details()
    {
        $campaign_details = $this->campaignService->getAdminCampaignDetail();

        return view('admin.campaign.details', compact('campaign_details'));
    }

    // public function getCampaignsByStore(Request $request)
    // {
    //     switch ($request->campaignType) {
    //         case '0':
    //             return $this->smsService->countSmsOfThisMonth([$request->store]);
    //             break;
    //         case '1':
    //             return $this->smsService->getCrossSellingOfThisMonth([$request->store]);
    //             break;
    //         default:
    //             # code...
    //             break;
    //     }
    //     return $request->all();
    // }

    public function getCountSms(Request $request)
    {
        $campaignsCount = [];

        $messages = MessageCustomer::where([
            ['messages_customers.store_id', $request->store_id],
            ['messages_customers.payed', '=', '0']
        ])
            ->select('messages_customers.type', DB::raw('count(*) as count'))
            ->groupBy('messages_customers.type')
            ->get();

        foreach ($messages as $message) {
            $campaignsCount[$message['type']] = [$message['type'], $message['count']];
        }

        return $campaignsCount;
        // // return $messages;
        // $campaignDetails = CampaignDetail::select('campaigns.type', 'campaigns.name', 'campaign_details.*')
        //     ->join('campaigns', 'campaigns.id', '=', 'campaign_details.campaign_id')
        //     ->where('campaign_details.store_id', $request->store_id)
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
        // return $result;
    }

    public function activateCampaigns(Request $request)
    {
        return $request->state == 'true' 
            ? CampaignDetail::where('id', $request->id)->update(['active' => 1]) 
            : CampaignDetail::where('id', $request->id)->update(['active' => 0]);
    }
    public function activateFilters(Request $request)
    {
        if($request->state == 'true'){
            $filter_id = CampaignDetail::where('id', $request->id)->value('filter_id');
            return Filter::where('id', $filter_id)->update(['active' => 1]);
        }
        else{
            $filter_id = CampaignDetail::where('id', $request->id)->value('filter_id');
            return Filter::where('id', $filter_id)->update(['active' => 0]);    
        }
    }  
    public function resetCampaigns(Request $request)
    {
        $this->authorizeAdminPaymentOverride($request, 'reset_campaigns');

        $request->merge([
            'store_id' => $request->input('store_id', $request->input('store')),
            'credit1' => $request->input('credit1', $request->input('debit')),
            'name' => $request->input('name', (string) $request->input('campaignType') === '1' ? 'cross-selling' : 'birthday'),
        ]);

        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
            'credit1' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
        ]);

        // dd($request);
        // $campaigns = [];
        // foreach ($request->all() as $key => $value) {
        //     if (Str::contains($key, 'credit')) {
        //         $substring = substr($key, 0, strpos($key, "credit"));
        //         if (!isset($campaigns[$substring])) {
        //             $campaigns[$substring] = [
        //                 "name" => $substring,
        //                 "count" => 0
        //             ];
        //         }
        //         $campaigns[$substring]["count"] += $value;
        //     }
        // }
        // $result = array_values($campaigns);
        // foreach($result as $_){
        //     $this->campaignService->customResetCampaign($request->store, $_['count'], $_['name']);
        // }
        $this->campaignService->customResetCampaign($request->store_id, $request->credit1, $request->name);
        $ownerId = Store::where('id', $request->store_id)->value('user_id');
        $key = 'detail_invoice' . $ownerId;
        $cmd = invoiceIdGenerator($ownerId);
        $this->setTest($request->credit1, $request->name, $cmd, $ownerId);
        $this->historiquePaiementService->create(session()->get($key), $cmd, 1);
        return redirect()->back()->with('store_id', $request->store_id);
    }

    public function payAll(Request $request)
    {
        $this->authorizeAdminPaymentOverride($request, 'pay_all');

        $request->validate([
            'ownerId' => 'required|integer|exists:users,id',
        ]);

        $stores_id = Store::where('user_id', $request->ownerId)->pluck('id')->toArray();
        $details = $this->campaignService->payAllCampaign($stores_id);
        $key = 'detail_invoice' . $request->ownerId;
        $cmd = invoiceIdGenerator($request->ownerId);
        $this->setPayall($details, $cmd, $request->ownerId);
        $this->historiquePaiementService->create(session()->get($key), $cmd, 1);

        return redirect(route('admin.historique-paiments'));
    }
    // public function payAll(Request $request)
    // {

    //     $key = 'detail_invoice' . $request->ownerId;
    //     $cmd = invoiceIdGenerator($request->ownerId);
    //     $storesId =  $this->storeService->getStoresByOwner($request->ownerId)->pluck('id')->toArray();
    //     $emails = EmailCampaign::whereIn('store_id', $storesId)->where('status', '=', 0)->get()->count();
    //     $countSmsBirthdayAndWelcome    = $this->smsService->countSmsOfThisMonth($storesId);
    //     $countSmsCrossSelling          = $this->smsService->getCrossSellingOfThisMonth($storesId);
    //     $this->setSession($emails, $countSmsBirthdayAndWelcome, $countSmsCrossSelling, $cmd, $request->ownerId);
    //     dd(session()->get($key));
    //     Event::dispatch(new DetailInvoice(session()->get($key), $cmd, 1));
    //     Event::dispatch(new PaiedSms($storesId));
    //     Event::dispatch(new PaidEmail($storesId));
    //     Event::dispatch(new ActivateAccount($request->ownerId, $storesId));
    //     return redirect(route('admin.historique-paiments'));
    // }

    public function credit(Request $request)
    {
        // dd($request->store < 1);
        if ($request->store < 1) {
            $errorMessage = "Veuillez sélectionner un magasin s'il vous plait.";
            return back()->withErrors($errorMessage);
        }
        $this->campaignService->AddCredit($request->store, $request->credit);
        return back();
    }

    // public function singleCampaign(Request $request)
    // {
    //     $this->validate($request, [
    //         'phone' => 'required|regex:/(212)[0-9]{9}/',
    //         'message' => 'required|string|max:160',
    //     ], [
    //         'phone.string' => 'Le Champ Phone est requis, verifiez le format du N° Téléphone',
    //         'phone.max' => 'Le Champ Phone est requis, verifiez le format du N° Téléphone',
    //         'message.required' => 'Le Champ message est requis',
    //     ]);
    //     $data = [];
    //     $data['camp_id']  = 6;
    //     $data['store_id'] = auth()->user()->stores->first()->id; //hard coded for now
    //     $data['phone']    = str_replace(' ', '', $request->phone);
    //     $data['message']  = $request->message;
    //     $data['start_date'] = Carbon::now();
    //     $data['sender']     =  auth()->user()->stores->first()->sender_id;

        // $this->campaignService->singleCampaign($data);
    //     if (auth()->user()->is_admin == 1)
    //         return  redirect(route('index'));
    //     return redirect('dashboard/owner');
    // }


    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'model' => 'required|not_in:0',
    //         'campaign_name' => 'required',
    //         'send_nature' => 'required|not_in:-1',
    //         'message' => 'required',
    //     ]);

    //     $campaign = Campaign::create([
    //         'template_id' => $request->model,
    //         'campaign_name' => $request->campaign_name,
    //         'nature_sending' => $request->send_nature,
    //         'message' => $request->message,
    //         'create_date' => Carbon::now(),
    //     ]);

    //     return redirect()->to('/admin/campaigns');
    // }

    public function show($id)
    {
        //
    }

    // public function getCampaignMessage(Request $request)
    // {
    //     $this->validate($request, [
    //         'campagn_id' => 'required|not_in:-1'
    //     ]);
    //     return Campaign::find($request->campagn_id)->message;
    // }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    private function setPayAll($details, $cmd, $ownerId)
    {
        $smsNormalPrice = config('constant.sms_price');
        $countSmsCrossSelling = 0;
        $countSms = 0;
        foreach ($details as $detail) {
            $countSmsCrossSelling += ($detail['0'] === 'cross-selling') ? $detail['1'] : 0;
            $countSms += ($detail['0'] !== 'cross-selling') ? $detail['1'] : 0;
        }

        $services = json_encode([
            'email_price' => config('constant.email_price'),
            'sms_normal' => $countSms,
            'sms_normale_price' => $smsNormalPrice,
            'sms_advanced' => $countSmsCrossSelling,
            'sms_advanced_price' => config('constant.sms_cross_selling')
        ]);
        $total_ht = $countSmsCrossSelling * config('constant.sms_cross_selling') + $countSms * $smsNormalPrice;
        $total_ttc = $total_ht * (1 + config('constant.nexa_tva'));
        $historiqueInvoice = [
            'services' => $services,
            'cmd' => $cmd,
            'tva' => config('constant.nexa_tva'),
            'owner_id' =>  $ownerId,
            'total_ht' => $total_ht,
            'total_ttc' =>  $total_ttc,
        ];
        $key = 'detail_invoice' . $ownerId;
        session()->put($key, $historiqueInvoice);
    }

    private function setTest($countSms, $type, $cmd, $ownerId)
    {
        $smsNormalPrice = config('constant.sms_price');
        $countSmsCrossSelling = 0;
        if (is_array($type)) {
            foreach ($type as $t) {
                $countSmsCrossSelling += ($t === 'cross-selling') ? $countSms : 0;
            }
        } else {
            $countSmsCrossSelling = ($type === 'cross-selling') ? $countSms : 0;
        }

        $services = json_encode([
            'email_price' => config('constant.email_price'),
            'sms_normal' => $countSms,
            'sms_normale_price' => $smsNormalPrice,
            'sms_advanced' => $countSmsCrossSelling,
            'sms_advanced_price' => config('constant.sms_cross_selling')
        ]);
        $total_ht = ($type === 'cross-selling') ? $countSmsCrossSelling * config('constant.sms_cross_selling') : $countSms * $smsNormalPrice;
        $total_ttc = $total_ht * (1 + config('constant.nexa_tva'));
        $historiqueInvoice = [
            'services' => $services,
            'cmd' => $cmd,
            'tva' => config('constant.nexa_tva'),
            'owner_id' =>  $ownerId,
            'total_ht' => $total_ht,
            'total_ttc' =>  $total_ttc,
        ];
        $key = 'detail_invoice' . $ownerId;
        session()->put($key, $historiqueInvoice);
    }
    // private function setSession($emails, $countSmsBirthdayAndWelcome, $countSmsCrossSelling, $cmd, $ownerId)
    // {
    //     $smsNormalPrice = config('constant.sms_price');
    //     $services = json_encode([
    //         'emails' => $emails,
    //         'email_price' => config('constant.email_price'),
    //         'sms_normal' => $countSmsBirthdayAndWelcome,
    //         'sms_normale_price' => $smsNormalPrice,
    //         'sms_advanced' => $countSmsCrossSelling,
    //         'sms_advanced_price' => config('constant.sms_cross_selling')
    //     ]);
    //     $total_ht = $emails * config('constant.email_price') + $countSmsBirthdayAndWelcome *  $smsNormalPrice + $countSmsCrossSelling * config('constant.sms_cross_selling');
    //     $total_ttc = $total_ht * (1 + config('constant.nexa_tva'));
    //     $historiqueInvoice = [
    //         'services' => $services,
    //         'cmd' => $cmd,
    //         'tva' => config('constant.nexa_tva'),
    //         'owner_id' =>  auth()->user()->id,
    //         'total_ht' => $total_ht,
    //         'total_ttc' =>  $total_ttc,
    //     ];
    //     $key = 'detail_invoice' . $ownerId;
    //     session()->put($key, $historiqueInvoice);
    // }
}
