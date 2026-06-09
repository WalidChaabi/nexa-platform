<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class CategoryPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($category));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Category $category): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($category));
    }

    public function delete(User $user, Category $category): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($category));
    }
}
