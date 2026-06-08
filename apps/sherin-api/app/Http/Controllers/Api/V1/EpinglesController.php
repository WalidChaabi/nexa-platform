<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpinglesResource;
use App\Models\Epingles;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EpinglesController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Epingles::query()->orderBy('name');

        if (! StoreAccess::isAdmin($request->user())) {
            $storeIds = StoreAccess::accessibleStoreIds($request->user());
            $query->whereIn('store_id', $storeIds ?: [-1]);
        }

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        return EpinglesResource::collection($query->get());
    }
}
