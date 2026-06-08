<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignHistoriqueResource;
use App\Models\CampaignHistorique;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CampaignHistoriqueController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', CampaignHistorique::class);

        $query = CampaignHistorique::query()
            ->with(['customer', 'store'])
            ->orderByDesc('start_date');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('is_payed')) {
            $query->where('is_payed', (int) $request->input('is_payed'));
        }

        $records = $query->paginate($request->integer('per_page', 15));

        return CampaignHistoriqueResource::collection($records);
    }
}
