<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SmsCrossSellingResource;
use App\Models\SmsCrossSelling;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SmsCrossSellingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SmsCrossSelling::class);

        $query = SmsCrossSelling::query()
            ->with(['store', 'product'])
            ->orderByDesc('sent_date');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status'));
        }

        $records = $query->paginate($request->integer('per_page', 15));

        return SmsCrossSellingResource::collection($records);
    }
}
