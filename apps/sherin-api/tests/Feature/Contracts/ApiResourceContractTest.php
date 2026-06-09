<?php

namespace Tests\Feature\Contracts;

use App\Models\Customer;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResourceContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_resource_exposes_stable_contract_fields(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        $response = $this->actingAs($employer)
            ->postJson('/api/v1/invoices', [
                'store_id' => $store->id,
                'customer_id' => $customer->id,
                'payment_method' => 0,
                'paid_amount' => 100,
                'total' => 100,
                'total_ht' => 83.33,
                'products' => [
                    ['product_id' => $product->id, 'qte' => 1],
                ],
            ])
            ->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'store_id',
                'customer_id',
                'invoice_date',
                'total',
                'total_ht',
                'paid_amount',
                'payment_method',
                'status',
                'status_label',
                'customer' => ['id', 'phone'],
                'items' => [
                    ['id', 'product_id', 'qte', 'price', 'line_total'],
                ],
            ],
        ]);
    }

    public function test_payment_history_resource_exposes_stable_contract_fields(): void
    {
        $owner = User::factory()->owner()->create();
        $payment = PaymentHistory::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($owner)
            ->getJson("/api/v1/payments/{$payment->id}")
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'owner_id',
                    'total_ttc',
                    'total_ht',
                    'status',
                    'status_label',
                    'created_at',
                ],
            ]);
    }

    public function test_checkout_preview_resource_exposes_stable_contract_fields(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/payments/preview')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'order_id',
                    'lines',
                    'total_ht',
                    'total_ttc',
                    'tva_rate',
                    'can_checkout',
                ],
            ]);
    }
}
