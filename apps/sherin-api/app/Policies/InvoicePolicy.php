<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Policies\Concerns\AuthorizesStoreResources;

class InvoicePolicy
{
    use AuthorizesStoreResources;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($invoice));
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($invoice));
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($invoice));
    }

    public function cancel(User $user, Invoice $invoice): bool
    {
        return $this->canAccessStore($user, $this->resourceStoreId($invoice));
    }
}
