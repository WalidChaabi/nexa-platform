<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_store_categories(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $category = Category::factory()->create([
            'store_id' => $store->id,
            'name' => 'Hair care',
        ]);
        Category::factory()->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Hair care');
    }

    public function test_employer_can_create_category(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $response = $this->actingAs($employer)
            ->postJson('/api/v1/categories', [
                'store_id' => $store->id,
                'name' => 'Services',
                'description' => 'Salon services',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Services');

        $this->assertDatabaseHas('categories', [
            'store_id' => $store->id,
            'name' => 'Services',
        ]);
    }

    public function test_employer_cannot_update_other_store_category(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $category = Category::factory()->create(['store_id' => $otherStore->id]);

        $this->actingAs($employer)
            ->putJson("/api/v1/categories/{$category->id}", [
                'name' => 'Hacked',
            ])
            ->assertForbidden();
    }

    public function test_employer_can_delete_own_category(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $category = Category::factory()->create(['store_id' => $store->id]);

        $this->actingAs($employer)
            ->deleteJson("/api/v1/categories/{$category->id}")
            ->assertOk();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
