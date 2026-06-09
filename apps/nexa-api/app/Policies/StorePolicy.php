<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;
use App\Support\Authorization\StoreAccess;

class StorePolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Store $store): bool
    {
        return $this->canAccessStore($user, $store->id);
    }

    public function create(User $user): bool
    {
        return StoreAccess::isAdmin($user) || $user->role() === UserRole::Owner;
    }

    public function update(User $user, Store $store): bool
    {
        return $this->canAccessStore($user, $store->id);
    }

    public function delete(User $user, Store $store): bool
    {
        return StoreAccess::isAdmin($user);
    }
}
