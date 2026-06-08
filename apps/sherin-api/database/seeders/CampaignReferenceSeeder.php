<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'birthday', 'type' => 'birthday'],
            ['name' => 'bienvenu', 'type' => 'bienvenu'],
            ['name' => 'rendez-vous', 'type' => 'rendez-vous'],
            ['name' => 'cross-selling', 'type' => 'cross-selling'],
            ['name' => 'unique', 'type' => 'unique'],
            ['name' => 'credit-sup', 'type' => 'credit-sup'],
        ];

        foreach ($types as $campaign) {
            Campaign::query()->firstOrCreate(
                ['type' => $campaign['type']],
                ['name' => $campaign['name']],
            );
        }
    }
}
