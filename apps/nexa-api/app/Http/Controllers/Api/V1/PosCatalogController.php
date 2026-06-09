<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Models\Customer;
use App\Models\Product;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosCatalogController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ]);

        $storeId = (int) $validated['store_id'];

        if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
            abort(403, 'You do not have access to this store.');
        }

        $this->authorize('viewAny', Product::class);
        $this->authorize('viewAny', Customer::class);

        $customers = Customer::query()
            ->where('store_id', $storeId)
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        $products = Product::query()
            ->where('store_id', $storeId)
            ->orderBy('label')
            ->get();

        return response()->json([
            'store_id' => $storeId,
            'customers' => CustomerResource::collection($customers),
            'products' => ProductResource::collection($products),
        ]);
    }
}
