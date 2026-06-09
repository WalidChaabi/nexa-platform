<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PosTokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class MigratePosTokens extends Command
{
    protected $signature = 'nexa:migrate-pos-tokens {--dry-run : List users without hashing}';

    protected $description = 'Hash legacy plaintext POS tokens into pos_token_hash';

    public function handle(PosTokenService $posTokenService)
    {
        if (!Schema::hasColumn('users', 'pos_token_hash')) {
            $this->error('Run migrations first: users.pos_token_hash column is missing.');

            return 1;
        }

        if (!Schema::hasColumn('users', 'token')) {
            $this->warn('No legacy users.token column — nothing to migrate.');

            return 0;
        }

        $migrated = 0;
        User::whereNotNull('token')->where('token', '!=', '')->chunk(100, function ($users) use ($posTokenService, &$migrated) {
            foreach ($users as $user) {
                if (!empty($user->pos_token_hash)) {
                    continue;
                }

                if ($this->option('dry-run')) {
                    $this->line("Would migrate user #{$user->id}");
                    $migrated++;

                    continue;
                }

                $posTokenService->setToken($user, $user->token);
                $migrated++;
            }
        });

        $this->info("POS token migration complete ({$migrated} user(s)).");

        return 0;
    }
}
