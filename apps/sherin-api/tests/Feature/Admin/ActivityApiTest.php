<?php

namespace Tests\Feature\Admin;

use App\Models\Activity;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_activities(): void
    {
        $admin = User::factory()->admin()->create();
        $activity = Activity::query()->create([
            'name' => 'Restaurant',
            'description' => 'Food service',
        ]);

        $this->actingAs($admin)
            ->getJson('/api/v1/activities')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $activity->id)
            ->assertJsonPath('data.0.name', 'Restaurant');
    }

    public function test_owner_cannot_manage_activities(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/activities')
            ->assertForbidden();

        $this->actingAs($owner)
            ->postJson('/api/v1/activities', [
                'name' => 'Blocked',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_create_and_update_activity(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->postJson('/api/v1/activities', [
                'name' => 'Retail',
                'description' => 'General retail',
            ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Retail');

        $activityId = $response->json('data.id');

        $this->actingAs($admin)
            ->putJson("/api/v1/activities/{$activityId}", [
                'name' => 'Retail & Services',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Retail & Services');
    }

    public function test_admin_cannot_delete_activity_assigned_to_store(): void
    {
        $admin = User::factory()->admin()->create();
        $activity = Activity::query()->create(['name' => 'Salon']);
        Store::factory()->create(['activity_id' => $activity->id]);

        $this->actingAs($admin)
            ->deleteJson("/api/v1/activities/{$activity->id}")
            ->assertStatus(422);

        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    public function test_admin_can_delete_unused_activity(): void
    {
        $admin = User::factory()->admin()->create();
        $activity = Activity::query()->create(['name' => 'Unused']);

        $this->actingAs($admin)
            ->deleteJson("/api/v1/activities/{$activity->id}")
            ->assertOk();

        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }
}
