<?php

namespace App\Integrations\Sms;

use App\Contracts\Integrations\SmsGateway;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Facades\Http;

class BulkSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message, string $senderId): SmsResult
    {
        $token = config('integrations.bulksms.token');
        $endpoint = config('integrations.bulksms.endpoint');

        if (empty($token)) {
            IntegrationLogger::warning('sms.send.skipped', [
                'reason' => 'missing_bulksms_token',
                'phone_masked' => IntegrationLogger::maskPhone($phone),
                'sender_id' => $senderId,
            ]);

            return SmsResult::skipped('missing_bulksms_token');
        }

        $startedAt = microtime(true);

        $response = Http::asForm()
            ->timeout((int) config('integrations.bulksms.timeout', 15))
            ->post($endpoint, [
                'token' => $token,
                'tel' => $phone,
                'message' => $message,
                'shortcode' => $senderId,
            ]);

        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        if (! $response->successful()) {
            IntegrationLogger::error('sms.send.failed', [
                'phone_masked' => IntegrationLogger::maskPhone($phone),
                'sender_id' => $senderId,
                'http_status' => $response->status(),
                'duration_ms' => $durationMs,
            ]);

            return SmsResult::failed('http_error', $response->body());
        }

        IntegrationLogger::info('sms.sent', [
            'phone_masked' => IntegrationLogger::maskPhone($phone),
            'sender_id' => $senderId,
            'http_status' => $response->status(),
            'duration_ms' => $durationMs,
        ]);

        return SmsResult::success($response->body());
    }
}
