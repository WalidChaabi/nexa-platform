<?php

namespace App\Policies;

use App\Models\Rdv;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class RdvPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Rdv $rdv): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($rdv));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Rdv $rdv): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($rdv));
    }

    public function delete(User $user, Rdv $rdv): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($rdv));
    }
}
