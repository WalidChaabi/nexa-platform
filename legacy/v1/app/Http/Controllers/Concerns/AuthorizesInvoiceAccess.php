<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Invoice;

trait AuthorizesInvoiceAccess
{
    use AuthorizesStoreScopedAccess;

    /**
     * Resolve an invoice the authenticated user is allowed to access.
     */
    protected function findAuthorizedInvoice($id): Invoice
    {
        return $this->findAuthorizedStoreScopedModel(Invoice::class, $id);
    }
}
