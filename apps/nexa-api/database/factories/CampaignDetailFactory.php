<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignDetail>
 */
class CampaignDetailFactory extends Factory
{
    protected $model = CampaignDetail::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'store_id' => Store::factory(),
            'filter_id' => Filter::factory(),
            'product_id' => null,
            'reduction' => 10,
            'active' => 1,
        ];
    }
}
