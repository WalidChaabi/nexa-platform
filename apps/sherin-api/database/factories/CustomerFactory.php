<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'user_id' => null,
            'phone' => fake()->numerify('06########'),
            'email' => fake()->optional()->safeEmail(),
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'sexe' => fake()->numberBetween(0, 1),
            'birth' => fake()->optional()->date(),
            'points' => (string) fake()->numberBetween(0, 500),
        ];
    }
}
