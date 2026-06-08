<?php

namespace App\Integrations\Payment;

final readonly class PaymentInitiationRequest
{
    public function __construct(
        public string $orderId,
        public float $amount,
        public string $email,
        public string $customerName,
        public string $companyName,
        public string $phone,
        public string $address,
        public string $language,
        public string $callbackUrl,
        public string $successUrl,
        public string $failureUrl,
        public string $shopUrl,
    ) {}
}
