<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Invoice;

trait AuthorizesInvoiceAccess
{
    /**
     * Resolve an invoice the authenticated user is allowed to access.
     */
    protected function findAuthorizedInvoice($id): Invoice
    {
        $user = auth()->user();
        $query = Invoice::where('id', $id);

        if ((int) $user->is_admin === 1) {
            return $query->firstOrFail();
        }

        if ((int) $user->is_admin === 2) {
            $storeIds = $user->stores()->pluck('id')->toArray();

            return $query->whereIn('store_id', $storeIds)->firstOrFail();
        }

        return $query->where('store_id', $user->store_id)->firstOrFail();
    }
}
