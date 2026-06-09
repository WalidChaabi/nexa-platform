<?php

namespace Tests\Feature\Invoices;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_store_invoices(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $invoice = Invoice::factory()->forStore($store)->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/invoices')
            ->assertOk()
            ->assertJsonPath('data.0.id', $invoice->id);
    }

    public function test_employer_cannot_view_other_store_invoice(): void
    {
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $storeA->id]);
        $invoice = Invoice::factory()->forStore($storeB)->create();

        $this->actingAs($employer)
            ->getJson("/api/v1/invoices/{$invoice->id}")
            ->assertForbidden();
    }

    public function test_employer_can_create_invoice(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price' => 120,
        ]);

        $response = $this->actingAs($employer)
            ->postJson('/api/v1/invoices', [
                'store_id' => $store->id,
                'customer_id' => $customer->id,
                'payment_method' => 0,
                'paid_amount' => 120,
                'total' => 120,
                'total_ht' => 100,
                'products' => [
                    ['product_id' => $product->id, 'qte' => 1],
                ],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.total', 120)
            ->assertJsonPath('data.status', InvoiceStatus::Pending->value)
            ->assertJsonPath('data.items.0.product_id', $product->id);

        $this->assertDatabaseHas('invoices', [
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'employ_id' => $employer->id,
        ]);
    }

    public function test_employer_can_cancel_pending_invoice(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $invoice = Invoice::factory()->forStore($store)->create([
            'status' => InvoiceStatus::Pending->value,
        ]);

        $this->actingAs($employer)
            ->postJson("/api/v1/invoices/{$invoice->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', InvoiceStatus::Cancelled->value);
    }

    public function test_employer_can_fetch_invoice_print_payload(): void
    {
        $store = Store::factory()->create([
            'name' => 'Print Store',
            'invoice_message' => 'Merci pour votre visite.',
        ]);
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'label' => 'Brushing',
            'price' => 80,
        ]);
        $invoice = Invoice::factory()->forStore($store)->create();
        $invoice->items()->create([
            'product_id' => $product->id,
            'qte' => 2,
            'price' => 80,
        ]);

        $this->actingAs($employer)
            ->getJson("/api/v1/invoices/{$invoice->id}/print")
            ->assertOk()
            ->assertJsonPath('data.id', $invoice->id)
            ->assertJsonPath('data.store.name', 'Print Store')
            ->assertJsonPath('data.store.invoice_message', 'Merci pour votre visite.')
            ->assertJsonPath('data.items.0.label', 'Brushing')
            ->assertJsonPath('data.items.0.qte', 2);
    }

    public function test_cannot_create_invoice_for_inaccessible_store(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $otherStore->id]);
        $product = Product::factory()->create(['store_id' => $otherStore->id]);

        $this->actingAs($employer)
            ->postJson('/api/v1/invoices', [
                'store_id' => $otherStore->id,
                'customer_id' => $customer->id,
                'total' => 50,
                'products' => [
                    ['product_id' => $product->id, 'qte' => 1],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    public function test_owner_can_list_invoices_for_owned_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id, 'name' => 'Owner Store']);
        $invoice = Invoice::factory()->forStore($store)->create();
        Invoice::factory()->forStore(Store::factory()->create())->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/invoices')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $invoice->id)
            ->assertJsonPath('data.0.store.name', 'Owner Store');
    }

    public function test_owner_cannot_view_other_store_invoice(): void
    {
        $owner = User::factory()->owner()->create();
        $invoice = Invoice::factory()->forStore(Store::factory()->create())->create();

        $this->actingAs($owner)
            ->getJson("/api/v1/invoices/{$invoice->id}")
            ->assertForbidden();
    }
}
