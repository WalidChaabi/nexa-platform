<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->randomFloat(2, 50, 1000);
        $paid = fake()->randomFloat(2, 0, $total);

        return [
            'store_id' => Store::factory(),
            'user_id' => fn (array $attributes) => Store::query()->find($attributes['store_id'])?->user_id
                ?? User::factory()->owner()->create()->id,
            'employ_id' => User::factory()->employer(),
            'customer_id' => fn (array $attributes) => Customer::factory()->create([
                'store_id' => $attributes['store_id'],
            ])->id,
            'for_sexe' => fake()->numberBetween(0, 1),
            'description' => 0,
            'invoice_date' => now(),
            'total' => $total,
            'total_ht' => round($total / 1.2, 2),
            'tva' => round($total - ($total / 1.2), 2),
            'paid_amount' => $paid,
            'payment_method' => fake()->numberBetween(0, 2),
            'points' => 0,
            'status' => InvoiceStatus::Pending->value,
            'reste' => max(0, $total - $paid),
            'reste_payer' => 0,
        ];
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => InvoiceStatus::Cancelled->value]);
    }

    public function forStore(Store $store): static
    {
        return $this->state(fn () => [
            'store_id' => $store->id,
            'user_id' => $store->user_id,
            'customer_id' => Customer::factory()->create(['store_id' => $store->id])->id,
        ]);
    }
}
