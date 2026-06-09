<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Customer::class);

        $query = Customer::query()
            ->with('store')
            ->orderByDesc('id');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where(function ($builder) use ($term): void {
                $builder
                    ->where('phone', 'like', $term)
                    ->orWhere('firstname', 'like', $term)
                    ->orWhere('lastname', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        $customers = $query->paginate($request->integer('per_page', 15));

        return CustomerResource::collection($customers);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::query()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        $customer->load('store');

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Customer $customer): CustomerResource
    {
        $this->authorize('view', $customer);

        $customer->load('store');

        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->validated());
        $customer->load('store');

        return new CustomerResource($customer);
    }

    public function destroy(Request $request, Customer $customer): JsonResponse
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return response()->json(['message' => 'Customer deleted.']);
    }
}
