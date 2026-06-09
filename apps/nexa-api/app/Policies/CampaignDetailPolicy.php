<?php

namespace App\Policies;

use App\Models\CampaignDetail;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class CampaignDetailPolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CampaignDetail $campaignDetail): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaignDetail));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CampaignDetail $campaignDetail): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaignDetail));
    }

    public function delete(User $user, CampaignDetail $campaignDetail): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaignDetail));
    }
}
