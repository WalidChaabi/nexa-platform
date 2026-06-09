<?php

namespace App\Jobs;

use App\Contracts\Integrations\SmsGateway;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @var list<int>
     */
    public array $backoff = [10, 30, 60];

    public function __construct(
        public string $phone,
        public string $message,
        public string $senderId,
        public ?int $storeId = null,
        public ?int $campaignId = null,
    ) {}

    public function handle(SmsGateway $smsGateway): void
    {
        $result = $smsGateway->send($this->phone, $this->message, $this->senderId);

        IntegrationLogger::info('job.campaign_sms.completed', [
            'store_id' => $this->storeId,
            'campaign_id' => $this->campaignId,
            'phone_masked' => IntegrationLogger::maskPhone($this->phone),
            'sent' => $result->sent,
            'failure_reason' => $result->failureReason,
        ]);

        if (! $result->sent) {
            $this->fail($result->failureReason ?? 'sms_send_failed');
        }
    }
}
