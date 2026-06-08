<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class InvoiceIdorTest extends TestCase
{
    public function test_invoice_controller_uses_authorized_invoice_lookup()
    {
        $invoice = file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Web/InvoiceController.php'
        );

        $this->assertContains('AuthorizesInvoiceAccess', $invoice);
        $this->assertContains('findAuthorizedInvoice', $invoice);
        $this->assertContains('findAuthorizedStoreScopedModel(Customer::class', $invoice);
        $this->assertContains('validatedInvoicePayload', $invoice);
    }

    public function test_invoice_access_trait_scopes_by_store()
    {
        $trait = file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Concerns/AuthorizesInvoiceAccess.php'
        );

        $this->assertContains('findAuthorizedStoreScopedModel', $trait);
    }
}
