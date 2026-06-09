<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * @return array{
     *     store: array{id: int, name: string},
     *     period: array{from: string, to: string},
     *     summary: array{invoice_count: int, total_paid: float, total_ht: float, total_ttc: float},
     *     rows: list<array<string, mixed>>
     * }
     */
    public function salesReport(int $storeId, Carbon $from, Carbon $to, ?int $forSexe = null): array
    {
        $query = Invoice::query()
            ->with(['customer'])
            ->where('store_id', $storeId)
            ->where('status', InvoiceStatus::Pending->value)
            ->whereBetween('invoice_date', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        if ($forSexe !== null && $forSexe > 0) {
            $query->where('for_sexe', $forSexe === 2 ? 0 : $forSexe);
        }

        $invoices = $query->orderBy('invoice_date')->get();

        return [
            'store' => $this->storeMeta($storeId),
            'period' => $this->periodMeta($from, $to),
            'summary' => [
                'invoice_count' => $invoices->count(),
                'total_paid' => round((float) $invoices->sum('paid_amount'), 2),
                'total_ht' => round((float) $invoices->sum('total_ht'), 2),
                'total_ttc' => round((float) $invoices->sum('total'), 2),
            ],
            'rows' => $invoices->map(fn (Invoice $invoice) => [
                'id' => $invoice->id,
                'invoice_date' => $invoice->invoice_date?->toIso8601String(),
                'customer_name' => $invoice->customer
                    ? trim("{$invoice->customer->firstname} {$invoice->customer->lastname}") ?: $invoice->customer->phone
                    : null,
                'customer_phone' => $invoice->customer?->phone,
                'total_ht' => (float) $invoice->total_ht,
                'total_ttc' => (float) $invoice->total,
                'paid_amount' => (float) $invoice->paid_amount,
                'points' => (int) $invoice->points,
            ])->values()->all(),
        ];
    }

    /**
     * @return array{
     *     store: array{id: int, name: string},
     *     period: array{from: string, to: string},
     *     summary: array{customer_count: int},
     *     rows: list<array<string, mixed>>
     * }
     */
    public function customersReport(int $storeId, Carbon $from, Carbon $to): array
    {
        $customers = Customer::query()
            ->where('store_id', $storeId)
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get();

        return [
            'store' => $this->storeMeta($storeId),
            'period' => $this->periodMeta($from, $to),
            'summary' => [
                'customer_count' => $customers->count(),
            ],
            'rows' => $customers->map(fn (Customer $customer) => [
                'id' => $customer->id,
                'display_name' => trim("{$customer->firstname} {$customer->lastname}") ?: $customer->phone,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'points' => $customer->points,
                'created_at' => $customer->created_at?->toIso8601String(),
            ])->values()->all(),
        ];
    }

    /**
     * @return array{
     *     store: array{id: int, name: string},
     *     period: array{from: string, to: string},
     *     summary: array{
     *         product_count: int,
     *         total_quantity: int,
     *         total_revenue: float,
     *         total_margin: float
     *     },
     *     rows: list<array<string, mixed>>
     * }
     */
    public function productsReport(int $storeId, Carbon $from, Carbon $to): array
    {
        $rows = DB::table('invoice_products')
            ->join('invoices', 'invoice_products.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_products.product_id', '=', 'products.id')
            ->where('products.store_id', $storeId)
            ->where('invoices.status', InvoiceStatus::Pending->value)
            ->whereBetween('invoices.invoice_date', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy(
                'invoice_products.product_id',
                'products.label',
                'products.price',
                'products.promotion_price',
            )
            ->select([
                'invoice_products.product_id',
                'products.label',
                'products.price',
                'products.promotion_price',
            ])
            ->selectRaw('SUM(invoice_products.qte) as quantity_sold')
            ->selectRaw('SUM(invoice_products.qte * invoice_products.price) as revenue')
            ->orderByDesc('quantity_sold')
            ->get();

        $totalQuantity = 0;
        $totalRevenue = 0.0;
        $totalMargin = 0.0;

        $mapped = $rows->map(function ($row) use (&$totalQuantity, &$totalRevenue, &$totalMargin) {
            $quantity = (int) $row->quantity_sold;
            $revenue = round((float) $row->revenue, 2);
            $unitMargin = (float) $row->price - (float) $row->promotion_price;
            $margin = round($unitMargin * $quantity, 2);

            $totalQuantity += $quantity;
            $totalRevenue += $revenue;
            $totalMargin += $margin;

            return [
                'product_id' => (int) $row->product_id,
                'label' => $row->label,
                'quantity_sold' => $quantity,
                'revenue' => $revenue,
                'margin' => $margin,
            ];
        })->values()->all();

        return [
            'store' => $this->storeMeta($storeId),
            'period' => $this->periodMeta($from, $to),
            'summary' => [
                'product_count' => count($mapped),
                'total_quantity' => $totalQuantity,
                'total_revenue' => round($totalRevenue, 2),
                'total_margin' => round($totalMargin, 2),
            ],
            'rows' => $mapped,
        ];
    }

    /**
     * @return array{id: int, name: string}
     */
    private function storeMeta(int $storeId): array
    {
        $store = Store::query()->findOrFail($storeId);

        return [
            'id' => $store->id,
            'name' => $store->name,
        ];
    }

    /**
     * @return array{from: string, to: string}
     */
    private function periodMeta(Carbon $from, Carbon $to): array
    {
        return [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
    }
}
