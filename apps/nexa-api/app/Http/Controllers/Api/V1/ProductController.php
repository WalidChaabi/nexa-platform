<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ImportProductsRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductImportService;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::query()
            ->with(['store', 'category'])
            ->orderBy('label');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', (int) $request->input('categorie_id'));
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where('label', 'like', $term);
        }

        $products = $query->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function import(
        ImportProductsRequest $request,
        ProductImportService $importService,
    ): JsonResponse {
        $file = $request->file('file');
        $path = $file?->getRealPath();

        if ($path === false || $path === null) {
            abort(422, 'Could not read the uploaded file.');
        }

        $result = $importService->import((int) $request->input('store_id'), $path);

        return response()->json($result);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $price = (float) $request->input('price');
        $promotionPrice = $request->filled('promotion_price')
            ? (float) $request->input('promotion_price')
            : $price;

        $product = Product::query()->create([
            'store_id' => (int) $request->input('store_id'),
            'label' => $request->string('label')->toString(),
            'price' => $price,
            'promotion_price' => $promotionPrice,
            'quantite' => $request->integer('quantite', 0),
            'categorie_id' => $request->input('categorie_id'),
        ]);

        $product->load(['store', 'category']);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Product $product): ProductResource
    {
        $this->authorize('view', $product);

        $product->load(['store', 'category']);

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());
        $product->load(['store', 'category']);

        return new ProductResource($product);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}
