<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_store_products(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'label' => 'Shampoo',
        ]);
        Product::factory()->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Shampoo');
    }

    public function test_employer_can_create_product(): void
    {
        $store = Store::factory()->create();
        $category = Category::factory()->create(['store_id' => $store->id]);
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $response = $this->actingAs($employer)
            ->postJson('/api/v1/products', [
                'store_id' => $store->id,
                'label' => 'Haircut',
                'price' => 150,
                'categorie_id' => $category->id,
                'quantite' => 10,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.label', 'Haircut')
            ->assertJsonPath('data.price', 150)
            ->assertJsonPath('data.category.id', $category->id);

        $this->assertDatabaseHas('products', [
            'store_id' => $store->id,
            'label' => 'Haircut',
        ]);
    }

    public function test_employer_cannot_create_product_for_other_store(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        $this->actingAs($employer)
            ->postJson('/api/v1/products', [
                'store_id' => $otherStore->id,
                'label' => 'Blocked',
                'price' => 50,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    public function test_employer_can_search_products(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        Product::factory()->create(['store_id' => $store->id, 'label' => 'Coloration']);
        Product::factory()->create(['store_id' => $store->id, 'label' => 'Brushing']);

        $this->actingAs($employer)
            ->getJson('/api/v1/products?q=Color')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Coloration');
    }

    public function test_employer_can_update_product(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'label' => 'Before',
            'price' => 100,
        ]);

        $this->actingAs($employer)
            ->putJson("/api/v1/products/{$product->id}", [
                'label' => 'After',
                'price' => 120,
            ])
            ->assertOk()
            ->assertJsonPath('data.label', 'After')
            ->assertJsonPath('data.price', 120);
    }

    public function test_owner_can_list_products_across_owned_stores(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        Product::factory()->create(['store_id' => $store->id]);
        Product::factory()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
