<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use App\Support\Authorization\StoreAccess;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function view(User $user, Activity $activity): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function create(User $user): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function update(User $user, Activity $activity): bool
    {
        return StoreAccess::isAdmin($user);
    }

    public function delete(User $user, Activity $activity): bool
    {
        return StoreAccess::isAdmin($user);
    }
}
