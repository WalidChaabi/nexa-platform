<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesStoreScopedAccess
{
    /**
     * Store IDs the current user may access, or null for admin (all stores).
     *
     * @return array<int>|null
     */
    protected function authorizedStoreIds()
    {
        $user = auth()->user();

        if ((int) $user->is_admin === 1) {
            return null;
        }

        if ((int) $user->is_admin === 2) {
            return $user->stores()->pluck('id')->map(function ($id) {
                return (int) $id;
            })->toArray();
        }

        return [(int) $user->store_id];
    }

    protected function userCanAccessStoreId($storeId): bool
    {
        $authorized = $this->authorizedStoreIds();

        if ($authorized === null) {
            return true;
        }

        return in_array((int) $storeId, $authorized, true);
    }

    protected function scopeToAuthorizedStores($query, $column = 'store_id')
    {
        $storeIds = $this->authorizedStoreIds();

        if ($storeIds === null) {
            return $query;
        }

        return $query->whereIn($column, $storeIds);
    }

    /**
     * @param string $modelClass
     * @param int|string $id
     * @return Model
     */
    protected function findAuthorizedStoreScopedModel($modelClass, $id)
    {
        return $this->scopeToAuthorizedStores($modelClass::where('id', $id))->firstOrFail();
    }

    protected function findAuthorizedStore($id): Store
    {
        $user = auth()->user();
        $query = Store::where('id', $id);

        if ((int) $user->is_admin === 1) {
            return $query->firstOrFail();
        }

        if ((int) $user->is_admin === 2) {
            return $query->where('user_id', $user->id)->firstOrFail();
        }

        return $query->where('id', $user->store_id)->firstOrFail();
    }

    protected function authorizeStoreIdFromRequest($storeId): void
    {
        if (!$this->userCanAccessStoreId($storeId)) {
            abort(404);
        }
    }
}
