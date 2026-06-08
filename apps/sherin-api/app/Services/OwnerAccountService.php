<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;

class OwnerAccountService
{
    public function deactivate(User $owner): void
    {
        $storeIds = $owner->stores()->pluck('id');

        $owner->update(['is_active' => 0]);

        if ($storeIds->isEmpty()) {
            return;
        }

        User::query()
            ->whereIn('store_id', $storeIds)
            ->where('is_admin', UserRole::Employer->value)
            ->update(['is_active' => 0]);
    }

    public function activate(User $owner): void
    {
        $storeIds = $owner->stores()->pluck('id');

        $owner->update(['is_active' => 1]);

        if ($storeIds->isEmpty()) {
            return;
        }

        User::query()
            ->whereIn('store_id', $storeIds)
            ->where('is_admin', UserRole::Employer->value)
            ->update(['is_active' => 1]);
    }
}
