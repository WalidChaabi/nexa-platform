<?php

namespace App\Http\Controllers\Web;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\MessageCampaign;
use App\Services\CampaignService;
use App\Services\StoreService;
use App\Services\UserService;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CampaignDetailController extends Controller
{
    protected $campaignService;
    protected $storeService;
    protected $ownerService;
    public function __construct()
    {
        $this->campaignService = new CampaignService;
        $this->storeService = new StoreService;
        $this->ownerService = new UserService;
    }

    public function index()
    {
    }


    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
        ]);

        $storeId = (int) $request->store_id;

        $result = (object) [
            'maxPoints' => Customer::where('store_id', $storeId)->max('points'),
            'ageMin' => Customer::where('store_id', $storeId)->min('birth'),
            'ageMax' => Customer::where('store_id', $storeId)->max('birth'),
            'last_visit' => Invoice::query()
                ->join('customers', 'invoices.customer_id', '=', 'customers.id')
                ->where('customers.store_id', $storeId)
                ->max('invoices.invoice_date'),
        ];
        // dd(isset($request['points']) && $request['points'] !== null ? $request['points'] : ($result->maxPoints ?? 0));
        $request['points'] = isset($request['points']) && $request['points'] !== null ? $request['points'] : ($result->maxPoints ?? 0);
        $request['age_max'] ??= $result->ageMax;
        $request['age_min'] ??= $result->ageMin;
        $request['sexe'] ??= 2;
        $request['heure_start'] ??= '00:00:00';
        $request['heure_end'] ??= '23:59:59';
        $request['last_visit'] ??= $result->last_visit
            ? Carbon::parse($result->last_visit)->diffInMonths(Carbon::now()) + 1
            : 0;
        // $request['last_visit'] = $request['last_visit'] ? Carbon::parse($result->last_visit)->diffInMonths(Carbon::now()) + 1 :0;


        $filter = $request->validate([
            'points'      => '',
            'age_max'     => '',
            'age_min'     => '',
            'last_visit'  => '',
            'sexe'        => '',
            'heure_start' => '',
            'heure_end'   => ''
        ]);
        $filter = Filter::create($filter);
        // dd($filter);
        // dd(Carbon::parse($filter->heure_start)->format('Y-m-d H:i:s'));
        $filter_id = $filter->id;

        $request['reduction'] = $request['reduction'] ? $request['reduction'] : 0;
        $request['filter_id'] = $filter_id;
        $request['product_id'] = isset($request['product_id']) ? $request['product_id'] : 0;
        $request['campaign_name'] = isset($request['campaign_name']) ? $request['campaign_name'] : NULL;
        $request['active'] = 1;
        // dd("ok");
        $request->validate([
            'campaign_name' => 'string|nullable',
            'campaign_id' => 'required|exists:campaigns,id',
            'filter_id' => 'required|exists:filters,id',
            'store_id' => 'required|exists:stores,id',
            // 'customer_id' => 'required|exists:customers,id',
            // 'code_id' => 'required|exists:codes,id',
            // 'product_id' => 'required|exists:products,id',
            'reduction' => 'required',
            'nombre_client' => 'nullable|integer',
            'active' => 'required',
        ]);

        $campaign = CampaignDetail::create($request->only([
            'campaign_name', 'campaign_id', 'filter_id', 'store_id', 'product_id',
            'reduction', 'active', 'nombre_client',
        ]));
        // dd($campaign);

        $messages = json_decode($request->messages);
        $type = Campaign::where('id', $request['campaign_id'])->value('type');
        $count = 0;
        foreach ($messages as $msg) {
            
            $req = new Request(get_object_vars($msg));
            $req->message = str_replace("\n", " ", $req->message);
            $time_str = $req->date_start;
            if(is_string($time_str) && $type == 'rdv'){
                $datetime = DateTime::createFromFormat("H:i", $time_str);
                $req['date_start'] = $datetime->getTimestamp() - strtotime('today');
            }
            $validator = Validator::make($req->all(),[
                'message' => 'required',
                'date_start' => 'integer|nullable ',
                'type_envoi' => 'integer |nullable',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
            if ($type == 'rdv') {
                $count++;
                if ($count <= 1) {  
                    $req['type_envoi'] = 1;
                    $message = Message::create($req->only(['message', 'date_start', 'type_envoi']));
                }
                else if ($count <= 2) {  
                    $req['type_envoi'] = 2;
                    $message = Message::create($req->only(['message', 'date_start', 'type_envoi']));
                }
                else{
                    $req['type_envoi'] = 0;
                    $message = Message::create($req->only(['message', 'date_start', 'type_envoi']));
                }
            }else{
                $message = Message::create($req->only(['message', 'date_start', 'type_envoi']));
            }
            MessageCampaign::create([
                'message_id' => $message->id,
                'campaign_detail_id' => $campaign->id,
            ]);
        }
        // dd($_);
        return back();
    }


    public function show($id)
    {
        //
    }


    public function edit(Request $request, $id)
    {
        $stores = $this->storeService->getAll();
        $owners = $this->ownerService->getOwners();
        $campaigns = $this->campaignService->getall();
        $campaign_details = CampaignDetail::findOrFail($id);
        return view('admin.campaign.edit', compact('stores', 'owners', 'campaigns', 'campaign_details'));
    }

    public function update(Request $request, $id)
    {
        $CampaignDetail = CampaignDetail::find($id);
        $request->validate([
            'campaign_id' => 'integer|exists:campaigns,id',
            'filter_id' => 'integer|exists:filters,id',
            'store_id' => 'integer|exists:stores,id',
            // 'product_id'=>'integer|exists:products,id',
            'reduction' => 'integer',
        ]);
        $CampaignDetail->update($request->only([
            'campaign_id', 'filter_id', 'store_id', 'product_id', 'reduction',
            'nombre_client', 'campaign_name', 'active',
        ]));
        $CampaignDetail->filter->update($request->only([
            'points', 'age_max', 'age_min', 'last_visit', 'sexe', 'heure_start', 'heure_end', 'active',
        ]));
        $CampaignDetail->message_camp()->forceDelete();

        $messages = json_decode($request->messages);
        // dd( $messages);
        foreach ($messages as $msg) {
            $req = new Request(json_decode(json_encode($msg), true));
            $messageUpdate = Message::find($msg->id);
            $validator = Validator::make($req->toArray(), [
                'message' => 'required',
                'date_start' => 'integer |required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
            $messageUpdate->update($req->only(['message', 'date_start', 'type_envoi']));

            MessageCampaign::create([
                'message_id' => $msg->id,
                'campaign_detail_id' => $id,
            ]);
        }
        return back();
    }
    public function destroy($id)
    {
        //
    }
}
