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
    public function Message($action, $dataProduct, $changes)
    {
        $userName = e($dataProduct['userName']);
        $productName = e($dataProduct['productName']);
        $actionLabel = e($action);

        if ($action === 'créer') {
            $date = e($dataProduct['date']);

            return "l'utilisateur <span style=\"color: blue;\">{$userName}</span> a <span style=\"color: green;\">{$actionLabel}</span> the product {$productName} in {$date}";
        }

        $text = "the user {$userName} had {$actionLabel} the product {$productName}";

        if (!empty($changes)) {
            $text .= implode(', ', $changes);
        }

        return $text;
    }
}
