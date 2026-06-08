<?php

namespace App\Integrations\Sms;

final readonly class SmsResult
{
    public function __construct(
        public bool $sent,
        public ?string $providerResponse = null,
        public ?string $failureReason = null,
    ) {}

    public static function skipped(string $reason): self
    {
        return new self(sent: false, failureReason: $reason);
    }

    public static function success(?string $providerResponse): self
    {
        return new self(sent: true, providerResponse: $providerResponse);
    }

    public static function failed(string $reason, ?string $providerResponse = null): self
    {
        return new self(sent: false, providerResponse: $providerResponse, failureReason: $reason);
    }
}
