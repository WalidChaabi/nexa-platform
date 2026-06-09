<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivityReferenceSeeder extends Seeder
{
    public function run(): void
    {
        Activity::query()->firstOrCreate(
            ['name' => 'Beauty & Wellness'],
            ['description' => 'Salons, spas, and wellness retailers'],
        );
    }
}
