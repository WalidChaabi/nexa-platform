<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentHistoryStatus;
use App\Enums\RdvStatus;
use App\Enums\UserRole;
use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Models\User;
use App\Support\Authorization\StoreAccess;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function kpis(User $user, ?int $storeId = null): array
    {
        $storeIds = $this->resolveStoreIds($user, $storeId);
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $weekEnd = $now->copy()->addDays(7)->endOfDay();

        $scopeStore = $storeId !== null
            ? Store::query()->find($storeId)
            : null;

        $payload = [
            'role' => $user->role()->label(),
            'scope' => [
                'store_id' => $storeId,
                'store_name' => $scopeStore?->name,
                'store_count' => count($storeIds),
            ],
            'sales' => [
                'today' => $this->salesMetrics(
                    $storeIds,
                    $now->copy()->startOfDay(),
                    $now->copy()->endOfDay(),
                ),
                'month' => $this->salesMetrics($storeIds, $monthStart, $monthEnd),
            ],
            'customers' => [
                'total' => $this->countCustomers($storeIds),
                'new_this_month' => $this->countCustomers($storeIds, $monthStart, $monthEnd),
            ],
            'catalog' => [
                'products' => $this->countProducts($storeIds),
            ],
            'appointments' => [
                'upcoming_week' => $this->countUpcomingAppointments($storeIds, $now, $weekEnd),
            ],
            'charts' => [
                'sales_daily' => $this->salesDailySeries($storeIds, $now),
                'customers_daily' => $this->customersDailySeries($storeIds, $now),
                'appointments_daily' => $this->appointmentsDailySeries($storeIds, $now),
            ],
        ];

        if ($user->role() === UserRole::Owner) {
            $payload['billing'] = [
                'payments_pending' => PaymentHistory::query()
                    ->where('owner_id', $user->id)
                    ->where('status', PaymentHistoryStatus::Pending->value)
                    ->count(),
            ];
        }

        if ($user->role() === UserRole::Admin) {
            $ownersActive = (int) User::query()
                ->where('is_admin', UserRole::Owner->value)
                ->where('is_active', 1)
                ->count();
            $ownersInactive = (int) User::query()
                ->where('is_admin', UserRole::Owner->value)
                ->where('is_active', 0)
                ->count();

            $payload['platform'] = [
                'owners_active' => $ownersActive,
                'owners_inactive' => $ownersInactive,
                'stores' => Store::query()->count(),
                'activities' => Activity::query()->count(),
                'campaigns' => Campaign::query()->count(),
                'payments_pending' => PaymentHistory::query()
                    ->where('status', PaymentHistoryStatus::Pending->value)
                    ->count(),
            ];

            $payload['charts']['platform_breakdown'] = [
                ['key' => 'owners_active', 'value' => $ownersActive],
                ['key' => 'owners_inactive', 'value' => $ownersInactive],
            ];
        }

        return $payload;
    }

    /**
     * @return list<int>
     */
    private function resolveStoreIds(User $user, ?int $storeId): array
    {
        if ($user->role() === UserRole::Employer) {
            return $user->store_id ? [(int) $user->store_id] : [];
        }

        if ($storeId !== null) {
            if (! StoreAccess::canAccessStore($user, $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            return [$storeId];
        }

        return StoreAccess::accessibleStoreIds($user);
    }

    /**
     * @param  list<int>  $storeIds
     * @return array{invoice_count: int, revenue_ttc: float}
     */
    private function salesMetrics(array $storeIds, Carbon $from, Carbon $to): array
    {
        if ($storeIds === []) {
            return ['invoice_count' => 0, 'revenue_ttc' => 0.0];
        }

        $base = Invoice::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', InvoiceStatus::Pending->value)
            ->whereBetween('invoice_date', [$from, $to]);

        return [
            'invoice_count' => (int) (clone $base)->count(),
            'revenue_ttc' => round((float) (clone $base)->sum('total'), 2),
        ];
    }

    /**
     * @param  list<int>  $storeIds
     */
    private function countCustomers(array $storeIds, ?Carbon $from = null, ?Carbon $to = null): int
    {
        if ($storeIds === []) {
            return 0;
        }

        $query = Customer::query()->whereIn('store_id', $storeIds);

        if ($from !== null && $to !== null) {
            $query->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);
        }

        return (int) $query->count();
    }

    /**
     * @param  list<int>  $storeIds
     */
    private function countProducts(array $storeIds): int
    {
        if ($storeIds === []) {
            return 0;
        }

        return (int) Product::query()->whereIn('store_id', $storeIds)->count();
    }

    /**
     * @param  list<int>  $storeIds
     */
    private function countUpcomingAppointments(array $storeIds, Carbon $from, Carbon $to): int
    {
        if ($storeIds === []) {
            return 0;
        }

        return (int) Rdv::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', RdvStatus::Active->value)
            ->whereBetween('start', [$from, $to])
            ->count();
    }

    private const CHART_DAYS = 7;

    /**
     * @param  list<int>  $storeIds
     * @return list<array{date: string, revenue_ttc: float, invoice_count: int}>
     */
    private function salesDailySeries(array $storeIds, Carbon $now): array
    {
        $start = $now->copy()->subDays(self::CHART_DAYS - 1)->startOfDay();
        $end = $now->copy()->endOfDay();
        $buckets = $this->emptyDateBuckets($start, self::CHART_DAYS);

        if ($storeIds === []) {
            return $this->formatSalesBuckets($buckets);
        }

        $invoices = Invoice::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', InvoiceStatus::Pending->value)
            ->whereBetween('invoice_date', [$start, $end])
            ->get(['invoice_date', 'total']);

        foreach ($invoices as $invoice) {
            $day = Carbon::parse($invoice->invoice_date)->toDateString();

            if (! isset($buckets[$day])) {
                continue;
            }

            $buckets[$day]['invoice_count']++;
            $buckets[$day]['revenue_ttc'] += (float) $invoice->total;
        }

        return $this->formatSalesBuckets($buckets);
    }

    /**
     * @param  list<int>  $storeIds
     * @return list<array{date: string, count: int}>
     */
    private function customersDailySeries(array $storeIds, Carbon $now): array
    {
        $start = $now->copy()->subDays(self::CHART_DAYS - 1)->startOfDay();
        $end = $now->copy()->endOfDay();
        $buckets = $this->emptyCountBuckets($start, self::CHART_DAYS);

        if ($storeIds === []) {
            return $this->formatCountBuckets($buckets);
        }

        $customers = Customer::query()
            ->whereIn('store_id', $storeIds)
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at']);

        foreach ($customers as $customer) {
            $day = Carbon::parse($customer->created_at)->toDateString();

            if (isset($buckets[$day])) {
                $buckets[$day]['count']++;
            }
        }

        return $this->formatCountBuckets($buckets);
    }

    /**
     * @param  list<int>  $storeIds
     * @return list<array{date: string, count: int}>
     */
    private function appointmentsDailySeries(array $storeIds, Carbon $now): array
    {
        $start = $now->copy()->startOfDay();
        $buckets = $this->emptyCountBuckets($start, self::CHART_DAYS);

        if ($storeIds === []) {
            return $this->formatCountBuckets($buckets);
        }

        $end = $start->copy()->addDays(self::CHART_DAYS - 1)->endOfDay();

        $appointments = Rdv::query()
            ->whereIn('store_id', $storeIds)
            ->where('status', RdvStatus::Active->value)
            ->whereBetween('start', [$start, $end])
            ->get(['start']);

        foreach ($appointments as $appointment) {
            $day = Carbon::parse($appointment->start)->toDateString();

            if (isset($buckets[$day])) {
                $buckets[$day]['count']++;
            }
        }

        return $this->formatCountBuckets($buckets);
    }

    /**
     * @return array<string, array{invoice_count: int, revenue_ttc: float}>
     */
    private function emptyDateBuckets(Carbon $start, int $days): array
    {
        $buckets = [];

        for ($offset = 0; $offset < $days; $offset++) {
            $date = $start->copy()->addDays($offset)->toDateString();
            $buckets[$date] = [
                'invoice_count' => 0,
                'revenue_ttc' => 0.0,
            ];
        }

        return $buckets;
    }

    /**
     * @return array<string, array{count: int}>
     */
    private function emptyCountBuckets(Carbon $start, int $days): array
    {
        $buckets = [];

        for ($offset = 0; $offset < $days; $offset++) {
            $date = $start->copy()->addDays($offset)->toDateString();
            $buckets[$date] = ['count' => 0];
        }

        return $buckets;
    }

    /**
     * @param  array<string, array{invoice_count: int, revenue_ttc: float}>  $buckets
     * @return list<array{date: string, revenue_ttc: float, invoice_count: int}>
     */
    private function formatSalesBuckets(array $buckets): array
    {
        return array_map(
            static fn (string $date, array $bucket): array => [
                'date' => $date,
                'revenue_ttc' => round($bucket['revenue_ttc'], 2),
                'invoice_count' => $bucket['invoice_count'],
            ],
            array_keys($buckets),
            array_values($buckets),
        );
    }

    /**
     * @param  array<string, array{count: int}>  $buckets
     * @return list<array{date: string, count: int}>
     */
    private function formatCountBuckets(array $buckets): array
    {
        return array_map(
            static fn (string $date, array $bucket): array => [
                'date' => $date,
                'count' => $bucket['count'],
            ],
            array_keys($buckets),
            array_values($buckets),
        );
    }
}
