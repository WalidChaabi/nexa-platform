<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class StoreScopedAuthorizationTest extends TestCase
{
    public function test_store_scoped_authorization_trait_exists()
    {
        $this->assertFileExists(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Concerns/AuthorizesStoreScopedAccess.php'
        );
    }

    public function test_idor_protected_controllers_use_store_scoped_authorization()
    {
        $root = dirname(__DIR__, 3);
        $directTraitControllers = [
            'CustomerController.php',
            'StoreController.php',
            'RdvController.php',
            'ProductController.php',
        ];

        foreach ($directTraitControllers as $controller) {
            $contents = file_get_contents($root . '/app/Http/Controllers/Web/' . $controller);
            $this->assertContains(
                'AuthorizesStoreScopedAccess',
                $contents,
                "Expected store-scoped authorization in {$controller}"
            );
        }

        $invoice = file_get_contents($root . '/app/Http/Controllers/Web/InvoiceController.php');
        $this->assertContains('AuthorizesInvoiceAccess', $invoice);
        $this->assertContains('findAuthorizedInvoice', $invoice);
    }

    public function test_customer_and_rdv_controllers_use_authorized_lookups()
    {
        $root = dirname(__DIR__, 3);

        $customer = file_get_contents($root . '/app/Http/Controllers/Web/CustomerController.php');
        $this->assertContains('findAuthorizedStoreScopedModel(Customer::class', $customer);

        $rdv = file_get_contents($root . '/app/Http/Controllers/Web/RdvController.php');
        $this->assertContains('findAuthorizedStoreScopedModel(Rdv::class', $rdv);
        $this->assertNotContains("Rdv::where('id',$rdv_id)", $rdv);
    }
}
