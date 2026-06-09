<?php

namespace Tests\Unit\Integrations;

use App\Integrations\Payment\CmiHashVerifier;
use App\Integrations\Payment\CmiPaymentGateway;
use App\Integrations\Payment\PaymentVerificationStatus;
use Tests\TestCase;

class CmiPaymentGatewayTest extends TestCase
{
    public function test_verify_callback_returns_post_auth_for_success_code(): void
    {
        config(['integrations.cmi.store_key' => 'STOREKEY']);

        $payload = [
            'amount' => '250.00',
            'oid' => 'CMD-99',
            'ProcReturnCode' => '00',
        ];

        $verifier = new CmiHashVerifier;
        $payload['HASH'] = $verifier->calculateHash($payload, 'STOREKEY');

        $result = (new CmiPaymentGateway($verifier))->verifyCallback($payload);

        $this->assertTrue($result->isSuccessful());
        $this->assertSame(PaymentVerificationStatus::PostAuth, $result->status);
        $this->assertSame('ACTION=POSTAUTH', $result->gatewayResponse);
    }

    public function test_verify_callback_returns_hash_mismatch_for_invalid_hash(): void
    {
        config(['integrations.cmi.store_key' => 'STOREKEY']);

        $result = (new CmiPaymentGateway(new CmiHashVerifier))->verifyCallback([
            'amount' => '250.00',
            'oid' => 'CMD-99',
            'HASH' => 'bad',
        ]);

        $this->assertFalse($result->isSuccessful());
        $this->assertSame(PaymentVerificationStatus::HashMismatch, $result->status);
    }

    public function test_verify_callback_returns_incomplete_without_hash(): void
    {
        $result = (new CmiPaymentGateway(new CmiHashVerifier))->verifyCallback([
            'oid' => 'CMD-99',
        ]);

        $this->assertSame(PaymentVerificationStatus::Incomplete, $result->status);
    }
}
