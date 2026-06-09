<?php

namespace Database\Factories;

use App\Models\CampaignHistorique;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignHistorique>
 */
class CampaignHistoriqueFactory extends Factory
{
    protected $model = CampaignHistorique::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $store = Store::factory()->create();
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $invoice = Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
        ]);

        return [
            'camp_id' => 1,
            'invoice_id' => $invoice->id,
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'phone' => $customer->phone,
            'code_red' => fake()->bothify('??####'),
            'start_date' => now(),
            'status' => 0,
            'expired_code' => 0,
            'is_payed' => 0,
        ];
    }
}
