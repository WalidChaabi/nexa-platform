<?php

namespace App\Support\Authorization;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class StoreAccess
{
    public static function canAccessStore(User $user, int $storeId): bool
    {
        return match ($user->role()) {
            UserRole::Admin => true,
            UserRole::Employer => (int) $user->store_id === $storeId,
            UserRole::Owner => Store::query()
                ->whereKey($storeId)
                ->where('user_id', $user->id)
                ->exists(),
        };
    }

    public static function isAdmin(User $user): bool
    {
        return $user->role() === UserRole::Admin;
    }

    /**
     * @return list<int>
     */
    public static function accessibleStoreIds(User $user): array
    {
        return match ($user->role()) {
            UserRole::Admin => Store::query()->pluck('id')->map(fn ($id) => (int) $id)->all(),
            UserRole::Employer => $user->store_id ? [(int) $user->store_id] : [],
            UserRole::Owner => Store::query()
                ->where('user_id', $user->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all(),
        };
    }

    public static function applyStoreScope(Builder $query, User $user, string $column = 'store_id'): void
    {
        if (self::isAdmin($user)) {
            return;
        }

        $storeIds = self::accessibleStoreIds($user);
        $query->whereIn($column, $storeIds ?: [-1]);
    }
}
