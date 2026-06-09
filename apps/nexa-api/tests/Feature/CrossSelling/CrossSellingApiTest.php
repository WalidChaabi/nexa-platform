<?php

namespace Tests\Feature\CrossSelling;

use App\Jobs\SendCampaignSmsJob;
use App\Models\CrossProduct;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\SmsCrossSelling;
use App\Models\Store;
use App\Models\User;
use App\Services\CrossSellingAutomationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CrossSellingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_list_cross_products_for_own_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);
        $otherProduct = Product::factory()->create();

        CrossProduct::factory()->create(['product_id' => $product->id]);
        CrossProduct::factory()->create(['product_id' => $otherProduct->id]);

        $this->actingAs($owner)
            ->getJson('/api/v1/cross-products')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_owner_can_create_cross_product_rule(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id, 'sender_id' => 'DEMO']);
        $product = Product::factory()->create(['store_id' => $store->id]);

        $this->actingAs($owner)
            ->postJson('/api/v1/cross-products', [
                'product_id' => $product->id,
                'message' => 'Try our add-on (Prénom)!',
                'days' => 14,
            ])
            ->assertCreated()
            ->assertJsonPath('data.days', 14)
            ->assertJsonPath('data.product.label', $product->label);
    }

    public function test_owner_can_update_cross_product_rule(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);
        $rule = CrossProduct::factory()->create([
            'product_id' => $product->id,
            'days' => 10,
            'message' => 'Original',
        ]);

        $this->actingAs($owner)
            ->putJson("/api/v1/cross-products/{$rule->id}", [
                'days' => 21,
                'message' => 'Updated follow-up',
            ])
            ->assertOk()
            ->assertJsonPath('data.days', 21)
            ->assertJsonPath('data.message', 'Updated follow-up');
    }

    public function test_owner_cannot_create_rule_for_other_store_product(): void
    {
        $owner = User::factory()->owner()->create();
        $product = Product::factory()->create();

        $this->actingAs($owner)
            ->postJson('/api/v1/cross-products', [
                'product_id' => $product->id,
                'message' => 'Blocked',
                'days' => 10,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['product_id']);
    }

    public function test_automation_queues_sms_for_matching_invoice_date(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $product = Product::factory()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create([
            'store_id' => $store->id,
            'firstname' => 'Sara',
            'phone' => '0611223344',
        ]);

        CrossProduct::factory()->create([
            'product_id' => $product->id,
            'message' => 'Hello (Prénom)',
            'days' => 20,
        ]);

        $invoice = Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'invoice_date' => Carbon::parse('2026-06-01 10:00:00'),
        ]);

        InvoiceProduct::query()->create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'qte' => 1,
            'price' => 100,
        ]);

        $result = app(CrossSellingAutomationService::class)
            ->runDaily(Carbon::parse('2026-06-21 09:00:00'));

        $this->assertSame(1, $result['sms_queued']);
        Bus::assertDispatched(SendCampaignSmsJob::class);
        $this->assertDatabaseHas('sms_cross_selling', [
            'store_id' => $store->id,
            'cross_product_id' => $product->id,
            'status' => 0,
        ]);
    }

    public function test_automation_skips_when_sms_already_sent_today(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $product = Product::factory()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        CrossProduct::factory()->create([
            'product_id' => $product->id,
            'days' => 5,
        ]);

        $invoice = Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'invoice_date' => Carbon::parse('2026-06-10'),
        ]);

        InvoiceProduct::query()->create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'qte' => 1,
            'price' => 50,
        ]);

        SmsCrossSelling::factory()->create([
            'store_id' => $store->id,
            'cross_product_id' => $product->id,
            'sent_date' => Carbon::parse('2026-06-15'),
        ]);

        $result = app(CrossSellingAutomationService::class)
            ->runDaily(Carbon::parse('2026-06-15'));

        $this->assertSame(0, $result['sms_queued']);
        Bus::assertNothingDispatched();
    }

    public function test_owner_can_list_sms_cross_selling_history(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['store_id' => $store->id]);

        SmsCrossSelling::factory()->create([
            'store_id' => $store->id,
            'cross_product_id' => $product->id,
        ]);

        SmsCrossSelling::factory()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/sms-cross-selling')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_list_all_cross_products(): void
    {
        $admin = User::factory()->admin()->create();
        CrossProduct::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/cross-products')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
