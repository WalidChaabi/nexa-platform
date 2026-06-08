<?php

namespace App\Http\Controllers\Web;

use App\Exports\ProductsDetails;
use App\Exports\ProductsDetails2;
use App\Http\Controllers\Controller;
use App\Models\CampaignDetail;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\EmailCampaign;
use App\Models\ProductQuantiteDetails;
use App\Services\CampaignService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RepportController extends Controller
{
    protected $campaignService;
    public function __construct()
    {
        $this->campaignService = new CampaignService;
    }
    public function getInvoices(Request $request)
    {
        $invoices = [];
        $total = 0;
        if (isset($_GET['store_id']) || isset($_GET['start_date']) || isset($_GET['end_date'])) {
            $this->validate($request, [
                'store_id'  => 'required',
                'start_date' => 'required',
                'end_date'  => 'required',
            ], [
                'store_id.required'   => __('Le Champ Store est requis'),
                'start_date.required'   => __('Le Champ Date Debut est requis'),
                'end_date.required'   => __('Le Champ Date fin est requis'),
            ]);
            $invoices = Invoice::where('status', '=', 0)
                ->where('store_id', '=', $request->store_id)
                ->whereBetween('invoice_date', [$request->start_date, $request->end_date])
                ->with('campaign')
                ->get();
            foreach ($invoices as $invoice) {
                $total = $total + $invoice->paid_amount;
            }
        }
        // dd($invoices);
        // $_ =collect();
        // $_ =[];
        // foreach($invoices as $item){
        // dd($item->customer->getFullNameAttribute());
        // $_ = $_->merge($item->customer);
        // array_push($_,$item->customer);
        // }
        // dd($_);
        return view('owner.repport.invoices', [
            'stores' => auth()->user()->stores->pluck('name', 'id'),
            'invoices' => $invoices,
            'total'   => $total
        ]);
    }

    public function getCustomers(Request $request)
    {
        $customers = [];
        $total = 0;

        if (isset($_GET['store_id']) || isset($_GET['start_date']) || isset($_GET['end_date'])) {
            $this->validate($request, [
                'store_id'  => 'required',
                'start_date' => 'required',
                'end_date'  => 'required',
            ], [
                'store_id.required'   => __('Le Champ Store est requis'),
                'start_date.required'   => __('Le Champ Date Debut est requis'),
                'end_date.required'   => __('Le Champ Date fin est requis'),
            ]);
            $customers = Customer::where('store_id', '=', $request->store_id)->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
            $total = $customers->count();
        }

        return view('owner.repport.customers', [
            'stores'     => auth()->user()->stores->pluck('name', 'id'),
            'customers'   => $customers,
            'total'      => $total
        ]);
    }

    public function getProducts(Request $request)
    {
        $totalMargeBrute = $totalSell = $total = 0;
        $stores = auth()->user()->stores->pluck('name', 'id');
        $products = [];$store_id='';

        return view('owner.repport.products', compact('totalMargeBrute', 'totalSell', 'total', 'stores', 'products','store_id'));
    }
    public function getProductsByTable(Request $request)
    {
        $store_id = $request['store_id'];
        if ($request->type == 't1') {
            $products = [];
            $total = 0;
            $totalMargeBrute = $totalSell = 0;

            $products = DB::table('invoices')
                ->join('invoice_products', 'invoice_products.invoice_id', '=', 'invoices.id')
                ->join('products', 'invoice_products.product_id', 'products.id')
                ->where('products.store_id', '=', $store_id)
                // ->whereBetween('invoice_date', [$request->start_date, $request->end_date])
                ->selectRaw('invoice_products.product_id ,  sum(total) AS r_total')
                ->select('invoice_products.*', 'products.*')
                ->groupBy('invoice_products.product_id')
                ->selectRaw('invoice_products.product_id ,  sum(qte) AS qte_total')
                ->get();
            foreach ($products as $product) {
                $totalSell = $totalSell + ($product->price * $product->qte_total);
                $totalMargeBrute = $totalMargeBrute + ($product->price - $product->promotion_price) * $product->qte_total;
            }
            $total = $products->count();

            return view('owner.repport.products', [
                'stores'         => auth()->user()->stores->pluck('name', 'id'),
                'products'       => $products,
                'total'          => $total,
                'totalSell'      => $totalSell,
                'totalMargeBrute' => $totalMargeBrute,
                'store_id' => $store_id,
            ]);
        } else {
            $products = ProductQuantiteDetails::join('products', 'product_quantite_details.product_id', '=', 'products.id')
                ->join('product_quantites', 'product_quantite_details.product_quantite_id', '=', 'product_quantites.id')
                ->where('product_quantite_details.store_id', $store_id)
                ->select('products.label', 'products.id', 'product_quantites.quantite', 'product_quantites.created_at')
                ->get();
            $result = collect();
            foreach ($products as $product) {
                $label = $product->label;

                if (!$result->has($label)) {
                    $result->put($label, collect());
                }

                $result->get($label)->push([
                    'quantite' => $product->quantite,
                    'created_at' => $product->created_at,
                ]);
            }
            $produit = [];
            $maxLength = 0;
            foreach ($result as $label => $details) {
                $lenght = count($details);
                if ($lenght > $maxLength) {
                    $maxLength = $lenght;
                }
                $totalQuantity = 0;
                $productData = ['product' => $label];

                $quantities = [];
                $dates = [];

                foreach ($details as $detail) {
                    $quantity = $detail['quantite'];
                    $totalQuantity += $quantity;

                    $quantities[] = $quantity;
                    $dates[] = $detail['created_at']->format('d/m/Y H:i');
                }

                for ($i = 0; $i < $maxLength; $i++) {
                    $productData['Quantité ' . ($i + 1)] = isset($quantities[$i]) ? $quantities[$i] : '';
                    $productData['Date d\'ajout ' . ($i + 1)] = isset($dates[$i]) ? $dates[$i] : '';
                }

                $productData['Total'] = $totalQuantity;

                $produit[] = $productData;
            }
            return view('owner.repport.productsTable2', [
                'stores' => auth()->user()->stores->pluck('name', 'id'),
                'length' => $maxLength,
                'products' => $produit,
                'store_id' => $store_id
            ]);
        }
    }

    public function productExcel($storeId)
    {
        return Excel::download(new ProductsDetails($storeId), 'products.xlsx');
    }
    public function productExcel2($storeId)
    {
        return Excel::download(new ProductsDetails2($storeId), 'products.xlsx');
    }

    public function getCampaignsList(Request $request)
    {
        $campaign_details = $this->campaignService->getOwnerCampaignList();
        return view('owner.campaigns.list', compact('campaign_details'));
    }

    public function getCampaignsDetails(Request $request, $type, $id)
    {
        $messages_details = $this->campaignService->getOwnerCampaignDetail($type, $id);

        return view('owner.campaigns.details', compact('messages_details', 'type'));
    }

    public function findCustomerByName(Request $request, $type, $name)
    {
        $customers_details = $this->campaignService->getCustomerByName($type, $name);
        return view('owner.campaigns.customers', compact('customers_details', 'type'));
    }

    public function getCampaigns(Request $request)
    {
        $data = [];


        if (!is_null($request->campaign) && $request->campaign == 0) {
            $type = 0;
            $data = CampaignDetail::where('store_id', '=', $request->store_id)
                ->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
        } else {
            $type = 1;
            $data = EmailCampaign::where('store_id', '=', $request->store_id)
                ->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
        }

        return view('owner.campaigns.rapport', [
            'stores'     => auth()->user()->stores->pluck('name', 'id'),
            'data'       => $data,
            'total'      => count($data),
            'type'       => $type
        ]);
    }
}
