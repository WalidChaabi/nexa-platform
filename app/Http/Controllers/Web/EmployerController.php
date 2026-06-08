<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Services\InvoiceService;
use App\Services\CustomerService;
use App\User;
use App\Models\Store;
use App\Services\UserService;

class EmployerController extends Controller
{

    protected $userService;
    public function __construct()
    {
        $this->userService = new UserService;
    }
    public function getDashboard()
    {

        
        $total_vente=InvoiceService::getTotalVente();
        $count_visite=InvoiceService::getTotalCustomer();


        $objective=Objective::where('user_id','=',auth()->user()->id)->first();
        $ca=InvoiceService::getTurnOver();
        $invoices=InvoiceService::getAuthInvoices();
        $store=Store::find(auth()->user()->store_id);
        
        $customers=CustomerService::getlastCustomers($store->user_id);
       
        $obj_realised=InvoiceService::getRealisedObjectives();

       
        return view('dashboard.dashboard-03',compact('total_vente','count_visite','objective','ca','invoices','obj_realised'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->is_admin == 1) {
            $stores = Store::where('status', '=', 1)->get();
            $employees = User::where('is_admin', '=', 3)->get();
            return view('admin.employees.list', compact('employees', 'stores'));
        } elseif (auth()->user()->is_admin == 2) {
            $stores = Store::where('status', '=', 1)->where('user_id', '=', auth()->user()->id)->get();
            $stores_id = [];
            foreach ($stores as $item)
                array_push($stores_id, $item->id);
            $employees = User::whereIn('store_id', $stores_id)->where('is_admin', '=', 3)->orderBy('id','DESC')->get();
            return view('owner.employees.list', compact('employees', 'stores'));
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function objectives()
    {
        if (auth()->user()->is_admin == 1) {
            $stores = Store::where('status', '=', 1)->get();
            $employees = User::where('is_admin', '=', 3)->get();
            return view('admin.objectives.list', compact('employees', 'stores'));
        } elseif (auth()->user()->is_admin == 2) {
            $stores = Store::where('status', '=', 1)->where('user_id', '=', auth()->user()->id)->get();
            $stores_id = [];
            foreach ($stores as $item)
                array_push($stores_id, $item->id);
            $employees = User::whereIn('store_id', $stores_id)->where('is_admin', '=', 3)->orderBy('id','DESC')->get();
            $employers_id=[];
            foreach($employees as $item)
                array_push($employers_id,$item->id);

            $objectives=Objective::whereIn('user_id',$employers_id)->get();  
           
            return view('owner.objectives.list', compact('objectives', 'stores'));
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
        $request->has('allow_share') ? $allow = 1 : $allow = 0;
        $employ = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'allow_share' => $request->allow_share,
            'is_admin' => 3,
            'store_id' => $request->store_id,
            'allow_share' => $allow
        ]);
        $objective=Objective::create([
            'user_id'=>$employ->id,
            'daily'=>$request->daily,
            'weekly'=>$request->weekly,
            'monthly'=>$request->monthly,
        ]);
        $store = Store::findOrFail($request->store_id);
        // Event::dispatch(new EmployEvent($employ, $store, $request->password));
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('admin.employees');
        } else {
            return redirect()->route('owner.employees');
        }
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
        $employe = $this->userService->find($id);
       if(auth()->user()->is_admin==1)
            return view('admin.employees.edit',compact('employe'));
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
        $employer =$this->userService->find($id);
        $newEmployer['firstname'] = $request['firstname'];
        $newEmployer['lastname'] = $request['lastname'];
        $newEmployer['phone'] = $request['phone'];
        if ($request->has('password') && $request->password!='' ) {
            $newEmployer['password'] = Hash::make($request['password']);
        }
        $this->userService->update($id,$newEmployer);
        if(auth()->user()->is_admin===1)
                return redirect(route('admin.employees'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employer = User::find($id);
        $employer->is_active = 0;
        $employer->save();
        return redirect(route('admin.employees'));
    }

    public function active($id)
    {
        $employer = User::find($id);
        $employer->is_active = 1;
        $employer->save();
        return redirect(route('admin.employees'));
    }
}
