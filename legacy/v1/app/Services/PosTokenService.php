<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PosTokenService
{
    public function verify(User $user, $plainToken): bool
    {
        if ($plainToken === null || $plainToken === '') {
            return false;
        }

        if (!empty($user->pos_token_hash)) {
            return Hash::check($plainToken, $user->pos_token_hash);
        }

        if (Schema::hasColumn('users', 'token') && !empty($user->token)) {
            return hash_equals((string) $user->token, (string) $plainToken);
        }

        return false;
    }

    public function setToken(User $user, $plainToken): void
    {
        $user->pos_token_hash = Hash::make($plainToken);

        if (Schema::hasColumn('users', 'token')) {
            $user->token = null;
        }

        $user->save();
    }
}
