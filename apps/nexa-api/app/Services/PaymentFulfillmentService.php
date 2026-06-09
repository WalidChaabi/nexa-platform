<?php

namespace App\Services;

use App\Jobs\ActivateAccountJob;
use App\Jobs\SendPaymentConfirmationEmailJob;
use App\Jobs\SendPaymentConfirmationSmsJob;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Support\Authorization\StoreAccess;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Facades\DB;

class PaymentFulfillmentService
{
    /**
     * @param  list<int>  $storeIds
     */
    public function fulfill(PaymentHistory $payment, array $storeIds): void
    {
        $owner = $payment->owner;

        if (! $owner instanceof User) {
            IntegrationLogger::warning('payment.fulfillment.missing_owner', [
                'payment_id' => $payment->id,
                'order_id' => $payment->cmd,
            ]);

            return;
        }

        if ($storeIds === []) {
            $storeIds = StoreAccess::accessibleStoreIds($owner);
        }

        SendPaymentConfirmationSmsJob::dispatch($owner->id, $storeIds, $payment->cmd);
        SendPaymentConfirmationEmailJob::dispatch($owner->id, $storeIds, $payment->cmd);
        ActivateAccountJob::dispatch($owner->id, $storeIds);

        if ($storeIds !== []) {
            DB::table('sms_cross_selling')
                ->whereIn('store_id', $storeIds)
                ->where('status', 0)
                ->update(['status' => 1]);
        }

        IntegrationLogger::info('payment.fulfillment.dispatched', [
            'payment_id' => $payment->id,
            'order_id' => $payment->cmd,
            'owner_id' => $owner->id,
            'store_ids' => $storeIds,
        ]);
    }
}
