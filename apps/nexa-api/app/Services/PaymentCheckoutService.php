<?php

namespace App\Services;

use App\Contracts\Integrations\PaymentGateway;
use App\Enums\PaymentHistoryStatus;
use App\Enums\UserRole;
use App\Integrations\Payment\PaymentInitiationRequest;
use App\Integrations\Payment\PaymentRedirect;
use App\Integrations\Payment\PaymentVerificationStatus;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Support\Authorization\StoreAccess;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentCheckoutService
{
    private const PENDING_TTL_MINUTES = 1440;

    public function __construct(private readonly PaymentGateway $paymentGateway) {}

    /**
     * @return array{
     *     order_id: string,
     *     lines: list<array{key: string, label: string, quantity: int, unit_price: float, line_total: float}>,
     *     total_ht: float,
     *     total_ttc: float,
     *     tva_rate: float,
     *     can_checkout: bool
     * }
     */
    public function preview(User $user): array
    {
        $this->assertOwner($user);

        $counts = $this->unpaidServiceCounts($user);
        $pricing = $this->pricing();
        $lines = $this->buildLines($counts, $pricing);
        $totalHt = $this->discountedTotalHt($lines);
        $tvaRate = (float) config('nexa.tva_rate');
        $totalTtc = round($totalHt * (1 + $tvaRate), 2);

        return [
            'order_id' => $this->generateOrderId(),
            'lines' => $lines,
            'total_ht' => $totalHt,
            'total_ttc' => $totalTtc,
            'tva_rate' => $tvaRate,
            'can_checkout' => $totalTtc > 0,
        ];
    }

    public function initiate(User $user): PaymentRedirect
    {
        $this->assertOwner($user);

        $preview = $this->preview($user);

        if (! $preview['can_checkout']) {
            abort(422, 'Nothing to pay for the current billing period.');
        }

        $services = [
            'emails' => $this->lineQuantity($preview['lines'], 'emails'),
            'email_price' => (float) config('nexa.email_price'),
            'sms_normal' => $this->lineQuantity($preview['lines'], 'sms_normal'),
            'sms_normale_price' => (float) config('nexa.sms_price'),
            'sms_advanced' => $this->lineQuantity($preview['lines'], 'sms_cross_selling'),
            'sms_advanced_price' => (float) config('nexa.sms_cross_selling_price'),
        ];

        $payment = PaymentHistory::query()->updateOrCreate(
            ['cmd' => $preview['order_id']],
            [
                'owner_id' => $user->id,
                'total_ht' => $preview['total_ht'],
                'total_ttc' => $preview['total_ttc'],
                'services' => json_encode($services),
                'status' => PaymentHistoryStatus::Pending->value,
            ],
        );

        $storeIds = StoreAccess::accessibleStoreIds($user);
        Cache::put($this->pendingCacheKey($payment->cmd), [
            'owner_id' => $user->id,
            'store_ids' => $storeIds,
            'payment_id' => $payment->id,
        ], now()->addMinutes(self::PENDING_TTL_MINUTES));

        $frontendUrl = rtrim((string) config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');

        return $this->paymentGateway->initiate(new PaymentInitiationRequest(
            orderId: $payment->cmd,
            amount: (float) $payment->total_ttc,
            email: (string) $user->email,
            customerName: trim("{$user->firstname} {$user->lastname}"),
            companyName: (string) config('app.name', 'Nexa'),
            phone: (string) ($user->phone ?? ''),
            address: (string) ($user->address ?? ''),
            language: app()->getLocale(),
            callbackUrl: rtrim((string) config('app.url'), '/').'/api/v1/payments/cmi/callback',
            successUrl: "{$frontendUrl}/owner/payments/return?outcome=success&oid={$payment->cmd}",
            failureUrl: "{$frontendUrl}/owner/payments/return?outcome=failed&oid={$payment->cmd}",
            shopUrl: "{$frontendUrl}/owner/payments",
        ));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleCallback(array $payload): string
    {
        $verification = $this->paymentGateway->verifyCallback($payload);
        $orderId = isset($payload['oid']) ? (string) $payload['oid'] : null;

        if ($orderId) {
            $payment = PaymentHistory::query()->where('cmd', $orderId)->first();
            $pendingContext = Cache::get($this->pendingCacheKey($orderId), []);
            $storeIds = is_array($pendingContext['store_ids'] ?? null)
                ? $pendingContext['store_ids']
                : [];

            if ($payment && $verification->isSuccessful()) {
                if ($payment->status !== PaymentHistoryStatus::Paid->value) {
                    $payment->update(['status' => PaymentHistoryStatus::Paid->value]);
                    $payment->refresh();

                    app(PaymentFulfillmentService::class)->fulfill($payment, $storeIds);
                }

                Cache::forget($this->pendingCacheKey($orderId));
            } elseif (
                $payment
                && ! $verification->isSuccessful()
                && $verification->status === PaymentVerificationStatus::Declined
            ) {
                $payment->update(['status' => PaymentHistoryStatus::Failed->value]);
                Cache::forget($this->pendingCacheKey($orderId));
            }
        }

        return match ($verification->status) {
            PaymentVerificationStatus::PostAuth => 'ACTION=POSTAUTH',
            PaymentVerificationStatus::Approved => 'APPROVED',
            PaymentVerificationStatus::HashMismatch => 'FAILURE',
            PaymentVerificationStatus::Incomplete => 'request incomplete',
            PaymentVerificationStatus::Declined => 'FAILURE',
        };
    }

    private function assertOwner(User $user): void
    {
        if ($user->role() !== UserRole::Owner) {
            abort(403, 'Only owners can access platform billing.');
        }
    }

    /**
     * @return array{emails: int, sms_normal: int, sms_cross_selling: int}
     */
    private function unpaidServiceCounts(User $user): array
    {
        $storeIds = StoreAccess::accessibleStoreIds($user);

        if ($storeIds === []) {
            return ['emails' => 0, 'sms_normal' => 0, 'sms_cross_selling' => 0];
        }

        return [
            'emails' => (int) DB::table('email_campaigns')
                ->whereIn('store_id', $storeIds)
                ->where('status', 0)
                ->count(),
            'sms_normal' => (int) DB::table('messages_customers')
                ->whereIn('store_id', $storeIds)
                ->where('payed', 0)
                ->count(),
            'sms_cross_selling' => (int) DB::table('sms_cross_selling')
                ->whereIn('store_id', $storeIds)
                ->where('status', 0)
                ->count(),
        ];
    }

    /**
     * @return array{sms_price: float, sms_cross_selling_price: float, email_price: float, exceptional_discount: float}
     */
    private function pricing(): array
    {
        return [
            'sms_price' => (float) config('nexa.sms_price'),
            'sms_cross_selling_price' => (float) config('nexa.sms_cross_selling_price'),
            'email_price' => (float) config('nexa.email_price'),
            'exceptional_discount' => (float) config('nexa.exceptional_discount'),
        ];
    }

    /**
     * @param  array{emails: int, sms_normal: int, sms_cross_selling: int}  $counts
     * @param  array{sms_price: float, sms_cross_selling_price: float, email_price: float, exceptional_discount: float}  $pricing
     * @return list<array{key: string, label: string, quantity: int, unit_price: float, line_total: float}>
     */
    private function buildLines(array $counts, array $pricing): array
    {
        return [
            [
                'key' => 'emails',
                'label' => 'Email campaigns',
                'quantity' => $counts['emails'],
                'unit_price' => $pricing['email_price'],
                'line_total' => round($counts['emails'] * $pricing['email_price'], 2),
            ],
            [
                'key' => 'sms_normal',
                'label' => 'SMS campaigns',
                'quantity' => $counts['sms_normal'],
                'unit_price' => $pricing['sms_price'],
                'line_total' => round($counts['sms_normal'] * $pricing['sms_price'], 2),
            ],
            [
                'key' => 'sms_cross_selling',
                'label' => 'Cross-selling SMS',
                'quantity' => $counts['sms_cross_selling'],
                'unit_price' => $pricing['sms_cross_selling_price'],
                'line_total' => round($counts['sms_cross_selling'] * $pricing['sms_cross_selling_price'], 2),
            ],
        ];
    }

    /**
     * @param  list<array{key: string, label: string, quantity: int, unit_price: float, line_total: float}>  $lines
     */
    private function discountedTotalHt(array $lines): float
    {
        $subtotal = array_sum(array_column($lines, 'line_total'));
        $discount = (float) config('nexa.exceptional_discount');

        return round(max(0, $subtotal - ($subtotal * $discount)), 2);
    }

    /**
     * @param  list<array{key: string, label: string, quantity: int, unit_price: float, line_total: float}>  $lines
     */
    private function lineQuantity(array $lines, string $key): int
    {
        foreach ($lines as $line) {
            if ($line['key'] === $key) {
                return $line['quantity'];
            }
        }

        return 0;
    }

    private function generateOrderId(): string
    {
        $now = now();

        return 'F-'.$now->format('d-m-Y').'-A-'.$now->format('His');
    }

    private function pendingCacheKey(string $cmd): string
    {
        return 'cmi_pending_payment:'.$cmd;
    }
}
