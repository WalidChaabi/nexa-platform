<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductService
{

    
    public static function index()
    {
        if (auth()->user()->is_admin == 1)
            $product = Product::orderBy('id', 'DESC')->get();
        else
            $product = Product::orderBy('id', 'DESC')->where('store_id', Auth::user()->store_id)->get();
        return $product;
    }


    public static function store($request)
    {
        $act = Product::create(
            [
                'store_id' => $request->store_id,
                'label' => $request->label,
                'prix' => $request->prix,
                'category_id' => $request->cat_id
            ]
        );
        if ($act) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function update($request, $id)
    {
        $product_store = Product::where('id', '=', $id)->first()->store_id;
        $store_id = (auth()->user()->is_admin == 1) ? $request->store_id : Auth()->user()->store_id;
        $product = Product::where('id', '=', $id)->where('store_id', $product_store)->update([
            'label' => $request->label,
            'prix' => $request->prix,
            'category_id' => $request->cat_id
        ]);

        if ($product == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getByIds(array $productIds = [])
    {
        return Product::whereIn('id', $productIds)->get();
    }

    public function getProductById(int $id)
    {
        return Product::find($id);
    }

    public function getTotalHt(array $productsId)
    {
        $total = 0;
        foreach ($productsId as $productId) {
            $total = $total + $this->getProductById($productId)->price;
        }
        return $total;
    }

    public function getTotal($totalHt)
    {
        return  $totalHt + ($totalHt *  auth()->user()->store->tva  / 100);
    }

    public function getTotalHtWithReduction($totalHt, Customer $customer)
    {
        $totalHt = $totalHt  - $customer->points;
        return  ceil($totalHt * (1 + (auth()->user()->store->tva  / 100)));
    }

    public function getCrossSelling()
    {
        return Product::where('is_cross_selling', 1)->select('id', 'days')->get();
    }

    public function getProductsForCrossSelling(int $store_id)
    {
        return Product::where('store_id', $store_id)->get();
    }
    public function Message($action,$dataProduct,$changes){
        if($action == 'créer'){
            $textStore = 'l\'utilisateur <span style="color: blue;">[userName]</span> a <span style="color: green;">[action]</span> the product [productName] in [date]';
            $text = str_replace(['[userName]','[action]','[productName]','[date]'] ,
                [$dataProduct['userName'],$action,$dataProduct['productName'],$dataProduct['date']], $textStore);
        }
        else {
            $textUpdate = 'the user [userName] had [action] the product [productName]';
            $text = str_replace(['[userName]','[action]','[productName]'] ,
                [$dataProduct['userName'],$action,$dataProduct['productName']], $textUpdate);
            $text = $text . implode(", ", $changes);
        }
        return $text;
    }
}
