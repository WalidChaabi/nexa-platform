<?php

namespace Database\Factories;

use App\Enums\PaymentHistoryStatus;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentHistory>
 */
class PaymentHistoryFactory extends Factory
{
    protected $model = PaymentHistory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalHt = fake()->randomFloat(2, 50, 500);

        return [
            'owner_id' => User::factory()->owner(),
            'cmd' => 'F-'.now()->format('d-m-Y').'-A-'.fake()->unique()->numerify('######'),
            'total_ht' => $totalHt,
            'total_ttc' => round($totalHt * 1.2, 2),
            'services' => json_encode([
                'emails' => 0,
                'sms_normal' => 0,
                'sms_advanced' => 0,
            ]),
            'status' => PaymentHistoryStatus::Paid->value,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => PaymentHistoryStatus::Pending->value]);
    }
}
