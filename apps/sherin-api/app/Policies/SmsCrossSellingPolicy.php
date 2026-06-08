<?php

namespace App\Policies;

use App\Models\SmsCrossSelling;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class SmsCrossSellingPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SmsCrossSelling $smsCrossSelling): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($smsCrossSelling));
    }
}
