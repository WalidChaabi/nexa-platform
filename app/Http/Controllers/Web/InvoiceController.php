<?php

namespace App\Http\Controllers\Web;

use Config;
use App\User;
use Carbon\Carbon;
use App\Models\Fond;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\Customer;
use App\Events\OrderEvent;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Services\InvoiceService;
use App\Services\ProductService;
use App\Services\CampaignService;
use App\Services\CustomerService;
use App\Services\BienvenuService;
use App\Models\CampaignHistorique;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;


// use PDF;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AuthorizesInvoiceAccess;
use PHPUnit\Util\Json;

class InvoiceController extends Controller
{
    use AuthorizesInvoiceAccess;

    protected $campaignService;
    protected $bienvenuService;


    public function __construct()
    {
        $this->campaignService = new CampaignService();
        $this->bienvenuService = new BienvenuService;
    }

    public function index(Request $request)
    {
        if (isset($_GET['start_date']) || isset($_GET['end_date'])) {

            $this->validate($request, [

                'start_date' => 'required',
                'end_date'  => 'required',
            ], [

                'start_date.required'   => __('Le Champ Date Debut est requis'),
                'end_date.required'   => __('Le Champ Date fin est requis'),
            ]);
            $invoices = Invoice::where('store_id', '=', auth()->user()->store->id)->whereBetween('invoice_date', [$request->start_date, $request->end_date])->with('campaign')->OrderBy('id', 'DESC')->get();
        } else {
            $invoices = Invoice::where('store_id', '=', auth()->user()->store->id)
                ->whereBetween('invoice_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->with('campaign')->OrderBy('id', 'DESC')->get();
        }
        $lastInvoice = Invoice::where('store_id', '=', auth()->user()->store->id)
            ->with('campaign')
            ->orderBy('id', 'DESC')
            ->first();
        $printUrl = $request->session()->get('printUrl', null);

        return view('employe.invoices.list', compact('invoices', 'printUrl'));
    }


    public function getOwnerInvoices(Request $request, $items = 25)
    {

        $stores_id = [];
        foreach (Store::where('user_id', '=', auth()->user()->id)->get() as $store) {

            array_push($stores_id, $store->id);
        }

        $invoices = Invoice::whereIn('store_id', $stores_id)->OrderBy('id', 'DESC')->get();


        return view('owner.invoices.invoices', compact('invoices'));
    }


    public function create()
    {
        //get the clients 
        $store = Store::find(auth()->user()->store_id);
        //get the owner and check if sharing data is allowed 
        $owner = User::where('id', '=', $store->user_id)->first();
        $customers = CustomerService::getSharedCustomer($owner);
        // dd($customers);
        if ($store->praticien_1 == 1) $type = "praticien_1";
        if ($store->praticien_2 == 1) $type = "praticien_2";
        $products = ProductService::index();
        $employes = User::where('store_id', '=', $store->id)->get();
        return view('employe.invoices.create', compact('customers', 'products', 'employes', 'type'));
    }

    public function store(Request $request)
    {
        //validate for sexe_type
        $request['ResteCheck'] = $request->has('ResteCheck');

        $validatedData = $request->validate([
            'paid_amount' => ['required'],
            'products'      => ['required', 'not_in:0'],
            'customer' => ['required', 'not_in:0'],
        ]);
        // $isChecked = $request->has('ResteCheck');
        // $isChecked = $request['ResteCheck'];
        $data = $request->all();
        // dd($data);

        $client = $this->findAuthorizedStoreScopedModel(Customer::class, $request->customer);
        $use_red_final = $client->points;
        $store = Store::find(auth()->user()->store_id);
        $base = $store->base;
        $base_profit = $store->base_profit;
        $total = ceil($request->total); // i added  *0.9 for reduction
        $to_paye = ceil($request->paid_amount); // i added  *0.9 for reduction

        if ($request->use_points == 1) {

            if (($request->total - $client->points) >= 0) {

                $use_red = InvoiceService::calculatePoints(true, $client, $total, $base_profit, $base);
            } else {

                $use_red = InvoiceService::calculatePoints(false, $client, $total, $base_profit, $base);
            }
        } else {

            $points_without_discount = InvoiceService::calculatePointsWithoutBalance($total, $client, $store->coeff, $base_profit, $base);
            $use_red_final = $use_red = 0;
        }
        $userId = in_array(auth()->user()->store_id, [19, 20]) && $request->userId != 0  ? $request->userId : Auth()->user()->id;

        $invoice_id = InvoiceService::createInvoice(Auth()->user()->store_id, $userId, $use_red, $data);
        InvoiceService::createInvoicePaiement($invoice_id, $data);
        if (json_decode($data['type']) === "praticien_2") {
            InvoiceService::createInvoiceProductPraticien($invoice_id, $data, Auth()->user()->store_id);
        } else {
            InvoiceService::createInvoiceProduct($invoice_id, $data, 0);
        }
        $tva = $store->tva;
        $invoice = Invoice::find($invoice_id);
        $invoice->total_ht = $request->total_ht;
        $invoice->tva = auth()->user()->store->tva;
        if ($request->use_points == 1) {
            $invoice->points = $use_red_final;
            $client->points = 0;
        } else {
            $client->points = (int)($use_red_final + $points_without_discount);
        }

        $client->save();
        $invoice->save();
        Fond::create([
            'store_id'        => $store->id,
            'invoice_id'      => $invoice->id,
            'encasement_type' => 0,
            'encasement_date' => Carbon::now()
        ]);

        if (isset($client->email)) {
            //   event(new OrderEvent($client, $invoice, $store));
        }
        //new code 
        if ($request->code_valide == 1) {
            CampaignHistorique::where('customer_id', '=', $request->customer)->where('code_red', '=', $request->code_red)->first()->update(['expired_code' => 1, 'invoice_id' => $invoice->id]);
        }
        $store = Store::find(auth()->user()->store_id);
        $owner = User::where('id', '=', $store->user_id)->first();
        $stores_id = Store::where('user_id', '=', $owner->id)->select('id')->groupBy('id')->get();

        $customer_id = $request->customer;
        //end new code

        // if ($request->code_valide == 1) {
        //     CampaignHistorique::where('customer_id', '=', $request->customer)->where('code_red', '=', $request->code_red)->first()->update(['expired_code' => 1, 'invoice_id' => $invoice->id]);
        // }

        //Sending bienvenu message
        // $stores_id=Auth()->user()->store_id;  
        // if(is_array($stores_id)){
        //     foreach($stores_id as $store_id){
        //         $customer_id = $request->customer;
        //         $SmsBirthday = $this->bienvenuService->SmsBienvenu($store_id,$customer_id);
        //     }
        // }else {
        //     $store_id=Auth()->user()->store_id;
        //     $customer_id = $request->customer;
        //     $SmsBirthday = $this->bienvenuService->SmsBienvenu($store_id,$customer_id);
        // }

        // if (in_array(Auth()->user()->store_id, [19, 10])) {
        //     $path = 'messages.' . Auth()->user()->store->sender_id . '.welcome';
        //     $message = Config::get($path);
        //     $message = str_replace('(prénom)', $client->lastname, $message);
        //     $data = [];
        //     $data['camp_id']  = 6;
        //     $data['store_id'] = Auth()->user()->store_id; //hard coded for now
        //     $data['phone']    = $client->phone;
        //     $data['message']  =  $message;
        //     $data['start_date'] = Carbon::now();
        //     $data['sender']     =  Auth()->user()->store->sender_id;
        //     $this->campaignService->singleCampaign($data);
        // }
        // if (Auth()->user()->store->POS == 1) {
        $printUrl = route('employe.invoices.print', $invoice_id);

        if (Auth()->user()->store->POS == 1) {
            return $printUrl;
        }
        if (json_decode($data['type']) === "praticien_2") {
            return $printUrl;
        }


        // } else {
        $notification = array(
            'message' => 'Post created successfully!',
            'alert-type' => 'success'
        );
        if (!$data['ResteCheck']) {
            return redirect('employe/invoices')->with($printUrl);
        } else if ($data['ResteCheck'] == true) {
            return redirect('employe/invoices')->with($notification, $printUrl);
        } else if ($data['ResteCheck'] == false) {
            return redirect('employe/invoices')->with($printUrl);
        }
        // }
    }


    public function renderProductRow1()
    {
        $products = ProductService::index();
        $store = Store::find(auth()->user()->store_id);
        $employes = User::where('store_id', '=', $store->id)->get();
        $returnHTML = view('employe.invoices.row', compact('products', 'employes'))->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
    public function renderProductRow2()
    {
        $products = ProductService::index();
        $store = Store::find(auth()->user()->store_id);
        $employes = User::where('store_id', '=', $store->id)->get();
        $returnHTML = view('employe.invoices.row1', compact('products', 'employes'))->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function printInvoice($id)
    {
        $url = route('employe.invoices.print', $id);
        return   $url;
    }



    public function print($id)
    {
        $this->findAuthorizedInvoice($id);
        $invoice = InvoiceService::printInvoice($id);
        $content = view('emails.invoice_restau', array('content' => $invoice->groupBy('product_id')))->render();
        return response($content)->header('Content-Type', 'text/html');
    }
    // public function print($id)
    // {

    //     $invoice = InvoiceService::printInvoice($id);
    //     // $customPaper = array(0, 0, 567.00, 283.80);
    //     $customPaper = array(0, 0, 650.00, 295.80);
    //     // invoice_restau.blade.php
    //     if (Auth()->user()->store->POS == 1) {
    // $pdf =  PDF::loadView('emails.invoice_restau', array('content' => $invoice->groupBy('product_id')))->setPaper($customPaper, 'landscape');
    //     } else {
    //         $pdf =  PDF::loadView('emails.invoice_new', array('content' => $invoice))->setPaper($customPaper, 'landscape');
    //     }

    //     return $pdf->download("Facture_#" . $id . ".pdf");
    // }

    public function returnInvoice(Request $request)
    {

        $invoice = $this->findAuthorizedInvoice($request->invoice_id);
        $store = Store::find(auth()->user()->store_id);
        $client = $invoice->customer;
        $base = $store->base;
        $base_profit = $store->base_profit;
        if ($invoice->points > 0) {
            $oldPoints =  InvoiceService::calculatePoints(false, $client, $request->hidden_total, $base_profit, $base);
        } else {
            $oldPoints =  InvoiceService::calculatePoints(true, $client, $request->hidden_total, $base_profit, $base);
        }
        if ($client->points - $oldPoints <= 0) {
            $client->points = 0;
            $client->save();
        } else {
            $client->points = (int) ($client->points - $oldPoints);
            $client->save();
        }
        $found   = Fond::create([
            'store_id'       => auth()->user()->store->id,
            'invoice_id'     => $request->invoice_id,
            'encasement_type' => 2,
            'value'          => $request->hidden_total,
            'encasement_date' => Carbon::now()
        ]);
        $found->refresh();
        return redirect('employe/invoices');
    }

    public function getReturns()
    {

        $founds = [];
        $total = 0;
        if (isset($_GET['store_id'])) {

            if (!in_array($_GET['store_id'], auth()->user()->stores->pluck('id')->toArray()))
                return abort(404);
            $founds = Fond::OrderBy('id', 'desc')->where('encasement_type', '=', 2)
                ->whereBetween('encasement_date', [$_GET['start_date'], $_GET['end_date']])
                ->where('store_id', '=', $_GET['store_id'])->get();
        }

        return view('owner.returns.index', compact('founds', 'total'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = $this->findAuthorizedInvoice($id);
        $store = Store::find($invoice->store_id);
        $owner = User::where('id', '=', $store->user_id)->first();
        $customer = Customer::find($invoice->customer_id);
        $products = InvoiceProduct::where('invoice_id', '=', $id)->get();
        return view('employe.invoices.detail', compact('customer', 'products', 'invoice'));
    }


    public function reste(Request $request)
    {
        $invoice = Invoice::select('reste', 'reste_payer', 'store_id')
            ->where('customer_id', $request->customer_id)
            ->where('store_id', auth()->user()->store_id)
            ->orderBy('invoice_date', 'desc')
            ->first();

        if (!$invoice) {
            return response()->json(['reste' => 0]);
        }

        return response()->json(['reste' => $invoice['reste'] - $invoice['reste_payer']]);
    }

    public function restePayer(Request $request)
    {
        $reste = Invoice::select("*")
            ->where('customer_id', $request->customer_id)
            ->where('store_id', auth()->user()->store_id)
            ->orderBy('invoice_date', 'desc')
            ->firstOrFail();
        $reste->update([
            'reste' => $reste['reste'] - $request->reste,
        ]);
    }

    public function edit($id)
    {
        //get the clients 
        $store = Store::find(auth()->user()->store_id);

        if ($store->praticien_1 == 1) {
            $type = "praticien_1";
        } elseif ($store->praticien_2 == 1) {
            $type = "praticien_2";
        } else {
            $type = "";
        }

        $owner = User::where('id', '=', $store->user_id)->first();
        $customers = CustomerService::getSharedCustomer($owner);
        $products = ProductService::index();
        $invoice = $this->findAuthorizedInvoice($id);
        if ($invoice->status == 0) {
            $customer = Customer::find($invoice->customer_id);
            //get the products of the invoice 
            $invoiceProducts = InvoiceProduct::where('invoice_id', '=', $id)->get();
            $employes = User::where('store_id', '=', $store->id)->get();
            return view('employe.invoices.edit', compact('customers', 'customer', 'products', 'invoice', 'employes', 'invoiceProducts','type','store'));
        } else {
            return abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'paid_amount' => ['required'],
            'customer' => ['required', 'not_in:0'],
            // 'for'      => ['required']
        ]);
        $total_ht = $request->total_ht;

        $data = $request->all();
        $oldInvoice = $this->findAuthorizedInvoice($id);
        $client = $this->findAuthorizedStoreScopedModel(Customer::class, $request->customer);
        $use_red_final = $client->points;
        $store = Store::find(auth()->user()->store_id);
        $base = $store->base;
        $base_profit = $store->base_profit;
        $total = ceil($request->total); // i added  *0.9 for reduction
        $to_paye = ceil($request->paid_amount); // i added  *0.9 for reduction
        $old_points = InvoiceService::calculatePoints(true, $client, $oldInvoice->total, $oldInvoice->paid_amount, $base_profit, $base); // i added  *0.9 for reduction
        if ($client->points - $old_points <= 0) {
            $client->points = 0;
            $client->save();
        } else {
            $client->points = $client->points - $old_points;
            $client->save();
        }

        if ($request->use_points == 1) {

            if ($client->points > 0) {
                //before making anything plz update the points of the client 
                $old_points = InvoiceService::calculatePoints(true, $client, $total, $to_paye, $base_profit, $base);

                //end recalculate points
                if (($request->total - $client->points) >= 0) {

                    $use_red = InvoiceService::calculatePoints(true, $client, $total, $to_paye, $base_profit, $base);
                } else {

                    $use_red = InvoiceService::calculatePoints(false, $client, $total, $to_paye, $base_profit, $base);
                }
            } else {
                return redirect()->back()->with('message', 'Vous pouvez pas utiliser les points de reduction');
            }
        } else {

            InvoiceService::calculatePointsWithoutBalance($total, $client, $store->coeff, $base_profit, $base);
            $use_red_final = $use_red = 0;
        }
        $userId = in_array(auth()->user()->store_id, [19]) && !empty($request->userId) ?: Auth()->user()->id;
        $invoice_id = InvoiceService::createInvoice(Auth()->user()->store_id, $userId, $use_red, $data);
        Fond::create([
            'store_id'        => $store->id,
            'invoice_id'      => $invoice_id,
            'encasement_type' => 0,
            'encasement_date' => Carbon::now()
        ]);
        //event to send Mail Order to client

        InvoiceService::createInvoicePaiement($invoice_id, $data);
        InvoiceService::createInvoiceProduct($invoice_id, $data, 0);
        $tva = $store->tva;

        $invoice = Invoice::find($invoice_id);
        $invoice->total_ht = $total_ht;
        $invoice->tva = auth()->user()->store->tva;
        $invoice->save();
        if (isset($client->email)) {
            //event(new OrderEvent($client, $invoice, $store));
        }
        $oldInvoice->status = 4;
        $oldInvoice->save();

        Fond::create([
            'store_id'        => $store->id,
            'invoice_id'      => $oldInvoice->id,
            'encasement_type' => 1,
            'encasement_date' => Carbon::now()
        ]);
        return redirect()->route('employe.invoices');
    }

    public function cancel($id)
    {
        $oldInvoice = $this->findAuthorizedInvoice($id);
        $client = Customer::find($oldInvoice->customer_id);
        $store = Store::find($oldInvoice->store_id);
        $base = $store->base;
        $base_profit = $store->base_profit;
        if ($oldInvoice->points > 0) {
            $oldPoints =  InvoiceService::calculatePoints(false, $client, $oldInvoice->total, $base_profit, $base);
        } else {
            $oldPoints =  InvoiceService::calculatePoints(true, $client, $oldInvoice->total, $base_profit, $base);
        }
        if ($client->points - $oldPoints <= 0) {
            $client->points = 0;
            $client->save();
        } else {
            $client->points = (int) ($client->points - $oldPoints);
            $client->save();
        }
        InvoiceService::cancelInvoice($id);
        Fond::create([
            'store_id'        => $store->id,
            'invoice_id'      => $oldInvoice->id,
            'encasement_type' => 1,
            'encasement_date' => Carbon::now()
        ]);

        return redirect()->back();
    }

    public function checkStore($id)
    {
        $store = $this->findAuthorizedStore($id);
        $hasRA = $store->RA = 1;

        return response()->json(['hasRA' => $hasRA]);
    }

    public function recherche(Request $request)
    {
        $this->authorizeStoreIdFromRequest($request->recherch_store_id);

        $stores_id = [];
        $total = 0;
        foreach (auth()->user()->stores as $store) {
            array_push($stores_id, $store->id);
        }
        $phone = $request->recherche_avencee;
        if ($phone) {
            $invoices = Invoice::join('customers', 'invoices.customer_id', '=', 'customers.id')
                ->where('invoice.store_id', $request->recherch_store_id)
                ->where('customers.phone', $phone)
                ->select('invoices.*')
                ->get();
        }
        $name = $request->name_rechercher;
        if ($name) {
            $invoices = Invoice::join('customers', 'invoices.customer_id', '=', 'customers.id')
                ->where('invoices.store_id', $request->recherch_store_id) // Use "invoices.store_id" instead of "invoice.store_id"
                ->where(function ($query) use ($name) {
                    $query->where('customers.firstname', 'like', '%' . $name . '%')
                        ->orWhere('customers.lastname', 'like', '%' . $name . '%');
                })
                ->select('invoices.*')
                ->get();
        }


        if (auth()->user()->is_admin == 2) {
            return view('owner.repport.results', [
                'stores' => auth()->user()->stores->pluck('name', 'id'),
                'invoices' => $invoices,
            ]);
        } else {
            return view('employe.invoices.list', compact('invoices'));
        }
    }

    protected function calculatePoints($total, $base_profit, $base)
    {
    }

    public function getCanceledInvoices($items = 25)
    {

        $items = (isset($_GET['items'])) ? $_GET['items'] : $items;
        $stores_id = [];
        $total = 0;
        foreach (auth()->user()->stores as $store)
            array_push($stores_id, $store->id);
        (isset($_GET['start_date']) && isset($_GET['end_date'])) ? $invoices = Invoice::whereIn('store_id', $stores_id)->whereBetween('invoice_date', [$_GET['start_date'], $_GET['end_date']])->where('status', '=', 2)->paginate($items)  : $invoices = Invoice::whereIn('store_id', $stores_id)->where('status', '=', 2)->paginate($items);

        foreach ($invoices as $invoice)
            $total = $total + $invoice->total;
        return view('owner.invoices.canceled', compact('invoices', 'total'));
    }
}
