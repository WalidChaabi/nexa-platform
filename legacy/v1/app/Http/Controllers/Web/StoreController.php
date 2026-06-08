<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AuthorizesStoreScopedAccess;
use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Store;
use App\Services\ProductService;
use App\Support\SecureImageUpload;
use App\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;

class StoreController extends Controller
{
    use AuthorizesStoreScopedAccess;

    protected $productService;


    public function __construct()
    {
        $this->productService = new ProductService;
    }

    public function index()
    {

        $activities = Activity::all();
        if (auth()->user()->is_admin == 1) {

            $owners = User::where('is_admin', '=', 2)->where('is_active', '=', 1)->get();
            $stores = Store::OrderBy('id', 'DESC')->get();
            return view('admin.stores.list', compact('stores', 'owners', 'activities'));
        } else {

            $stores = Store::where('user_id', '=', auth()->user()->id)->orderBy('id', 'DESC')->get();

            return view('owner.stores.list', compact('stores', 'activities'));
        }
    }

    public function create()
    {
        //
    }

    public function QG(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['QG' => $request->QG]); 
    }
    public function POS(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['POS' => $request->POS]);
    }
    public function reste_Payer(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['reste' => $request->reste]); 
    }

    public function rendez_vous_Payer(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['rendez_vous' => $request->rendez_vous]);
    }

    public function praticien_1_Payer(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['praticien_1' => $request->praticien_1]); 
    }
    public function praticien_2_Payer(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['praticien_2' => $request->praticien_2]); 
    }

    public function Ra(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['RA' => $request->RA]); 
    }
    public function Anniv(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        $store->update(['Anniv' => $request->Anniv]); 
    }
    public function Table(Request $request)
    {
        $store = $this->findAuthorizedStore($request->store_id);
        if($store->POS ==1 ){
            $store->update(['Table' => $request->Table]);
        }else{
            return Response()->Json(['msg'=>'vous douvrez avoir POS'],401);
        }
    }



    public function store(Request $request)
    {
        if (auth()->user()->is_admin == 2) {
            return abort(403);
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => SecureImageUpload::rules()]);
        }

        if ($request->hasFile('logo')) {
            $store = Store::create([
                'user_id' => $request->owner_id,
                'activity_id' => $request->activity_id,
                'name' => $request->store_name,
                'sender_id' => $request->sender_id,
                'address' => $request->address,
                'contact' => $request->contact,
                'phone' => $request->phone,
                'base' => $request->base_calcul,
                'base_profit' => $request->base_profit,
                'coeff' => $request->coeff,
                'min_point_to_use' => $request->min_point_to_use,
                'logo' => '',
                'tva' => $request->tva,
                'invoice_message' => $request->message_invoice
            ]);
            $logo = $this->move_files($request->logo, $store->id);
            $store->update(['logo' => $logo]);
        } else {
            $store = Store::create([
                'user_id' => $request->owner_id,
                'activity_id' => $request->activity_id,
                'name' => $request->store_name,
                'sender_id' => $request->sender_id,
                'contact' => $request->contact,
                'phone' => $request->phone,
                'address' => $request->address,
                'base' => $request->base_calcul,
                'base_profit' => $request->base_profit,
                'coeff' => $request->coeff,
                'tva' => $request->tva,
                'invoice_message' => $request->message_invoice,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return redirect()->route('admin.stores');
    }


    protected function move_files($file, $id)
    {
        return SecureImageUpload::store($file, 'uploads/stores/' . $id);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $store = $this->findAuthorizedStore($id);

        if (auth()->user()->is_admin == 1) {
            $owners = User::where('is_admin', '=', 2)->where('is_active', '=', 1)->get();
            $activities = Activity::all();

            return view('admin.stores.edit', compact('store', 'activities', 'owners'));
        }

        $activities = Activity::all();

        return view('owner.stores.edit', compact('store', 'activities'));
    }


    public function update(Request $request, $id)
    {
        $this->findAuthorizedStore($id);

        if (auth()->user()->is_admin == 1) {
            $owner_id = $request->owner_id;
        } elseif (auth()->user()->is_admin == 2) {
            $owner_id = auth()->user()->id;
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => SecureImageUpload::rules()]);
            $result = Store::where('id', $id)->update([
                'user_id' => $owner_id,
                'activity_id' => $request->activity_id,
                'name' => $request->store_name,
                'sender_id' => $request->sender_id,
                'address' => $request->address,
                'contact' => $request->contact,
                'phone' => $request->phone,
                'base' => $request->base_calcul,
                'base_profit' => $request->base_profit,
                'coeff' => $request->coeff,
                'logo' => $this->move_files($request->logo, $id),
                'tva' => $request->tva,
                'invoice_message' => $request->message_invoice

            ]); //remove the image exesting in stores directory next
            // uploadFile($request->logo,$request->owner,'stores');
        } else {

            $result = Store::where('id', $id)->update([
                'user_id' => $owner_id,
                'activity_id' => $request->activity_id,
                'name' => $request->store_name,
                'sender_id' => $request->sender_id,
                'contact' => $request->contact,
                'phone' => $request->phone,
                'address' => $request->address,
                'base' => $request->base_calcul,
                'base_profit' => $request->base_profit,
                'coeff' => $request->coeff,
                'tva' => $request->tva,
                'invoice_message' => $request->message_invoice
            ]);
        }
        if (auth()->user()->is_admin == 1)
            return redirect()->route('admin.stores');
        return redirect()->route('owner.stores');
    }


    public function destroy($id)
    {
        $store = $this->findAuthorizedStore($id);
        $store->status = 0;
        $store->save();

        if (auth()->user()->is_admin == 1) {
            return redirect()->route('admin.stores');
        }

        return redirect()->route('owner.stores');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $store = $this->findAuthorizedStore($id);
        $store->status = 1;
        $store->save();
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('admin.stores');
        } elseif (auth()->user()->is_admin == 2) {
            return redirect()->route('owner.stores');
        }
    }

    public function getProductsStore(Request $request)
    {
        $this->authorizeStoreIdFromRequest($request->store_id);
        $products = $this->productService->getProductsForCrossSelling($request->store_id);
        $returnHTML = view('admin.cross-selling.row', compact('products'))->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
}
