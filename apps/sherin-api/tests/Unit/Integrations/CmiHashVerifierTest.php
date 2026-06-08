<?php

namespace Tests\Unit\Integrations;

use App\Integrations\Payment\CmiHashVerifier;
use Tests\TestCase;

class CmiHashVerifierTest extends TestCase
{
    public function test_matches_returns_false_when_hash_missing(): void
    {
        $verifier = new CmiHashVerifier;

        $this->assertFalse($verifier->matches(['oid' => 'CMD-1'], 'store-key'));
    }

    public function test_matches_returns_true_for_valid_callback_payload(): void
    {
        config(['integrations.cmi.store_key' => 'TESTSTOREKEY']);

        $payload = [
            'amount' => '100.00',
            'oid' => 'CMD-42',
            'ProcReturnCode' => '00',
        ];

        $verifier = new CmiHashVerifier;
        $payload['HASH'] = $verifier->calculateHash($payload, 'TESTSTOREKEY');

        $this->assertTrue($verifier->matches($payload, 'TESTSTOREKEY'));
    }

    public function test_matches_returns_false_when_hash_tampered(): void
    {
        $payload = [
            'amount' => '100.00',
            'oid' => 'CMD-42',
            'HASH' => 'invalid-hash',
        ];

        $verifier = new CmiHashVerifier;

        $this->assertFalse($verifier->matches($payload, 'TESTSTOREKEY'));
    }
}
