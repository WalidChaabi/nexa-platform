<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use App\Support\Authorization\StoreAccess;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return StoreAccess::isAdmin($user);
    }
}
