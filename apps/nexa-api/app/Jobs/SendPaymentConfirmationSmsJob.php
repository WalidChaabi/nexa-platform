<?php

namespace App\Jobs;

use App\Support\Logging\IntegrationLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendPaymentConfirmationSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  list<int>  $storeIds
     */
    public function __construct(
        public int $ownerId,
        public array $storeIds,
        public string $orderId,
    ) {}

    public function handle(): void
    {
        DB::table('messages_customers')
            ->whereIn('store_id', $this->storeIds)
            ->where('payed', 0)
            ->update(['payed' => 1]);

        IntegrationLogger::info('payment.confirmation.sms', [
            'owner_id' => $this->ownerId,
            'store_ids' => $this->storeIds,
            'order_id' => $this->orderId,
        ]);
    }
}
