<?php

namespace App\Policies;

use App\Models\CrossProduct;
use App\Models\User;
use App\Support\Authorization\StoreAccess;

class CrossProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CrossProduct $crossProduct): bool
    {
        return $this->canAccessProductStore($user, $crossProduct);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CrossProduct $crossProduct): bool
    {
        return $this->canAccessProductStore($user, $crossProduct);
    }

    public function delete(User $user, CrossProduct $crossProduct): bool
    {
        return $this->canAccessProductStore($user, $crossProduct);
    }

    private function canAccessProductStore(User $user, CrossProduct $crossProduct): bool
    {
        $crossProduct->loadMissing('product');

        if (! $crossProduct->product) {
            return false;
        }

        return StoreAccess::canAccessStore($user, (int) $crossProduct->product->store_id);
    }
}
