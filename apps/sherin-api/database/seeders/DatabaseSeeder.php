<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'firstname' => 'Nexa',
            'lastname' => 'Admin',
            'email' => 'admin@nexa.test',
        ]);

        User::factory()->owner()->create([
            'firstname' => 'Store',
            'lastname' => 'Owner',
            'email' => 'owner@nexa.test',
        ]);

        User::factory()->employer()->create([
            'firstname' => 'POS',
            'lastname' => 'Employer',
            'email' => 'employer@nexa.test',
        ]);

        $this->call(ActivityReferenceSeeder::class);
        $this->call(CampaignReferenceSeeder::class);
        $this->call(NexaDemoSeeder::class);
    }
}
