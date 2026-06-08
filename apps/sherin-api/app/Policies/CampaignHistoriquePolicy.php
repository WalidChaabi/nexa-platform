<?php

namespace App\Policies;

use App\Models\CampaignHistorique;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class CampaignHistoriquePolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CampaignHistorique $campaignHistorique): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($campaignHistorique));
    }
}
