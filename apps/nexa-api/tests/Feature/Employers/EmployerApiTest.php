<?php

namespace Tests\Feature\Employers;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_list_employers_for_owned_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create([
            'store_id' => $store->id,
            'email' => 'staff@nexa.test',
        ]);
        User::factory()->employer()->create(['store_id' => $otherStore->id]);

        $this->actingAs($owner)
            ->getJson('/api/v1/employers')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $employer->id)
            ->assertJsonPath('data.0.store_id', $store->id)
            ->assertJsonPath('data.0.store.name', $store->name);
    }

    public function test_owner_can_filter_employers_by_store(): void
    {
        $owner = User::factory()->owner()->create();
        $storeA = Store::factory()->create(['user_id' => $owner->id]);
        $storeB = Store::factory()->create(['user_id' => $owner->id]);
        $employerA = User::factory()->employer()->create(['store_id' => $storeA->id]);
        User::factory()->employer()->create(['store_id' => $storeB->id]);

        $this->actingAs($owner)
            ->getJson("/api/v1/employers?store_id={$storeA->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $employerA->id);
    }

    public function test_owner_can_invite_employer_to_owned_store(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->postJson('/api/v1/employers', [
                'store_id' => $store->id,
                'firstname' => 'Sara',
                'lastname' => 'Staff',
                'email' => 'sara@nexa.test',
                'phone' => '0611223344',
                'password' => 'password123',
                'allow_share' => false,
            ])
            ->assertCreated()
            ->assertJsonPath('data.firstname', 'Sara')
            ->assertJsonPath('data.store_id', $store->id)
            ->assertJsonPath('data.is_active', 1);

        $this->assertDatabaseHas('users', [
            'email' => 'sara@nexa.test',
            'store_id' => $store->id,
            'is_admin' => UserRole::Employer->value,
        ]);
    }

    public function test_owner_cannot_invite_employer_to_foreign_store(): void
    {
        $owner = User::factory()->owner()->create();
        $foreignStore = Store::factory()->create();

        $this->actingAs($owner)
            ->postJson('/api/v1/employers', [
                'store_id' => $foreignStore->id,
                'firstname' => 'Blocked',
                'lastname' => 'User',
                'email' => 'blocked@nexa.test',
                'phone' => '0611223344',
                'password' => 'password123',
            ])
            ->assertForbidden();
    }

    public function test_owner_can_update_and_deactivate_employer(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->create([
            'store_id' => $store->id,
            'phone' => '0600000000',
        ]);

        $this->actingAs($owner)
            ->putJson("/api/v1/employers/{$employer->id}", [
                'phone' => '0699887766',
            ])
            ->assertOk()
            ->assertJsonPath('data.phone', '0699887766');

        $this->actingAs($owner)
            ->postJson("/api/v1/employers/{$employer->id}/deactivate")
            ->assertOk()
            ->assertJsonPath('data.is_active', 0);
    }

    public function test_owner_cannot_manage_employer_from_foreign_store(): void
    {
        $owner = User::factory()->owner()->create();
        Store::factory()->create(['user_id' => $owner->id]);
        $foreignEmployer = User::factory()->employer()->create();

        $this->actingAs($owner)
            ->getJson("/api/v1/employers/{$foreignEmployer->id}")
            ->assertForbidden();

        $this->actingAs($owner)
            ->putJson("/api/v1/employers/{$foreignEmployer->id}", [
                'phone' => '0600000001',
            ])
            ->assertForbidden();
    }

    public function test_employer_cannot_access_team_endpoints(): void
    {
        $employer = User::factory()->employer()->create(['store_id' => null]);

        $this->actingAs($employer)
            ->getJson('/api/v1/employers')
            ->assertForbidden();

        $this->actingAs($employer)
            ->postJson('/api/v1/employers', [
                'store_id' => 1,
                'firstname' => 'Nope',
                'lastname' => 'User',
                'email' => 'nope@nexa.test',
                'phone' => '0611223344',
                'password' => 'password123',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_manage_all_employers(): void
    {
        $admin = User::factory()->admin()->create();
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $this->actingAs($admin)
            ->getJson('/api/v1/employers')
            ->assertOk()
            ->assertJsonPath('data.0.id', $employer->id);

        $this->actingAs($admin)
            ->postJson("/api/v1/employers/{$employer->id}/activate")
            ->assertOk();
    }
}
