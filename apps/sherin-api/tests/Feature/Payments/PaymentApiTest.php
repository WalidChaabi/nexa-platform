<?php

namespace Tests\Feature\Payments;

use App\Enums\PaymentHistoryStatus;
use App\Integrations\Payment\CmiHashVerifier;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $payment = PaymentHistory::factory()->pending()->create([
            'cmd' => 'F-02-01-2026-A-130000',
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
