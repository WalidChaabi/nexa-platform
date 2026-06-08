<?php

namespace App\Services;

use App\Jobs\SendCampaignSmsJob;
use App\Models\CrossProduct;
use App\Models\InvoiceProduct;
use App\Models\SmsCrossSelling;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Carbon;

class CrossSellingAutomationService
{
    /**
     * @return array{rules_processed: int, sms_queued: int, skipped: int}
     */
    public function runDaily(?Carbon $referenceDate = null): array
    {
        $today = ($referenceDate ?? now())->startOfDay();
        $queued = 0;
        $skipped = 0;
        $rulesProcessed = 0;

        $rules = CrossProduct::query()
            ->with(['product.store'])
            ->get();

        foreach ($rules as $rule) {
            $rulesProcessed++;
            $product = $rule->product;

            if (! $product || ! $product->store) {
                $skipped++;

                continue;
            }

            $targetInvoiceDate = $today->copy()->subDays($rule->days)->toDateString();

            $lines = InvoiceProduct::query()
                ->where('product_id', $rule->product_id)
                ->whereHas('invoice', function ($query) use ($targetInvoiceDate): void {
                    $query->whereDate('invoice_date', $targetInvoiceDate);
                })
                ->with(['invoice.customer', 'invoice.store'])
                ->get();

            foreach ($lines as $line) {
                $invoice = $line->invoice;
                $customer = $invoice?->customer;
                $store = $invoice?->store ?? $product->store;

                if (! $customer || ! $store) {
                    $skipped++;

                    continue;
                }

                $alreadySent = SmsCrossSelling::query()
                    ->where('store_id', $store->id)
                    ->where('cross_product_id', $rule->product_id)
                    ->whereDate('sent_date', $today)
                    ->exists();

                if ($alreadySent) {
                    $skipped++;

                    continue;
                }

                $message = str_replace(
                    ['(Prénom)', '(Prenom)'],
                    [(string) $customer->firstname, (string) $customer->firstname],
                    $rule->message,
                );

                SendCampaignSmsJob::dispatch(
                    phone: (string) $customer->phone,
                    message: $message,
                    senderId: (string) ($store->sender_id ?: 'SHERIN'),
                    storeId: (int) $store->id,
                    campaignId: null,
                );

                SmsCrossSelling::query()->create([
                    'store_id' => $store->id,
                    'cross_product_id' => $rule->product_id,
                    'sent_date' => $today,
                    'credit' => 2,
                    'status' => 0,
                ]);

                $queued++;

                IntegrationLogger::info('cross_selling.sms_queued', [
                    'store_id' => $store->id,
                    'product_id' => $rule->product_id,
                    'phone_masked' => IntegrationLogger::maskPhone((string) $customer->phone),
                ]);
            }
        }

        return [
            'rules_processed' => $rulesProcessed,
            'sms_queued' => $queued,
            'skipped' => $skipped,
        ];
    }
}
