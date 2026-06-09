<?php

namespace Tests\Feature\Pos;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_load_pos_catalog_for_own_store(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $product = Product::factory()->create(['store_id' => $store->id, 'label' => 'Haircut']);

        $this->actingAs($employer)
            ->getJson("/api/v1/pos/catalog?store_id={$store->id}")
            ->assertOk()
            ->assertJsonPath('store_id', $store->id)
            ->assertJsonPath('customers.0.id', $customer->id)
            ->assertJsonPath('products.0.id', $product->id)
            ->assertJsonPath('products.0.label', 'Haircut');
    }

    public function test_employer_cannot_load_catalog_for_other_store(): void
    {
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $storeA->id]);

        $this->actingAs($employer)
            ->getJson("/api/v1/pos/catalog?store_id={$storeB->id}")
            ->assertForbidden();
    }

    public function test_employer_can_create_invoice_from_pos_catalog(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price' => 150,
        ]);

        $this->actingAs($employer)
            ->getJson("/api/v1/pos/catalog?store_id={$store->id}")
            ->assertOk();

        $this->actingAs($employer)
            ->postJson('/api/v1/invoices', [
                'store_id' => $store->id,
                'customer_id' => $customer->id,
                'payment_method' => 0,
                'paid_amount' => 150,
                'total' => 150,
                'total_ht' => 125,
                'products' => [
                    ['product_id' => $product->id, 'qte' => 1],
                ],
            ])
            ->assertCreated()
            ->assertJsonPath('data.total', 150)
            ->assertJsonPath('data.paid_amount', 150);
    }
}
