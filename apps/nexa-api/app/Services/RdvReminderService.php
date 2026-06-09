<?php

namespace App\Services;

use App\Enums\RdvStatus;
use App\Jobs\SendCampaignSmsJob;
use App\Models\Rdv;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class RdvReminderService
{
    /**
     * @return array{checked: int, queued: int, skipped: int}
     */
    public function sendUpcomingReminders(?Carbon $referenceTime = null): array
    {
        $now = $referenceTime ?? now();
        $windowEnd = $now->copy()->addMinutes(30);

        $appointments = Rdv::query()
            ->with(['customer', 'store'])
            ->where('status', RdvStatus::Active->value)
            ->whereBetween('start', [$now, $windowEnd])
            ->get();

        $queued = 0;
        $skipped = 0;

        foreach ($appointments as $rdv) {
            $customer = $rdv->customer;
            $store = $rdv->store;

            if (! $customer || ! $store) {
                $skipped++;

                continue;
            }

            $cacheKey = "rdv_reminder:{$rdv->id}:{$now->format('Y-m-d-H')}";

            if (Cache::has($cacheKey)) {
                $skipped++;

                continue;
            }

            $name = trim("{$customer->firstname}") ?: 'client';
            $time = $rdv->start?->format('d/m/Y H:i') ?? '';
            $message = "Rappel RDV {$store->name}: {$rdv->title} le {$time}. Bonjour {$name}.";

            SendCampaignSmsJob::dispatch(
                phone: (string) $customer->phone,
                message: $message,
                senderId: (string) ($store->sender_id ?: 'NEXA'),
                storeId: (int) $store->id,
                campaignId: null,
            );

            Cache::put($cacheKey, true, $now->copy()->addHour());

            $queued++;

            IntegrationLogger::info('rdv.reminder_queued', [
                'rdv_id' => $rdv->id,
                'store_id' => $store->id,
                'phone_masked' => IntegrationLogger::maskPhone((string) $customer->phone),
            ]);
        }

        return [
            'checked' => $appointments->count(),
            'queued' => $queued,
            'skipped' => $skipped,
        ];
    }
}
