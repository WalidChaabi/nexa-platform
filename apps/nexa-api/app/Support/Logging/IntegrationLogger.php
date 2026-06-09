<?php

namespace App\Support\Logging;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

final class IntegrationLogger
{
    public static function channel(): LoggerInterface
    {
        return Log::channel('integrations');
    }

    public static function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        return strlen($digits) > 4 ? substr($digits, 0, 4).'****' : '****';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public static function info(string $event, array $context = []): void
    {
        self::channel()->info($event, $context);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public static function warning(string $event, array $context = []): void
    {
        self::channel()->warning($event, $context);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public static function error(string $event, array $context = []): void
    {
        self::channel()->error($event, $context);
    }
}
