<?php

namespace App\Policies\Concerns;

use App\Models\User;
use App\Support\Authorization\StoreAccess;

trait AuthorizesStoreResources
{
    protected function canAccessStore(User $user, int $storeId): bool
    {
        return StoreAccess::canAccessStore($user, $storeId);
    }

    /**
     * @param  object{store_id: int|null}  $resource
     */
    protected function resourceStoreId(object $resource): int
    {
        return (int) $resource->store_id;
    }
}
