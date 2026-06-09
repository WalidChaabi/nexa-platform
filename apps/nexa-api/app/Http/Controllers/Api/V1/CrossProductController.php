<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrossSelling\StoreCrossProductRequest;
use App\Http\Requests\CrossSelling\UpdateCrossProductRequest;
use App\Http\Resources\CrossProductResource;
use App\Models\CrossProduct;
use App\Models\Product;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CrossProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', CrossProduct::class);

        $query = CrossProduct::query()
            ->with(['product.store'])
            ->orderByDesc('id');

        if (! StoreAccess::isAdmin($request->user())) {
            $storeIds = StoreAccess::accessibleStoreIds($request->user());
            $query->whereHas('product', fn ($productQuery) => $productQuery->whereIn('store_id', $storeIds ?: [-1]));
        }

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->whereHas('product', fn ($productQuery) => $productQuery->where('store_id', $storeId));
        }

        $rules = $query->paginate($request->integer('per_page', 15));

        return CrossProductResource::collection($rules);
    }

    public function store(StoreCrossProductRequest $request): JsonResponse
    {
        $crossProduct = CrossProduct::query()->create($request->validated());
        $crossProduct->load(['product.store']);

        return (new CrossProductResource($crossProduct))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, CrossProduct $crossProduct): CrossProductResource
    {
        $this->authorize('view', $crossProduct);

        $crossProduct->load(['product.store']);

        return new CrossProductResource($crossProduct);
    }

    public function update(UpdateCrossProductRequest $request, CrossProduct $crossProduct): CrossProductResource
    {
        if ($request->filled('product_id')) {
            $productId = (int) $request->input('product_id');
            $storeId = (int) Product::query()->whereKey($productId)->value('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this product.');
            }
        }

        $crossProduct->update($request->validated());
        $crossProduct->load(['product.store']);

        return new CrossProductResource($crossProduct);
    }

    public function destroy(Request $request, CrossProduct $crossProduct): JsonResponse
    {
        $this->authorize('delete', $crossProduct);

        $crossProduct->delete();

        return response()->json(['message' => 'Cross-selling rule deleted.']);
    }
}
