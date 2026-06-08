<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AuthorizesStoreScopedAccess;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductQuantite;
use App\Models\ProductHestorique;
use App\Models\ProductQuantiteDetails;
use App\Imports\ProductsImport;
use App\Services\ProductService;
use App\Support\SecureImageUpload;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    use AuthorizesStoreScopedAccess;

    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }
    public function index($items = 25)
    {
        if (auth()->user()->is_admin == 1) {
            if (isset($_GET['storeId']))
                $products = Product::OrderBy('id', 'DESC')->where('store_id', $_GET['storeId'])->get();
            else
                $products = collect([]);
            $stores = Store::where('status', '=', 1)->get();
            return view('admin.products.list', compact('products', 'stores'));
        } elseif (auth()->user()->is_admin == 2) {

            $stores = Store::where('status', '=', 1)->where('user_id', '=', auth()->user()->id)->orderBy('id', 'DESC')->paginate($items);
            $stores_id = [];
            foreach ($stores as $str)
                array_push($stores_id, $str->id);
            $products = Product::whereIn('store_id', $stores_id)->orderBy('id', 'DESC')->get();
            return view('owner.products.list', compact('products', 'stores'));
        } elseif (auth()->user()->is_admin == 3) {
            $products = Product::where('store_id', '=', auth()->user()->store_id)->orderBy('id', 'DESC')->get();
            $stores = Store::where('id', '=', auth()->user()->store_id)->get();
            return view('employe.products.list', compact('products', 'stores'));
        }
    }

    public function getPrice(Request $request)
    {
        return $this->findAuthorizedStoreScopedModel(Product::class, $request->productId);
    }
    
    public function getProductByCatagorei(Request $request, $id)
    {
        $store_id = auth()->user()->store_id;
        $products = Product::where([['store_id', '=', $store_id], ['categorie_id', $id]])->orderBy('id', 'DESC')->get();
        $stores = Store::where('id', '=', $store_id)->get();
        return view('employe.products.list', compact('products', 'stores'));
    }

    public function create()
    {
        //
    }
    public function productInCategorie(Request $request)
    {
        $action = 'store';
        is_null($request['quantite']) ? $request['quantite'] = 100 : $request['quantite'];
        $request->validate([
            'store_id' => '',
            'categorie_id' => '',
            'label' => '',
            'price' => '',
            'promotion_price' => '',
            'quantite' => '',
        ]);
        $this->authorizeStoreIdFromRequest($request->store_id);
        $product = Product::create($request->only([
            'store_id', 'categorie_id', 'label', 'price', 'promotion_price', 'quantite',
        ]));
        if ($request->hasFile('image')) {
            $request->validate(['image' => SecureImageUpload::rules()]);
            $filename = SecureImageUpload::store(
                $request->file('image'),
                'uploads/product/images/' . $product->id
            );
            $product->image = $filename;
            $product->save();
        }
        $dataProduct = [
            'userName' => (auth()->user()->firstname || auth()->user()->lastname)
                ? (auth()->user()->firstname ?? '') . ' ' . (auth()->user()->lastname ?? '')
                : 'anonyme',
            'productName' => $product->label,
            'date' => $product->created_at,
        ];
        $message = $this->productService->Message($action,$dataProduct,null);
        ProductHestorique::create([
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'product_id' => $product->id, 
            "notification" => $message,
        ]);
        return back();
    }

    public function store(Request $request)
    {
        $this->authorizeStoreIdFromRequest($request->store_id);
        $action = 'créer';
        $product = Product::create([
            'store_id' => $request->store_id,
            'label' => $request->label,
            'price' => $request->price,
            'promotion_price' => $request->promotion_price,
            'quantite' => $request->quantite
        ]);
        $dataProduct = [
            'userName' => (auth()->user()->firstname || auth()->user()->lastname) 
                ? (auth()->user()->firstname ?? '') . ' ' . (auth()->user()->lastname ?? '') 
                : 'anonyme',
            'productName' => $product->label, 
            'date' => $product->created_at, 
        ];
        $message = $this->productService->Message($action,$dataProduct,null);
        ProductHestorique::create([
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'product_id' => $product->id, 
            "notification" => $message,
        ]);

        if (auth()->user()->is_admin == 1)
            return redirect()->route('admin.products');
        elseif (auth()->user()->is_admin == 2)
            return redirect()->route('owner.products');
    }

    public function show($id)
    {
        //
    }
    public function addQuantite(Request $request)
    {
        // dd($request);
        $product = $this->findAuthorizedStoreScopedModel(Product::class, $request->product_id);
        $this->authorizeStoreIdFromRequest($request->store_id);
        $quantite = (int) $request->quantite; // Convert to integer
        $sup_Qt = (int) $request->sup_Qt; // Convert to integer
        
        $product->update(['quantite' => $quantite + $sup_Qt]);
        $productQuantite = ProductQuantite::create([
            "quantite" => $sup_Qt,
        ]);
        ProductQuantiteDetails::create([
            'store_id' => $request->store_id,
            'product_id' => $product->id, 
            "product_quantite_id" => $productQuantite->id,
        ]);

        return $product->quantite;
    }
    public function changeQuantite(Request $request)
    {
        $oldProduct = $this->findAuthorizedStoreScopedModel(Product::class, $request->productId);
        $product = $oldProduct;
        $product->update(['quantite' => $request->quantite]);
        $date = Carbon::now()->format('y-m-d H:i:s');
        $userName = (auth()->user()->firstname || auth()->user()->lastname) 
        ? (auth()->user()->firstname ?? '') . ' ' . (auth()->user()->lastname ?? '') 
        : 'anonyme';

        $message = "l'utilisateur <span style=\"color: blue;\">" . e($userName) . "</span> a <span style=\"color: green;\">changé</span> le produit <span style=\"color: blue;\">" . e($product->label) . "</span> quantité de <span style=\"color: red;\">" . e($oldProduct->quantite) . "</span> a <span style=\"color: blue;\">" . e($product->quantite) . "</span> a " . e($date);
        ProductHestorique::create([
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'product_id' => $product->id, 
            "notification" => $message,
        ]);
        return $product->quantite;
    }

    public function edit($id)
    {
        $product = $this->findAuthorizedStoreScopedModel(Product::class, $id);
        $stores = Store::where('status', '=', 1)->get();

        return view('admin.products.edit', compact('product', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $action = 'changé';
        $oldProduct = $this->findAuthorizedStoreScopedModel(Product::class, $id);
        $product = $oldProduct;
        $attributes = $request->only(['label', 'price', 'promotion_price', 'quantite', 'categorie_id']);
        if ($request->filled('store_id')) {
            $this->authorizeStoreIdFromRequest($request->store_id);
            $attributes['store_id'] = $request->store_id;
        }
        $product->update($attributes);

        if ($request->hasFile('image')) {
            $request->validate(['image' => SecureImageUpload::rules()]);
            $filename = SecureImageUpload::store(
                $request->file('image'),
                'uploads/product/images/' . $id
            );
            $product->image = $filename;
            $product->save();
        }
        $changes = [];
        if ($oldProduct->label != $product->label) {
            $changes[] = ' Label de <span style="color: red;">' . e($oldProduct->label) . '</span> a <span style="color: blue;">' . e($product->label) . '</span>';
        }
        if ($oldProduct->price != $product->price) {
            $changes[] = 'Prix de <span style="color: red;">' . e($oldProduct->price) . '</span> a <span style="color: blue;">' . e($product->price) . '</span>';
        }
        if ($oldProduct->quantite != $product->quantite) {
            $changes[] = 'Quantite de <span style="color: red;">' . e($oldProduct->quantite) . '</span> a <span style="color: blue;">' . e($product->quantite) . '</span>';
        }
        $dataProduct = [
            'userName' => (auth()->user()->firstname || auth()->user()->lastname) 
                ? (auth()->user()->firstname ?? '') . ' ' . (auth()->user()->lastname ?? '') 
                : 'anonyme',
            'productName' => $product->label, 
        ];
        $changes[] = 'a ' . e($product->updated_at);
        $message = $this->productService->Message($action,$dataProduct,$changes);
        ProductHestorique::create([
            'store_id' => $request->store_id,
            'user_id' => auth()->user()->id,
            'product_id' => $product->id, 
            "notification" => $message,
        ]);
        if (auth()->user()->is_admin == 1)
            return redirect(route('admin.products') . '?storeId=' . $product->store_id);
        else if(auth()->user()->is_admin == 2)
            return redirect()->route('owner.products');
        else 
            return back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'products' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'store_id' => 'required|integer',
        ]);
        $this->authorizeStoreIdFromRequest($request->store_id);

        $path1 = $request->file('products')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        $store_id = $request->store_id;
        Excel::import(new ProductsImport($store_id), $path);
        // return redirect()->route('owner.products');
        return back();
    }

    protected function move_files($file, $id)
    {
        return SecureImageUpload::store($file, 'uploads/products/' . $id);
    }
    public function historiqueProduits()
    {
        $product_hestoriques = ProductHestorique::join('stores','product_hestoriques.store_id','=','stores.id')
            ->where([
                ['stores.status', '=', 1],
                ['stores.user_id', '=', auth()->user()->id]
            ])
            ->select('product_hestoriques.user_id','product_hestoriques.store_id','product_hestoriques.product_id',
                'product_hestoriques.notification','product_hestoriques.id')
            ->orderBy('product_hestoriques.id', 'Desc')
            ->get();
        return view('owner.products.hestorique',compact('product_hestoriques'));
    }
}
