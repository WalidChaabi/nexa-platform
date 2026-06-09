<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CampaignFilterService
{
    /**
     * @return array{
     *     max_points: int,
     *     age_min: int,
     *     age_max: int,
     *     last_visit_months: int,
     *     heure_start: string,
     *     heure_end: string,
     *     sexe: string
     * }
     */
    public function defaultsForStore(int $storeId): array
    {
        $maxPoints = (int) (Customer::query()
            ->where('store_id', $storeId)
            ->max('points') ?? 0);

        $lastInvoiceDate = Invoice::query()
            ->where('store_id', $storeId)
            ->max('invoice_date');

        $lastVisitMonths = 0;
        if ($lastInvoiceDate) {
            $lastVisitMonths = (int) Carbon::parse($lastInvoiceDate)
                ->diffInMonths(Carbon::now()) + 1;
        }

        return [
            'max_points' => $maxPoints,
            'age_min' => 0,
            'age_max' => 100,
            'last_visit_months' => $lastVisitMonths,
            'heure_start' => '00:00:00',
            'heure_end' => '23:59:59',
            'sexe' => 'both',
        ];
    }

    public function countAudience(Filter $filter, int $storeId): int
    {
        return $this->audienceQuery($filter, $storeId)->count();
    }

    /**
     * Customers whose birthday matches the V1 scheduler window for a campaign message.
     *
     * @return Builder<Customer>
     */
    public function birthdayAudienceQuery(
        Filter $filter,
        int $storeId,
        Carbon $reference,
        int $dateStartOffset,
    ): Builder {
        $query = Customer::query()
            ->where('store_id', $storeId)
            ->where('points', '<=', $filter->points)
            ->whereNotNull('birth');

        $this->applySexeFilter($query, (string) $filter->sexe);
        $this->applyAgeFilter($query, (int) $filter->age_min, (int) $filter->age_max);

        $targetMonth = $reference->copy()->addDay()->month;
        $targetDay = $reference->copy()->addDay($dateStartOffset)->day;

        return $query
            ->whereMonth('birth', $targetMonth)
            ->whereDay('birth', $targetDay);
    }

    /**
     * @return Builder<Customer>
     */
    public function audienceQuery(Filter $filter, int $storeId): Builder
    {
        $query = Customer::query()
            ->where('store_id', $storeId)
            ->where('points', '<=', $filter->points);

        $this->applySexeFilter($query, (string) $filter->sexe);
        $this->applyAgeFilter($query, (int) $filter->age_min, (int) $filter->age_max);

        if ((int) $filter->last_visit > 0) {
            $start = Carbon::now()->subMonths((int) $filter->last_visit);
            $heureStart = $this->formatTime((string) $filter->heure_start);
            $heureEnd = $this->formatTime((string) $filter->heure_end);

            $query->whereDoesntHave('invoices', function (Builder $invoiceQuery) use ($start, $heureStart, $heureEnd): void {
                $invoiceQuery
                    ->whereBetween('invoice_date', [$start, Carbon::now()])
                    ->whereRaw('TIME(invoice_date) BETWEEN ? AND ?', [$heureStart, $heureEnd]);
            });
        }

        return $query;
    }

    /**
     * @param  Builder<Customer>  $query
     */
    private function applySexeFilter(Builder $query, string $sexe): void
    {
        $normalized = $this->normalizeSexe($sexe);

        if ($normalized === 'female') {
            $query->where('sexe', 0);

            return;
        }

        if ($normalized === 'male') {
            $query->where('sexe', 1);
        }
    }

    /**
     * @param  Builder<Customer>  $query
     */
    private function applyAgeFilter(Builder $query, int $ageMin, int $ageMax): void
    {
        $minBirth = Carbon::now()->subYears($ageMax)->startOfDay();
        $maxBirth = Carbon::now()->subYears($ageMin)->endOfDay();

        $query->whereBetween('birth', [$minBirth, $maxBirth]);
    }

    private function normalizeSexe(string $sexe): string
    {
        return match ($sexe) {
            '0', 'female' => 'female',
            '1', 'male' => 'male',
            default => 'both',
        };
    }

    private function formatTime(string $time): string
    {
        if (strlen($time) === 5) {
            return $time.':00';
        }

        return $time;
    }
}
