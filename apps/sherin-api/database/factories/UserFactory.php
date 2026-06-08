<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => null,
            'is_admin' => UserRole::Employer->value,
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'phone' => fake()->numerify('06########'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'allow_share' => 0,
            'status' => 1,
            'is_active' => 1,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['is_admin' => UserRole::Admin->value]);
    }

    public function owner(): static
    {
        return $this->state(fn () => ['is_admin' => UserRole::Owner->value]);
    }

    public function employer(): static
    {
        return $this->state(fn () => ['is_admin' => UserRole::Employer->value]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => 0]);
    }
}
