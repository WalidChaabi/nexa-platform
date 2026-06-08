<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use App\models\Store;
use App\models\Table;
use App\Models\User;
use App\Services\CampaignService;
use App\Services\CustomerService;
use App\Services\InvoiceService;
use App\Services\CategorieService;
use App\Services\PosTokenService;
use App\Services\ProductService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use function Psy\debug;

class PosController extends Controller
{
    public $customerService;
    protected $productService;
    protected $CategorieService;
    protected $campaignService;
    protected $invoiceService;
    public function __construct()
    {
        $this->customerService = new CustomerService;
        $this->productService = new ProductService;
        $this->CategorieService = new CategorieService;
        $this->campaignService = new CampaignService;
        $this->invoiceService = new InvoiceService;
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
            $invoices = Invoice::where('store_id', '=', auth()->user()->store->id)->whereBetween('invoice_date', [$request->start_date, $request->end_date])->OrderBy('id', 'DESC')->get();
        } else {
            $invoices = Invoice::where('store_id', '=', auth()->user()->store->id)
                ->whereBetween('invoice_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->OrderBy('id', 'DESC')->get();
        }



        return view('employe.pos.list', compact('invoices'));
    }

    public function create($id = null)
    {
        $customers =  $this->customerService
            ->getSharedCustomer(auth()->user()->store->owner)->map(function ($customer) {
                return $customer->only(['id', 'phone', 'firstname', 'lastname']);
            });
        $products = $this->productService->index()->where('categorie_id', '!=', null)->groupBy('categorie_id');
        $categories = $this->CategorieService->all();
        if (!empty($id)) {
            $table = Table::findOrFail($id);
            return view('employe.pos.create', ['customers' => $customers, 'products' => $products, 'categories' => $categories, 'table' => $table]);
        } else {
            return view('employe.pos.create', [
                'customers' => $customers, 'products' => $products,
                'categories' => $categories
            ]);
        }
    }

    public function getProductByCategorieId(Request $request)
    {
        $product = Product::orderBy('id', 'DESC')
            ->where([['store_id', Auth::user()->store_id], ['categorie_id', '=', $request->categorie_id]])
            ->get();

        return $product;
    }

    public function store(Request $request)
    {

        $totalHt      = $this->productService->getTotalHt($request->productsId);
        $total        = $this->productService->getTotal($totalHt);
        $customer     = $this->customerService->getCustomerById($request->customerId);
        $useRedFinal  = $customer->points;
        $pointsWithoutDiscount = 0;
        $useRed = 0;
        if ($request->isUseReduction == true) {
            $total   = $this->productService->getTotalHtWithReduction($totalHt, $customer);
            $useRed  =  $this->invoiceService->updatePoints($total, $customer);
        } else {
            $pointsWithoutDiscount = $this->invoiceService->updatePointsWithoutBalance($total, $customer);
            $useRedFinal = $useRed = 0;
        }
        return [$total, $totalHt, $pointsWithoutDiscount, $useRedFinal, $useRed];
    }

    public function checkPassword(Request $request, PosTokenService $posTokenService)
    {
        $request->validate([
            'store_id' => 'required|integer|exists:stores,id',
            'token' => 'required|string|max:255',
        ]);

        $store = Store::FindOrFail($request->store_id);
        $user = User::findOrFail($store->user_id);

        if ($posTokenService->verify($user, $request->token)) {
            return Response()->json(['mes' => 'token is valid']);
        }

        return Response()->json(['mes' => 'token is not valid'], 403);
    }


    public function edit()
    {
        return 3;
    }


    // if(!empty($request->code)){
    //     $res =  $this->campaignService->getCampaignHistoriqueByCodeAndCustomerId($request->code, $customer->id);
    //     $total  = $request->total;
    // }
}
