<?php

namespace App\Integrations\Sms;

use App\Contracts\Integrations\SmsGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BulkSmsGateway implements SmsGateway
{
    public function send(string $phone, string $message, string $senderId): SmsResult
    {
        $token = config('integrations.bulksms.token');
        $endpoint = config('integrations.bulksms.endpoint');

        if (empty($token)) {
            Log::warning('sms.send.skipped', ['reason' => 'missing_bulksms_token']);

            return SmsResult::skipped('missing_bulksms_token');
        }

        $response = Http::asForm()
            ->timeout((int) config('integrations.bulksms.timeout', 15))
            ->post($endpoint, [
                'token' => $token,
                'tel' => $phone,
                'message' => $message,
                'shortcode' => $senderId,
            ]);

        Log::info('sms.sent', [
            'tel_masked' => strlen($phone) > 4 ? substr($phone, 0, 4).'****' : '****',
            'shortcode' => $senderId,
            'status' => $response->status(),
        ]);

        if (! $response->successful()) {
            return SmsResult::failed('http_error', $response->body());
        }

        return SmsResult::success($response->body());
    }
}
