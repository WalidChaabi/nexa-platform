<?php

namespace Tests\Unit\Integrations;

use App\Integrations\Sms\BulkSmsGateway;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BulkSmsGatewayTest extends TestCase
{
    public function test_send_skips_when_token_missing(): void
    {
        config(['integrations.bulksms.token' => null]);

        $result = (new BulkSmsGateway)->send('0612345678', 'Hello', 'DEMO');

        $this->assertFalse($result->sent);
        $this->assertSame('missing_bulksms_token', $result->failureReason);
        Http::assertNothingSent();
    }

    public function test_send_posts_to_bulksms_endpoint(): void
    {
        config([
            'integrations.bulksms.token' => 'test-token',
            'integrations.bulksms.endpoint' => 'https://bulksms.test/send',
        ]);

        Http::fake([
            'https://bulksms.test/send' => Http::response('OK', 200),
        ]);

        $result = (new BulkSmsGateway)->send('0612345678', 'Hello', 'DEMO');

        $this->assertTrue($result->sent);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://bulksms.test/send'
                && $request['token'] === 'test-token'
                && $request['tel'] === '0612345678'
                && $request['shortcode'] === 'DEMO';
        });
    }
}
