<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BulkSmsClient
{
    /**
     * Send an SMS via BulkSMS.ma API.
     */
    public function send(string $phone, string $message, string $shortcode): ?string
    {
        $token = config('services.bulksms.token');
        $url = config('services.bulksms.endpoint');

        if (empty($token)) {
            Log::warning('sms.send.skipped', ['reason' => 'missing_bulksms_token']);

            return null;
        }

        $fields = http_build_query([
            'token' => $token,
            'tel' => $phone,
            'message' => $message,
            'shortcode' => $shortcode,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        curl_close($ch);

        Log::info('sms.sent', [
            'tel_masked' => strlen($phone) > 4 ? substr($phone, 0, 4) . '****' : '****',
            'shortcode' => $shortcode,
        ]);

        return $result;
    }
}
