<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Events\OwnerEvent;
use App\Http\Requests\OwnerRequest;
use App\Models\CampaignHistorique;
use Illuminate\Support\Str;
use App\Services\InvoiceService;
use App\Services\CustomerService;
use App\Services\SmsService;
use App\Models\EmailCampaign;
use App\models\Store;
use App\Services\SmsCrossSellingService;
use App\Services\StoreService;
use App\Services\UserService;
use App\User;
use Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OwnerController extends Controller
{

    protected $storeService;
    protected $userService;
    protected $smsService;
    public string $dateToPay;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = new UserService;
        $this->storeService = new StoreService;
        $this->smsService = new SmsService;
        $this->dateToPay = '';
    }

    public function getDashboard()
    {

        $stores = auth()->user()->stores;
        $stores_id = array_values($stores->pluck('id')->toArray());
        $invoices = InvoiceService::getOwnerInvoices($stores_id, 0);
        $total_vente = InvoiceService::getTotalVenteOwner($stores_id);
        $ca = InvoiceService::getTurnOverOwner($stores_id);
        $ca_today = InvoiceService::getTurnOverOwnerToday($stores_id);
        $ca_last_day = InvoiceService::getTurnOverOwnerBeforeToday($stores_id);
        $ca_week = InvoiceService::getTurnOverOwnerWeek($stores_id);
        $ca_month = InvoiceService::getTurnOverOwnerMonth($stores_id);
        $retention = InvoiceService::getRetention($stores_id);
        $orderAverage = InvoiceService::getOrderAverage(InvoiceService::getOwnerInvoices($stores_id, 1));
        $stores = (isset(auth()->user()->stores)) ? auth()->user()->stores : [];
        $emails = EmailCampaign::whereIn('store_id', $stores_id)->where('status', '=', 0)->get()->count();

        $mergedSellers = collect();

        foreach ($stores_id as $store_id) {
            if (Store::findOrFail($store_id)->praticien_2 == 1) {
                $sellers = InvoiceService::getBestSellersPraticien($store_id);
            } else {
                $sellers = InvoiceService::getBestSellers($store_id);
            }
            if ($sellers instanceof \Illuminate\Support\Collection) {
                $mergedSellers = $mergedSellers->concat($sellers);
            }
        }
        
        $countSms    = SmsService::getCountSms($stores_id);
        $closeAccountReminder =  $this->closeAccountReminder();
        $dateToPay = $this->dateToPay;
        return view('dashboard.dashboard-02', compact('emails', 'dateToPay', 'closeAccountReminder',
         'invoices', 'total_vente', 'ca', 'ca_today', 'ca_last_day', 'ca_week', 
        'ca_month', 'retention', 'orderAverage', 'stores', 'mergedSellers', 'countSms'));
    }


    public function getChartData($type)
    {
        $stores_id = [];
        //the evolution of Turn Over for each month
        foreach (auth()->user()->stores as $item)
            array_push($stores_id, $item->id);
        $data = InvoiceService::getRedAccGranted($stores_id, $type);
        return $data;
    }

    public function getRepportData()
    {
        //['store name','month','numbre']
        $data = InvoiceService::getCustomersStoresByDate(auth()->user()->stores);
        return $data;
    }

    public function getTurnOver()
    {
        $customers = CustomerService::getlastCustomersOwner(auth()->user()->id);
        $data = InvoiceService::turnOverStores(auth()->user()->stores, $customers);
        return $data;
    }

    public function getSoldeDetail()
    {
        return view('owner.solde');
    }


    public function index()
    {
        $owners = User::where('is_admin', '=', '2')->orderBy('id', 'DESC')->get();
        return view('admin.owners.list', compact('owners'));
    }


    public function store(Request $request)
    {
        $request->has('allow_share') ? $allow = 1 : $allow = 0;
        $request['remember_token'] = (Str::random(60));

        $owner = User::create([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone' => $request['phone'],
            'allow_share' => $request['allow_share'],
            'is_admin' => 2,
            'allow_share' => $allow,
        ]);
        // Event::dispatch(new OwnerEvent($owner,$request->password));
        return redirect()->route('admin.owners');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $owner = User::where('is_admin', '=', 2)->where('id', '=', $id)->first();
        return  view('admin.owners.edit', compact('owner'));
    }

    public function update(Request $request, $id)
    {
        $owner = User::find($id);
        $newOwner['firstname'] = $request['firstname'];
        $newOwner['lastname'] = $request['lastname'];
        $newOwner['phone'] = $request['phone'];
        if ($request->has('password') && $request->password!='' ) {
            $newOwner['password'] = Hash::make($request['password']);
        }
        if ($request->has('allow_share')) {
            $newOwner['allow_share'] = 1;
        }
        $owner->update($newOwner);
        if(auth()->user()->is_admin===1)
          return redirect()->route('admin.owners');
    }


    public function destroy($id)
    {
        $owner = User::find($id);
        $storesId = $owner->stores->pluck('id')->toArray();
        $owner->is_active = 0;
        User::whereIn('store_id', $storesId)->update(['is_active' => 0]);
        $owner->save();

        return redirect()->route('admin.owners');
    }

    public function active($id)
    {
        $owner = User::find($id);
        $storesId = $owner->stores->pluck('id')->toArray();
        $owner->is_active = 1;
        $owner->save();
        User::whereIn('store_id', $storesId)->update(['is_active' => 1]);

        return redirect()->route('admin.owners');
    }


    public function closeAccountReminder()
    {
            $stores = auth()->user()->stores;
            $storesId = $stores->pluck('id')->toArray();
            $countSms = $this->smsService->countSmsOfThisMonth($storesId);
            $countSmsCrossSelling = $this->smsService->getCrossSellingOfThisMonth($storesId);
            $index = shouldPayFormMonth();
            $total = $countSms * config('constant.sms_price') + $countSmsCrossSelling * config('constant.sms_cross_selling');
            $balance = config('constant.balance');
            Log::info([$balance,$total,$countSms,$countSmsCrossSelling,$countSmsCrossSelling,config('constant.sms_cross_selling')]);
            if ($total  >= $balance) {
                if (!empty($index)) {
                    $this->dateToPay = $index[1];
                    return true;
                } else {
                    return false;
                }
            }
            return false;
    }

    public function cleanSms(){
        $storesIds =  auth()->user()->stores->toArray();
        $smss = SmsService::countSms(auth()->user()->stores->pluck('id')->toArray());
        return $smss->count();
    }
}
