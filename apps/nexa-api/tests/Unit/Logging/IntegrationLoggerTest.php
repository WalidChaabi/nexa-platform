<?php

namespace Tests\Unit\Logging;

use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class IntegrationLoggerTest extends TestCase
{
    public function test_mask_phone_redacts_tail_digits(): void
    {
        $this->assertSame('0612****', IntegrationLogger::maskPhone('0612345678'));
        $this->assertSame('****', IntegrationLogger::maskPhone('12'));
    }

    public function test_logs_use_integrations_channel(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('integrations')
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('sms.sent', ['phone_masked' => '0612****']);

        IntegrationLogger::info('sms.sent', ['phone_masked' => '0612****']);
    }

    public function test_integrations_channel_is_configured(): void
    {
        $this->assertArrayHasKey('integrations', config('logging.channels'));
        $this->assertSame('daily', config('logging.channels.integrations.driver'));
    }
}
