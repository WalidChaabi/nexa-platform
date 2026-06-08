<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class CheckoutPaymentContextTest extends TestCase
{
    public function test_checkout_service_resolves_payment_without_session_auth()
    {
        $root = dirname(__DIR__, 3);
        $checkoutService = file_get_contents($root . '/app/Services/CheckoutService.php');
        $controller = file_get_contents($root . '/app/Http/Controllers/Web/CheckoutController.php');

        $this->assertContains('resolvePaymentContext', $checkoutService);
        $this->assertContains('cachePendingPayment', $checkoutService);
        $this->assertContains('clearPendingPayment', $checkoutService);
        $this->assertContains('cmi_pending_payment:', $checkoutService);

        $this->assertContains('resolvePaymentContext($request->oid)', $controller);
        $this->assertNotContains(
            'Event::dispatch(new PaiedSms(auth()->user()->stores',
            $controller
        );
    }

    public function test_detail_invoice_listener_clears_session_by_owner_id_from_payload()
    {
        $listener = file_get_contents(
            dirname(__DIR__, 3) . '/app/Listeners/DetailInvoiceListener.php'
        );

        $this->assertContains("invoiceDetail['owner_id']", $listener);
    }

    public function test_birthday_scheduler_is_enabled_with_casablanca_timezone()
    {
        $kernel = file_get_contents(dirname(__DIR__, 3) . '/app/Console/Kernel.php');

        $this->assertContains("command('birthday:campaign')", $kernel);
        $this->assertContains("timezone('Africa/Casablanca')", $kernel);
    }

    public function test_invoice_controller_scopes_remaining_write_paths()
    {
        $invoice = file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Web/InvoiceController.php'
        );

        $this->assertContains('findAuthorizedStoreScopedModel(Customer::class', $invoice);
        $this->assertContains('findAuthorizedInvoice($request->invoice_id)', $invoice);
        $this->assertContains('authorizeStoreIdFromRequest($request->recherch_store_id)', $invoice);
        $this->assertContains('findAuthorizedStore($id)', $invoice);
    }
}
