<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 10, 500);

        return [
            'store_id' => Store::factory(),
            'label' => fake()->words(2, true),
            'price' => $price,
            'promotion_price' => $price,
            'quantite' => fake()->numberBetween(0, 100),
            'categorie_id' => null,
            'image' => null,
        ];
    }
}
