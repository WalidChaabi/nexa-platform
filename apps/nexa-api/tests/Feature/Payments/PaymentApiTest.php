<?php

namespace Tests\Feature\Payments;

use App\Enums\PaymentHistoryStatus;
use App\Integrations\Payment\CmiHashVerifier;
use App\Jobs\ActivateAccountJob;
use App\Jobs\SendPaymentConfirmationEmailJob;
use App\Jobs\SendPaymentConfirmationSmsJob;
use App\Models\Customer;
use App\Models\MessageCustomer;
use App\Models\PaymentHistory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_list_own_payment_history(): void
    {
        $owner = User::factory()->owner()->create();
        $ownPayment = PaymentHistory::factory()->create(['owner_id' => $owner->id]);
        PaymentHistory::factory()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/payments')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownPayment->id);
    }

    public function test_admin_can_list_all_payment_history(): void
    {
        $admin = User::factory()->admin()->create();
        PaymentHistory::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/payments')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_owner_cannot_view_other_owner_payment(): void
    {
        $owner = User::factory()->owner()->create();
        $payment = PaymentHistory::factory()->create();

        $this->actingAs($owner)
            ->getJson("/api/v1/payments/{$payment->id}")
            ->assertForbidden();
    }

    public function test_owner_can_preview_checkout(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/payments/preview')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'order_id',
                    'lines',
                    'total_ht',
                    'total_ttc',
                    'tva_rate',
                    'can_checkout',
                ],
            ]);
    }

    public function test_owner_cannot_checkout_when_nothing_to_pay(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->postJson('/api/v1/payments/checkout')
            ->assertStatus(422);
    }

    public function test_cmi_callback_is_idempotent_for_paid_order(): void
    {
        $payment = PaymentHistory::factory()->create([
            'cmd' => 'F-01-01-2026-A-120000',
            'status' => PaymentHistoryStatus::Paid->value,
        ]);

        $payload = $this->signedCallbackPayload($payment->cmd, '00');

        $this->post('/api/v1/payments/cmi/callback', $payload)
            ->assertOk()
            ->assertSee('ACTION=POSTAUTH');

        $this->assertDatabaseHas('historique_paiements', [
            'id' => $payment->id,
            'status' => PaymentHistoryStatus::Paid->value,
        ]);
    }

    public function test_cmi_callback_marks_pending_payment_as_paid(): void
    {
        Bus::fake();

        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $payment = PaymentHistory::factory()->pending()->create([
            'owner_id' => $owner->id,
            'cmd' => 'F-02-01-2026-A-130000',
        ]);

        Cache::put('cmi_pending_payment:'.$payment->cmd, [
            'owner_id' => $owner->id,
            'store_ids' => [$store->id],
            'payment_id' => $payment->id,
        ], now()->addHour());

        $payload = $this->signedCallbackPayload($payment->cmd, '00');

        $this->post('/api/v1/payments/cmi/callback', $payload)
            ->assertOk()
            ->assertSee('ACTION=POSTAUTH');

        $this->assertDatabaseHas('historique_paiements', [
            'id' => $payment->id,
            'status' => PaymentHistoryStatus::Paid->value,
        ]);

        Bus::assertDispatched(SendPaymentConfirmationSmsJob::class);
        Bus::assertDispatched(SendPaymentConfirmationEmailJob::class);
        Bus::assertDispatched(ActivateAccountJob::class);
    }

    public function test_owner_can_initiate_checkout_when_unpaid_messages_exist(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        MessageCustomer::query()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'message' => 'Happy birthday!',
            'type' => 'birthday',
            'payed' => 0,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/payments/preview')
            ->assertOk()
            ->assertJsonPath('data.can_checkout', true)
            ->assertJsonPath('data.lines.1.quantity', 1);

        $this->actingAs($owner)
            ->postJson('/api/v1/payments/checkout')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'action_url',
                    'fields' => [
                        'oid',
                        'amount',
                        'hash',
                    ],
                ],
            ]);
    }

    public function test_cmi_callback_rejects_invalid_hash(): void
    {
        $payment = PaymentHistory::factory()->pending()->create([
            'cmd' => 'F-03-01-2026-A-140000',
        ]);

        $this->post('/api/v1/payments/cmi/callback', [
            'oid' => $payment->cmd,
            'ProcReturnCode' => '00',
            'HASH' => 'invalid',
        ])
            ->assertOk()
            ->assertSee('FAILURE');

        $this->assertDatabaseHas('historique_paiements', [
            'id' => $payment->id,
            'status' => PaymentHistoryStatus::Pending->value,
        ]);
    }

    public function test_cmi_callback_does_not_redispatch_fulfillment_when_already_paid(): void
    {
        Bus::fake();

        $payment = PaymentHistory::factory()->create([
            'cmd' => 'F-04-01-2026-A-150000',
            'status' => PaymentHistoryStatus::Paid->value,
        ]);

        $payload = $this->signedCallbackPayload($payment->cmd, '00');

        $this->post('/api/v1/payments/cmi/callback', $payload)
            ->assertOk()
            ->assertSee('ACTION=POSTAUTH');

        Bus::assertNothingDispatched();
    }

    /**
     * @return array<string, string>
     */
    private function signedCallbackPayload(string $orderId, string $procReturnCode): array
    {
        $storeKey = (string) config('integrations.cmi.store_key', 'test-store-key');
        config(['integrations.cmi.store_key' => $storeKey]);

        $fields = [
            'oid' => $orderId,
            'ProcReturnCode' => $procReturnCode,
            'amount' => '120.00',
        ];

        $fields['HASH'] = (new CmiHashVerifier)->calculateHash($fields, $storeKey);

        return $fields;
    }
}
