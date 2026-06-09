<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employers\StoreEmployerRequest;
use App\Http\Requests\Employers\UpdateEmployerRequest;
use App\Http\Resources\EmployerResource;
use App\Models\User;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->assertCanManageTeam($request);

        $query = User::query()
            ->where('is_admin', UserRole::Employer->value)
            ->with('store:id,name')
            ->orderByDesc('id');

        StoreAccess::applyStoreScope($query, $request->user(), 'store_id');

        if ($request->filled('store_id')) {
            $storeId = $request->integer('store_id');
            if (StoreAccess::canAccessStore($request->user(), $storeId)) {
                $query->where('store_id', $storeId);
            }
        }

        $employers = $query->paginate($request->integer('per_page', 50));

        return EmployerResource::collection($employers);
    }

    public function store(StoreEmployerRequest $request): JsonResponse
    {
        $employer = User::query()->create([
            'store_id' => $request->integer('store_id'),
            'firstname' => $request->string('firstname')->toString(),
            'lastname' => $request->string('lastname')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'password' => $request->string('password')->toString(),
            'allow_share' => $request->boolean('allow_share') ? 1 : 0,
            'is_admin' => UserRole::Employer->value,
            'status' => 1,
            'is_active' => 1,
        ]);

        $employer->load('store:id,name');

        return (new EmployerResource($employer))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, User $employer): EmployerResource
    {
        $this->assertEmployerAccount($employer);
        $this->assertCanManageEmployer($request, $employer);

        $employer->load('store:id,name');

        return new EmployerResource($employer);
    }

    public function update(UpdateEmployerRequest $request, User $employer): EmployerResource
    {
        $this->assertEmployerAccount($employer);

        $payload = $request->safe()->only(['firstname', 'lastname', 'phone', 'store_id']);

        if ($request->has('allow_share')) {
            $payload['allow_share'] = $request->boolean('allow_share') ? 1 : 0;
        }

        if ($request->filled('password')) {
            $payload['password'] = $request->string('password')->toString();
        }

        $employer->update($payload);
        $employer->load('store:id,name');

        return new EmployerResource($employer);
    }

    public function activate(Request $request, User $employer): EmployerResource
    {
        $this->assertEmployerAccount($employer);
        $this->assertCanManageEmployer($request, $employer);

        $employer->update(['is_active' => 1]);

        $employer->load('store:id,name');

        return new EmployerResource($employer);
    }

    public function deactivate(Request $request, User $employer): EmployerResource
    {
        $this->assertEmployerAccount($employer);
        $this->assertCanManageEmployer($request, $employer);

        $employer->update(['is_active' => 0]);

        $employer->load('store:id,name');

        return new EmployerResource($employer);
    }

    private function assertCanManageTeam(Request $request): void
    {
        $user = $request->user();

        if (! $user || (! StoreAccess::isAdmin($user) && $user->role() !== UserRole::Owner)) {
            abort(403, 'Forbidden.');
        }
    }

    private function assertCanManageEmployer(Request $request, User $employer): void
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Forbidden.');
        }

        if (StoreAccess::isAdmin($user)) {
            return;
        }

        if ($user->role() !== UserRole::Owner || ! $employer->store_id) {
            abort(403, 'Forbidden.');
        }

        if (! StoreAccess::canAccessStore($user, (int) $employer->store_id)) {
            abort(403, 'Forbidden.');
        }
    }

    private function assertEmployerAccount(User $employer): void
    {
        if ((int) $employer->is_admin !== UserRole::Employer->value) {
            abort(404);
        }
    }
}
