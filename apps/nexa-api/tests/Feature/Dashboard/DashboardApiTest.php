<?php

namespace Tests\Feature\Dashboard;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_receives_store_kpis(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Product::factory()->create(['store_id' => $store->id]);

        Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'invoice_date' => Carbon::parse('2026-06-09'),
            'total' => 300,
            'status' => 0,
        ]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'start' => Carbon::parse('2026-06-10 10:00:00'),
            'status' => 0,
        ]);

        $this->actingAs($employer)
            ->getJson('/api/v1/dashboard/kpis')
            ->assertOk()
            ->assertJsonPath('data.role', 'employer')
            ->assertJsonPath('data.sales.today.invoice_count', 1)
            ->assertJsonPath('data.sales.today.revenue_ttc', 300)
            ->assertJsonPath('data.customers.total', 1)
            ->assertJsonPath('data.catalog.products', 1)
            ->assertJsonPath('data.appointments.upcoming_week', 1)
            ->assertJsonCount(7, 'data.charts.sales_daily')
            ->assertJsonPath('data.charts.sales_daily.6.revenue_ttc', 300)
            ->assertJsonPath('data.charts.sales_daily.6.invoice_count', 1)
            ->assertJsonCount(7, 'data.charts.appointments_daily');

        Carbon::setTestNow();
    }

    public function test_owner_can_filter_kpis_by_store(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');

        $owner = User::factory()->owner()->create();
        $storeA = Store::factory()->create(['user_id' => $owner->id, 'name' => 'Store A']);
        $storeB = Store::factory()->create(['user_id' => $owner->id, 'name' => 'Store B']);
        $customerA = Customer::factory()->create(['store_id' => $storeA->id]);
        $customerB = Customer::factory()->create(['store_id' => $storeB->id]);

        Invoice::factory()->create([
            'store_id' => $storeA->id,
            'customer_id' => $customerA->id,
            'invoice_date' => Carbon::parse('2026-06-09'),
            'total' => 100,
            'status' => 0,
        ]);

        Invoice::factory()->create([
            'store_id' => $storeB->id,
            'customer_id' => $customerB->id,
            'invoice_date' => Carbon::parse('2026-06-09'),
            'total' => 200,
            'status' => 0,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/dashboard/kpis?store_id='.$storeA->id)
            ->assertOk()
            ->assertJsonPath('data.scope.store_id', $storeA->id)
            ->assertJsonPath('data.scope.store_name', 'Store A')
            ->assertJsonPath('data.sales.today.invoice_count', 1)
            ->assertJsonPath('data.sales.today.revenue_ttc', 100);

        $this->actingAs($owner)
            ->getJson('/api/v1/dashboard/kpis')
            ->assertOk()
            ->assertJsonPath('data.scope.store_count', 2)
            ->assertJsonPath('data.sales.today.invoice_count', 2)
            ->assertJsonPath('data.sales.today.revenue_ttc', 300);

        Carbon::setTestNow();
    }

    public function test_admin_receives_platform_kpis(): void
    {
        $admin = User::factory()->admin()->create();
        $activeOwner = User::factory()->owner()->create();
        User::factory()->owner()->inactive()->create();
        Store::factory()->create(['user_id' => $activeOwner->id]);

        $this->actingAs($admin)
            ->getJson('/api/v1/dashboard/kpis')
            ->assertOk()
            ->assertJsonPath('data.role', 'admin')
            ->assertJsonPath('data.platform.owners_active', 1)
            ->assertJsonPath('data.platform.owners_inactive', 1)
            ->assertJsonPath('data.platform.stores', 1)
            ->assertJsonCount(2, 'data.charts.platform_breakdown');
    }

    public function test_employer_cannot_query_foreign_store(): void
    {
        $owner = User::factory()->owner()->create();
        $foreignStore = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->create(['store_id' => null]);

        $this->actingAs($employer)
            ->getJson('/api/v1/dashboard/kpis?store_id='.$foreignStore->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }
}
