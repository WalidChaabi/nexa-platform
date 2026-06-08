<?php

namespace App\Contracts\Integrations;

use App\Integrations\Payment\PaymentInitiationRequest;
use App\Integrations\Payment\PaymentRedirect;
use App\Integrations\Payment\PaymentVerification;

interface PaymentGateway
{
    public function initiate(PaymentInitiationRequest $request): PaymentRedirect;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function verifyCallback(array $payload): PaymentVerification;
}
