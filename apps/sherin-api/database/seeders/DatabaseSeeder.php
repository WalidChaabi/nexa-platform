<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'firstname' => 'Sherin',
            'lastname' => 'Admin',
            'email' => 'admin@sherin.test',
        ]);

        User::factory()->owner()->create([
            'firstname' => 'Store',
            'lastname' => 'Owner',
            'email' => 'owner@sherin.test',
        ]);

        User::factory()->employer()->create([
            'firstname' => 'POS',
            'lastname' => 'Employer',
            'email' => 'employer@sherin.test',
        ]);

        $this->call(ActivityReferenceSeeder::class);
        $this->call(CampaignReferenceSeeder::class);
        $this->call(SherinDemoSeeder::class);
    }
}
