<?php

namespace Database\Factories;

use App\Enums\RdvStatus;
use App\Models\Customer;
use App\Models\Rdv;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rdv>
 */
class RdvFactory extends Factory
{
    protected $model = Rdv::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $store = Store::factory()->create();
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        return [
            'customer_id' => $customer->id,
            'store_id' => $store->id,
            'event_id' => '#3b82f6',
            'title' => fake()->words(2, true),
            'start' => now()->addDays(2),
            'status' => RdvStatus::Active->value,
        ];
    }
}
