<?php

namespace App\Integrations\Payment;

final readonly class PaymentRedirect
{
    /**
     * @param  array<string, string>  $fields
     */
    public function __construct(
        public string $actionUrl,
        public array $fields,
    ) {}
}
