<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Store;
use App\Models\Customer;
use App\Services\SmsService;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners=User::where('is_admin','=',2)->get();
        $campaigns=Campaign::where('type','=','birthday')->get();
        return view('admin.sms.campaign',compact('campaigns','owners'));
    }

    public function getData(){
        $countSms    = SmsService::countSms(auth()->user()->stores->pluck('id')->toArray());
        $to = Carbon::now()->endOfMonth();
        $from = Carbon::today();
        if($to->diffInDays($from) > 10 && $countSms * Config::get('constant.sms_price') > Config::get('constant.balance')  )
             return ['data'=> $to->diffInDays($from)];
        else 
            return ['data'=> $countSms * Config::get('constant.sms_price')];

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function getStoresByOwner(Request $request)
    {
        $this->validate($request,[
            'owner_id'=>'required|not_in:-1'
        ]); 
        $stores=Store::where('user_id','=',$request->owner_id)->get();
        $html = view('admin.sms.filtres.stores')
            ->with('stores', $stores)
            ->render();
        return response()->json(array('success' => true, 'html' => $html));
        
    }

    public function getCustomersByStore(Request $request)
    {
        $this->validate($request,[
            'store_id'=>'required|not_in:-1'
        ]); 
        $customers=Customer::where('store_id','=',$request->store_id)->get();
        $html = view('admin.sms.filtres.customers')
        ->with('customers', $customers)
        ->render();
        return response()->json(array('success' => true, 'html' => $html));
    }

}
