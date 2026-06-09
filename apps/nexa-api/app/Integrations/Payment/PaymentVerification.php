<?php

namespace App\Integrations\Payment;

enum PaymentVerificationStatus: string
{
    case Approved = 'approved';
    case PostAuth = 'post_auth';
    case HashMismatch = 'hash_mismatch';
    case Declined = 'declined';
    case Incomplete = 'incomplete';
}

final readonly class PaymentVerification
{
    public function __construct(
        public PaymentVerificationStatus $status,
        public bool $hashValid,
        public ?string $procReturnCode = null,
        public ?string $gatewayResponse = null,
    ) {}

    public function isSuccessful(): bool
    {
        return in_array($this->status, [
            PaymentVerificationStatus::Approved,
            PaymentVerificationStatus::PostAuth,
        ], true);
    }
}
