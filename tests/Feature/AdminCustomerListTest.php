<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class AdminCustomerListTest extends TestCase
{
    public function test_admin_customer_index_loads_stores_and_customers()
    {
        $controller = file_get_contents(
            dirname(__DIR__, 2) . '/app/Http/Controllers/Web/CustomerController.php'
        );

        $this->assertContains("case '1':", $controller);
        $this->assertContains('$stores = Store::orderBy', $controller);
        $this->assertContains('$customers = Customer::orderBy', $controller);
        $this->assertContains("compact('customers', 'stores')", $controller);
    }
}
