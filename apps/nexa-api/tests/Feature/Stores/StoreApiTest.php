<?php

namespace Tests\Feature\Stores;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_list_own_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $ownStore = Store::factory()->create(['user_id' => $owner->id, 'name' => 'Own Shop']);
        Store::factory()->create(['name' => 'Other Shop']);

        $this->actingAs($owner)
            ->getJson('/api/v1/stores')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownStore->id)
            ->assertJsonPath('data.0.name', 'Own Shop');
    }

    public function test_owner_cannot_view_other_store(): void
    {
        $owner = User::factory()->owner()->create();
        $otherStore = Store::factory()->create();

        $this->actingAs($owner)
            ->getJson("/api/v1/stores/{$otherStore->id}")
            ->assertForbidden();
    }

    public function test_owner_can_create_store(): void
    {
        $owner = User::factory()->owner()->create();

        $response = $this->actingAs($owner)
            ->postJson('/api/v1/stores', [
                'name' => 'New Boutique',
                'phone' => '0612345678',
                'address' => 'Casablanca',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'New Boutique')
            ->assertJsonPath('data.user_id', $owner->id);

        $this->assertDatabaseHas('stores', [
            'user_id' => $owner->id,
            'name' => 'New Boutique',
        ]);
    }

    public function test_owner_can_update_own_store(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id, 'name' => 'Before']);

        $this->actingAs($owner)
            ->putJson("/api/v1/stores/{$store->id}", [
                'name' => 'After',
                'phone' => '0699999999',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'After')
            ->assertJsonPath('data.phone', '0699999999');
    }

    public function test_employer_can_view_assigned_store(): void
    {
        $store = Store::factory()->create(['name' => 'POS Store']);
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $this->actingAs($employer)
            ->getJson("/api/v1/stores/{$store->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'POS Store');
    }

    public function test_employer_cannot_create_store(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $this->actingAs($employer)
            ->postJson('/api/v1/stores', ['name' => 'Blocked'])
            ->assertForbidden();
    }

    public function test_admin_can_list_all_stores(): void
    {
        $admin = User::factory()->admin()->create();
        Store::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/stores')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_delete_store(): void
    {
        $admin = User::factory()->admin()->create();
        $store = Store::factory()->create();

        $this->actingAs($admin)
            ->deleteJson("/api/v1/stores/{$store->id}")
            ->assertOk();

        $this->assertDatabaseMissing('stores', ['id' => $store->id]);
    }

    public function test_owner_cannot_delete_store(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->deleteJson("/api/v1/stores/{$store->id}")
            ->assertForbidden();
    }
}
