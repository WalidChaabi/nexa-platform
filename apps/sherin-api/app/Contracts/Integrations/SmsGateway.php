<?php

namespace App\Contracts\Integrations;

use App\Integrations\Sms\SmsResult;

interface SmsGateway
{
    public function send(string $phone, string $message, string $senderId): SmsResult;
}
