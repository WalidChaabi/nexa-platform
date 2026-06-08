<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_owners(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create(['email' => 'listed@sherin.test']);
        User::factory()->employer()->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/owners')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $owner->id)
            ->assertJsonPath('data.0.email', 'listed@sherin.test');
    }

    public function test_owner_cannot_access_owner_admin_endpoints(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/owners')
            ->assertForbidden();
    }

    public function test_admin_can_create_owner(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson('/api/v1/owners', [
                'firstname' => 'Karim',
                'lastname' => 'Retail',
                'email' => 'karim@sherin.test',
                'phone' => '0611223344',
                'password' => 'password123',
                'allow_share' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.firstname', 'Karim')
            ->assertJsonPath('data.allow_share', 1)
            ->assertJsonPath('data.is_active', 1);

        $this->assertDatabaseHas('users', [
            'email' => 'karim@sherin.test',
            'is_admin' => UserRole::Owner->value,
        ]);
    }

    public function test_admin_can_update_owner(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create(['phone' => '0600000000']);

        $this->actingAs($admin)
            ->putJson("/api/v1/owners/{$owner->id}", [
                'phone' => '0699887766',
                'allow_share' => false,
            ])
            ->assertOk()
            ->assertJsonPath('data.phone', '0699887766')
            ->assertJsonPath('data.allow_share', 0);
    }

    public function test_admin_can_deactivate_owner_and_employers(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->create([
            'store_id' => $store->id,
            'is_active' => 1,
        ]);

        $this->actingAs($admin)
            ->postJson("/api/v1/owners/{$owner->id}/deactivate")
            ->assertOk()
            ->assertJsonPath('data.is_active', 0);

        $this->assertDatabaseHas('users', ['id' => $owner->id, 'is_active' => 0]);
        $this->assertDatabaseHas('users', ['id' => $employer->id, 'is_active' => 0]);
    }

    public function test_admin_can_activate_owner_and_employers(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->inactive()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->inactive()->create([
            'store_id' => $store->id,
        ]);

        $this->actingAs($admin)
            ->postJson("/api/v1/owners/{$owner->id}/activate")
            ->assertOk()
            ->assertJsonPath('data.is_active', 1);

        $this->assertDatabaseHas('users', ['id' => $owner->id, 'is_active' => 1]);
        $this->assertDatabaseHas('users', ['id' => $employer->id, 'is_active' => 1]);
    }

    public function test_admin_cannot_resolve_non_owner_user_as_owner(): void
    {
        $admin = User::factory()->admin()->create();
        $employer = User::factory()->employer()->create();

        $this->actingAs($admin)
            ->getJson("/api/v1/owners/{$employer->id}")
            ->assertNotFound();
    }
}
