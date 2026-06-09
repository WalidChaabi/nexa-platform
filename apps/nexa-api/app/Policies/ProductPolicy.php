<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class ProductPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($product));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($product));
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($product));
    }
}
