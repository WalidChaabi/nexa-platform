<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AuthorizesStoreScopedAccess;
use App\Models\CampaignDetail;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Models\Store;
use App\Models\CampaignHistorique;
use App\Models\Code;
use App\Models\Invoice;
use App\User;
use App\Services\CampaignService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use AuthorizesStoreScopedAccess;

    protected $campaignService;
    
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }
    
    
    public function storeOwnerCustomer(Request $request){
               
        $validated = $request->validate([
            // 'phone' => 'required|regex:/(212)[0-9]{9}/',
            'sexe'  => 'required|not_in:-1'
        ]);

        $this->authorizeStoreIdFromRequest($request->store_id);

        $customer=Customer::create([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'birth'=> $request->birth,
            'sexe'=>$request->sexe,
            'store_id'=>$request->store_id,
            'user_id'=>auth()->user()->id,
            // 'created_at'=>Carbon::now(),
            // 'updated_at'=>Carbon::now()
        ]);
        if($request->type==1){
            return   "<option selected value='".$customer->id."'>".$customer->getFullNameAttribute()." : " .$customer->phone. " </option>" ;
        }
        return redirect()->back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($items=25)
    {
        $items = isset($_GET['items']) ? (int) $_GET['items'] : (int) $items;

        switch (auth()->user()->is_admin) {
            case '1':
                $stores = Store::orderBy('name')->get();
                $customers = Customer::orderBy('id', 'DESC')->paginate($items);

                return view('employe.customers.list', compact('customers', 'stores'));
            case '2':
                $stores = Store::where('user_id', auth()->user()->id)->orderBy('name')->get();
                $storeIds = $stores->pluck('id')->toArray();
                $customers = Customer::whereIn('store_id', $storeIds)->orderBy('id', 'DESC')->paginate($items);

                return view('employe.customers.list', compact('customers', 'stores'));
            case '3':
                
                 $store=Store::findOrFail(auth()->user()->store_id);
                 $owner=User::findOrFail($store->user_id);
                (isset( $_GET['items']) ) ? $items=$_GET['items'] : $items=25 ;
                if( isset($_GET['start_date']) ||  isset($_GET['end_date'])   )
                $customers=CustomerService::getSharedCustomerView($owner,$items,$_GET['start_date'],$_GET['end_date']);
                else
                $customers=CustomerService::getSharedCustomerView($owner,$items);
                return view('employe.customers.list', compact('customers'));
            break;
            
            default:
                return abort(403);
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
  
        $validated = $request->validate([
            // 'phone' => 'required|regex:/(212)[0-9]{9}/',
            'sexe'  => 'required|not_in:-1'
        ]);

        $customer=Customer::create([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'birth'=> $request->birth,
            'sexe'=>$request->sexe,
            'store_id'=>auth()->user()->store_id,
            'user_id'=>auth()->user()->id,
            // 'created_at'=>Carbon::now(),
            // 'updated_at'=>Carbon::now()
        ]);
        if($request->type==1){
            return   "<option selected value='".$customer->id."'>".$customer->getFullNameAttribute()." : " .$customer->phone. " </option>" ;
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCustomerPoints(Request $request)
    {
        $customer = $this->findAuthorizedStoreScopedModel(Customer::class, $request->customer_id);
        $store = Store::findOrFail($customer->store_id);
        $facture = Invoice::where('customer_id', $request->customer_id)->get();
        $total = $facture->reduce(function ($carry, $item) {
            return $carry + $item->total;
        }, 0);
        $paid_amount = $facture->reduce(function ($carry, $item) {
            return $carry + $item->paid_amount;
        }, 0);
        // $reste= 0;
        // foreach($facture as $fac){
        //     $reste=$reste+$fac->reset;
        // }
        return [
            'mad_red' => $customer->points, 
            'points_red' => $customer->points * $store->coeff, 
            // 'reste'=> $reste
            // 'reste'=> $total - $paid_amount
        ];
        
    }

  
    public function edit($id)
    {
        $customer = $this->findAuthorizedStoreScopedModel(Customer::class, $id);

        return view('employe.customers.edit', compact('customer'));
    }

    
    public function update(Request $request, $id)
    {
        $customer = $this->findAuthorizedStoreScopedModel(Customer::class, $id);
        $customer->firstname=$request->firstname;
        $customer->lastname=$request->lastname;
        $customer->sexe=$request->sexe;
        $customer->phone=$request->phone;
        $customer->email=$request->email;
        $customer->birth=$request->birth;
        $customer->save();
        return redirect()->route('employe.customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
     public function getCustomerRed(Request $request)
    {

        // $campaignHistory = CampaignHistorique::where('code_red','=',$request->code)->where('customer_id','=',$request->customer_id)->where('expired_code','=',0)->first();
        // if($campaignHistory){

        //     if(date('Y-m-d', strtotime($campaignHistory->start_date. ' + 8 days')) >= date('Y-m-d'))
        //     {
        //         return response()->json(['success'=>1,'code'=>'0.1']);
        //     }else{
        //         return response()->json(['success'=>0,'message'=>'Vous pouvez pas utiliser ce code de reduction car il est expiré!']);
        //     }
        // }else{
        //     return response()->json(['success'=>0,'message'=>'Code invalide']);
        // // }
        // dd($request->store_id);
        $campaign_details = DB::table('campaign_details')
        ->join('codes', 'campaign_details.code_id', '=', 'codes.id')
        ->join('stores', 'campaign_details.store_id', '=', 'stores.id')
        ->join('customers', 'campaign_details.customer_id', '=', 'customers.id')
        ->where([
            ['code_red', '=', $request->code],
            ['stores.id', '=', $request->store_id],
            ['customers.id', '=', $request->customer_id],
        ])->select('codes.status', 'codes.reduction')
        ->first();
        // dd($campaign_details);       
        if(isset($campaign_details)){
            if($campaign_details->status == 0){
                $reduction = $campaign_details->reduction;
                return response()->json(['success'=>1,'code'=>$reduction]);
            }
            else{
                return response()->json(['success'=>0,'message'=>'Vous pouvez pas utiliser ce code de reduction car il est expiré!']);
            }
        }
        else{
            return response()->json(['success'=>0,'message'=>'Code invalide']);
        }
    }
    
}
