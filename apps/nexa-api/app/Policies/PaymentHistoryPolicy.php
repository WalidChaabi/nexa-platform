<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Support\Authorization\StoreAccess;

class PaymentHistoryPolicy
{
    public function viewAny(User $user): bool
    {
        return StoreAccess::isAdmin($user) || $user->role() === UserRole::Owner;
    }

    public function view(User $user, PaymentHistory $paymentHistory): bool
    {
        if (StoreAccess::isAdmin($user)) {
            return true;
        }

        return $user->role() === UserRole::Owner
            && (int) $paymentHistory->owner_id === $user->id;
    }

    public function checkout(User $user): bool
    {
        return $user->role() === UserRole::Owner;
    }
}
