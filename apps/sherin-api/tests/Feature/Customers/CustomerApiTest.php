<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_store_customers(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create([
            'store_id' => $store->id,
            'phone' => '0611111111',
        ]);
        Customer::factory()->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/customers')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $customer->id);
    }

    public function test_employer_can_create_customer(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $response = $this->actingAs($employer)
            ->postJson('/api/v1/customers', [
                'store_id' => $store->id,
                'phone' => '0622222222',
                'firstname' => 'Sara',
                'lastname' => 'Benali',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.phone', '0622222222')
            ->assertJsonPath('data.display_name', 'Sara Benali');

        $this->assertDatabaseHas('customers', [
            'store_id' => $store->id,
            'phone' => '0622222222',
            'user_id' => $employer->id,
        ]);
    }

    public function test_employer_cannot_create_customer_for_other_store(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $this->actingAs($employer)
            ->postJson('/api/v1/customers', [
                'store_id' => $otherStore->id,
                'phone' => '0633333333',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    public function test_employer_cannot_view_other_store_customer(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $otherStore->id]);

        $this->actingAs($employer)
            ->getJson("/api/v1/customers/{$customer->id}")
            ->assertForbidden();
    }

    public function test_owner_can_search_customers_across_owned_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        Customer::factory()->create([
            'store_id' => $store->id,
            'firstname' => 'Karim',
            'phone' => '0644444444',
        ]);
        Customer::factory()->create(['firstname' => 'Other']);

        $this->actingAs($owner)
            ->getJson('/api/v1/customers?q=Karim')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.firstname', 'Karim');
    }

    public function test_employer_can_update_customer(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id, 'phone' => '0655555555']);

        $this->actingAs($employer)
            ->putJson("/api/v1/customers/{$customer->id}", [
                'firstname' => 'Updated',
            ])
            ->assertOk()
            ->assertJsonPath('data.firstname', 'Updated');
    }

    public function test_admin_can_delete_customer(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/v1/customers/{$customer->id}")
            ->assertOk();

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
