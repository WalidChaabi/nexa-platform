<?php

namespace Tests\Feature\Reports;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_fetch_sales_report(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'invoice_date' => Carbon::parse('2026-06-05'),
            'paid_amount' => 100,
            'total' => 120,
            'total_ht' => 100,
            'status' => 0,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/reports/sales?store_id='.$store->id.'&from=2026-06-01&to=2026-06-30')
            ->assertOk()
            ->assertJsonPath('data.summary.invoice_count', 1)
            ->assertJsonPath('data.summary.total_paid', 100);
    }

    public function test_owner_can_fetch_customers_report(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        Customer::factory()->create([
            'store_id' => $store->id,
            'created_at' => Carbon::parse('2026-06-10'),
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/reports/customers?store_id='.$store->id.'&from=2026-06-01&to=2026-06-30')
            ->assertOk()
            ->assertJsonPath('data.summary.customer_count', 1);
    }

    public function test_owner_can_fetch_products_report(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'price' => 200,
            'promotion_price' => 150,
        ]);

        $invoice = Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'invoice_date' => Carbon::parse('2026-06-12'),
            'status' => 0,
        ]);

        InvoiceProduct::query()->create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'qte' => 2,
            'price' => 200,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/reports/products?store_id='.$store->id.'&from=2026-06-01&to=2026-06-30')
            ->assertOk()
            ->assertJsonPath('data.summary.total_quantity', 2)
            ->assertJsonPath('data.summary.total_revenue', 400);
    }

    public function test_owner_cannot_fetch_report_for_other_store(): void
    {
        $owner = User::factory()->owner()->create();
        $otherStore = Store::factory()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/reports/sales?store_id='.$otherStore->id.'&from=2026-06-01&to=2026-06-30')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    public function test_owner_can_download_sales_export_html(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)
            ->get('/api/v1/reports/sales/export?store_id='.$store->id.'&from=2026-06-01&to=2026-06-30');

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8');
    }

    public function test_admin_can_fetch_sales_report_for_any_store(): void
    {
        $admin = User::factory()->admin()->create();
        $store = Store::factory()->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/reports/sales?store_id='.$store->id.'&from=2026-06-01&to=2026-06-30')
            ->assertOk();
    }
}
