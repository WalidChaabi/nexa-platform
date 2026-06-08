<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stores\StoreStoreRequest;
use App\Http\Requests\Stores\UpdateStoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StoreController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Store::class);

        $query = Store::query()
            ->with('owner')
            ->orderByDesc('id');

        $this->applyListScope($query, $request->user());

        $stores = $query->paginate($request->integer('per_page', 15));

        return StoreResource::collection($stores);
    }

    public function store(StoreStoreRequest $request): JsonResponse
    {
        $user = $request->user();
        $ownerId = StoreAccess::isAdmin($user)
            ? (int) $request->input('user_id')
            : $user->id;

        $store = Store::query()->create([
            'user_id' => $ownerId,
            'activity_id' => $request->integer('activity_id', 0),
            'name' => $request->string('name')->toString(),
            'phone' => $request->input('phone', ''),
            'contact' => $request->input('contact', ''),
            'address' => $request->input('address'),
            'invoice_message' => $request->input('invoice_message'),
            'sender_id' => $request->input('sender_id', ''),
            'status' => $request->integer('status', 1),
        ]);

        $store->load('owner');

        return (new StoreResource($store))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Store $store): StoreResource
    {
        $this->authorize('view', $store);

        $store->load('owner');

        return new StoreResource($store);
    }

    public function update(UpdateStoreRequest $request, Store $store): StoreResource
    {
        $store->update($request->validated());
        $store->load('owner');

        return new StoreResource($store);
    }

    public function destroy(Request $request, Store $store): JsonResponse
    {
        $this->authorize('delete', $store);

        $store->delete();

        return response()->json(['message' => 'Store deleted.']);
    }

    private function applyListScope(\Illuminate\Database\Eloquent\Builder $query, \App\Models\User $user): void
    {
        match ($user->role()) {
            UserRole::Admin => null,
            UserRole::Owner => $query->where('user_id', $user->id),
            UserRole::Employer => $user->store_id
                ? $query->whereKey($user->store_id)
                : $query->whereRaw('1 = 0'),
        };
    }
}
