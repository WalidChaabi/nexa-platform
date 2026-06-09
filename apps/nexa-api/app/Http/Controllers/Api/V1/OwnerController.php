<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owners\StoreOwnerRequest;
use App\Http\Requests\Owners\UpdateOwnerRequest;
use App\Http\Resources\OwnerResource;
use App\Models\User;
use App\Services\OwnerAccountService;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OwnerController extends Controller
{
    public function __construct(
        private readonly OwnerAccountService $ownerAccounts,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->assertAdmin($request);

        $owners = User::query()
            ->where('is_admin', UserRole::Owner->value)
            ->withCount('stores')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 50));

        return OwnerResource::collection($owners);
    }

    public function store(StoreOwnerRequest $request): JsonResponse
    {
        $owner = User::query()->create([
            'firstname' => $request->string('firstname')->toString(),
            'lastname' => $request->string('lastname')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'password' => $request->string('password')->toString(),
            'allow_share' => $request->boolean('allow_share') ? 1 : 0,
            'is_admin' => UserRole::Owner->value,
            'status' => 1,
            'is_active' => 1,
        ]);

        return (new OwnerResource($owner))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, User $owner): OwnerResource
    {
        $this->assertAdmin($request);
        $this->assertOwnerAccount($owner);

        $owner->loadCount('stores');

        return new OwnerResource($owner);
    }

    public function update(UpdateOwnerRequest $request, User $owner): OwnerResource
    {
        $this->assertOwnerAccount($owner);

        $payload = $request->safe()->only(['firstname', 'lastname', 'phone']);

        if ($request->has('allow_share')) {
            $payload['allow_share'] = $request->boolean('allow_share') ? 1 : 0;
        }

        if ($request->filled('password')) {
            $payload['password'] = $request->string('password')->toString();
        }

        $owner->update($payload);

        return new OwnerResource($owner);
    }

    public function activate(Request $request, User $owner): OwnerResource
    {
        $this->assertAdmin($request);
        $this->assertOwnerAccount($owner);

        $this->ownerAccounts->activate($owner);

        return new OwnerResource($owner->fresh());
    }

    public function deactivate(Request $request, User $owner): OwnerResource
    {
        $this->assertAdmin($request);
        $this->assertOwnerAccount($owner);

        $this->ownerAccounts->deactivate($owner);

        return new OwnerResource($owner->fresh());
    }

    private function assertAdmin(Request $request): void
    {
        if (! StoreAccess::isAdmin($request->user())) {
            abort(403, 'Forbidden.');
        }
    }

    private function assertOwnerAccount(User $owner): void
    {
        if ((int) $owner->is_admin !== UserRole::Owner->value) {
            abort(404);
        }
    }
}
