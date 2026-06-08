<?php

namespace Database\Factories;

use App\Models\CrossProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CrossProduct>
 */
class CrossProductFactory extends Factory
{
    protected $model = CrossProduct::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'message' => 'Hello (Prénom), discover our new offer!',
            'days' => 20,
        ];
    }
}
