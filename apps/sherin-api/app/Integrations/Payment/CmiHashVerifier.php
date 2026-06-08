<?php

namespace App\Integrations\Payment;

final class CmiHashVerifier
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function calculateHash(array $payload, string $storeKey): string
    {
        $postParams = array_keys($payload);
        natcasesort($postParams);

        $hashval = '';

        foreach ($postParams as $param) {
            $paramValue = html_entity_decode(
                preg_replace("/\n$/", '', (string) $payload[$param]),
                ENT_QUOTES,
                'UTF-8',
            );

            $escapedParamValue = str_replace('|', '\\|', str_replace('\\', '\\\\', $paramValue));
            $lowerParam = strtolower($param);

            if ($lowerParam !== 'hash' && $lowerParam !== 'encoding') {
                $hashval .= $escapedParamValue.'|';
            }
        }

        $escapedStoreKey = str_replace('|', '\\|', str_replace('\\', '\\\\', $storeKey));
        $hashval .= $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);

        return base64_encode(pack('H*', $calculatedHashValue));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function matches(array $payload, string $storeKey): bool
    {
        $received = $payload['HASH'] ?? $payload['hash'] ?? null;

        if (! is_string($received) || $received === '') {
            return false;
        }

        return hash_equals($this->calculateHash($payload, $storeKey), $received);
    }
}
