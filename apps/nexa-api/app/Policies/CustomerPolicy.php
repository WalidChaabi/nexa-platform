<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class CustomerPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($customer));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($customer));
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($customer));
    }
}
