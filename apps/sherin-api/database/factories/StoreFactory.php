<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->owner(),
            'activity_id' => 1,
            'name' => fake()->company(),
            'phone' => fake()->numerify('06########'),
            'contact' => fake()->name(),
            'address' => fake()->address(),
            'sender_id' => strtoupper(fake()->lexify('????')),
            'status' => 1,
        ];
    }
}
