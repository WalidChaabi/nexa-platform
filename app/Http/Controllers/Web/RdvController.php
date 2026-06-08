<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Rdv;
use App\Services\CustomerService;
use App\Services\RdvService;
use Tymon\JWTAuth\Claims\Custom;

class RdvController extends Controller
{
    protected $rdvService;
    protected $customerService;
    public function __construct()
    {
        $this->rdvService = new RdvService;
        $this->customerService = new CustomerService;
    }

    public function create(Request $request)
    {
        $storeId =  $this->customerService->getCustomerById($request->rdv['customerId'])->store_id;
        $rdv = [];
        $rdv['title'] = $request->rdv['title'];
        $rdv['customer_id'] = $request->rdv['customerId'];
        $rdv['event_id'] = $request->rdv['eventId'];
        $rdv['store_id'] = $storeId;
        $rdv['start'] = $request->rdv['start'];
        return $this->rdvService->create($rdv);
    }

    public function getAllByStoreId()
    {
        if (auth()->user()->is_admin == 3) {
            $storesId = [auth()->user()->store->id];
        } else {
            $storesId = auth()->user()->stores->pluck('id')->toArray();
        }
        $list =   $this->rdvService->getAllByStoresId($storesId)->map(function ($el) {
            // dd($el);
            // dd($el->title);
            $obj  = [];
            // $obj['title'] = $el->customer->getFullNameAttribute() . '/' . $el->customer->phone;
            $obj['title'] = $el->title;
            $obj['start'] = $el->start;
            $obj['overlap'] = false;
            $obj['rendering'] = 'background';
            $obj['color'] = $el->event_id;
            $obj['id'] = $el->id;
            return $obj;
        });
        // dd($list);
        return $list;
    }

    public function delete(Request $request)
    {
        $this->rdvService->delete($request->eventId);
        return $request->all();
    }

    public function deleteRdv($id)
    {
        $this->rdvService->delete($id);
        return back();
    }

    public function detail(Request $request)
    {
        $rdv_id = $request['id'];
        $rdv = Rdv::where('id',$rdv_id)->select('customer_id','start','id')->first();
        $customer = Customer::where('id',$rdv['customer_id'])->select('phone','firstname','lastname')->get();

        return ['customer'=>$customer,'rdv'=>$rdv];
    }

    public function update(Request $request)
    {
        return $this->rdvService->updateStartDate($request->eventId, $request->start);
    }
}
