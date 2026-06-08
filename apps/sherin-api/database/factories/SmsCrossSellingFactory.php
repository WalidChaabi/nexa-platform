<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SmsCrossSelling;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmsCrossSelling>
 */
class SmsCrossSellingFactory extends Factory
{
    protected $model = SmsCrossSelling::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        return [
            'store_id' => $store->id,
            'cross_product_id' => $product->id,
            'sent_date' => now(),
            'credit' => 2,
            'status' => 0,
        ];
    }
}
