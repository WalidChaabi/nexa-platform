<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class CampaignPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaign));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaign));
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaign));
    }
}
