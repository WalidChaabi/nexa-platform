<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RdvStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rdvs\StoreRdvRequest;
use App\Http\Requests\Rdvs\UpdateRdvRequest;
use App\Http\Resources\RdvResource;
use App\Models\Customer;
use App\Models\Rdv;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RdvController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Rdv::class);

        $query = Rdv::query()
            ->with(['customer', 'store'])
            ->where('status', RdvStatus::Active->value)
            ->orderBy('start');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('from')) {
            $query->where('start', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->where('start', '<=', $request->date('to'));
        }

        $appointments = $query->paginate($request->integer('per_page', 50));

        return RdvResource::collection($appointments);
    }

    public function store(StoreRdvRequest $request): JsonResponse
    {
        $customer = Customer::query()->findOrFail((int) $request->input('customer_id'));

        $rdv = Rdv::query()->create([
            'customer_id' => $customer->id,
            'store_id' => $customer->store_id,
            'title' => $request->string('title')->toString(),
            'event_id' => $request->string('event_id')->toString(),
            'start' => $request->date('start'),
            'status' => RdvStatus::Active->value,
        ]);

        $rdv->load(['customer', 'store']);

        return (new RdvResource($rdv))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Rdv $rdv): RdvResource
    {
        $this->authorize('view', $rdv);

        $rdv->load(['customer', 'store']);

        return new RdvResource($rdv);
    }

    public function update(UpdateRdvRequest $request, Rdv $rdv): RdvResource
    {
        $rdv->update($request->validated());
        $rdv->load(['customer', 'store']);

        return new RdvResource($rdv);
    }

    public function destroy(Request $request, Rdv $rdv): JsonResponse
    {
        $this->authorize('delete', $rdv);

        $rdv->delete();

        return response()->json(['message' => 'Appointment deleted.']);
    }
}
