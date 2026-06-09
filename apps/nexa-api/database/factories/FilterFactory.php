<?php

namespace Database\Factories;

use App\Models\Filter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Filter>
 */
class FilterFactory extends Factory
{
    protected $model = Filter::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'age_min' => 18,
            'age_max' => 65,
            'sexe' => 'both',
            'points' => 100,
            'last_visit' => 0,
            'heure_start' => '09:00:00',
            'heure_end' => '18:00:00',
        ];
    }
}
