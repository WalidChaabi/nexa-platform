<?php

namespace Tests\Feature\Jobs;

use App\Contracts\Integrations\SmsGateway;
use App\Integrations\Sms\SmsResult;
use App\Jobs\ActivateAccountJob;
use App\Jobs\SendCampaignSmsJob;
use App\Jobs\SendPaymentConfirmationEmailJob;
use App\Jobs\SendPaymentConfirmationSmsJob;
use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_sms_job_is_queueable(): void
    {
        Queue::fake();

        SendCampaignSmsJob::dispatch('0612345678', 'Hello', 'DEMO', 1, 2);

        Queue::assertPushed(SendCampaignSmsJob::class, function (SendCampaignSmsJob $job) {
            return $job->phone === '0612345678'
                && $job->senderId === 'DEMO'
                && $job->storeId === 1
                && $job->campaignId === 2;
        });
    }

    public function test_campaign_sms_job_sends_via_gateway(): void
    {
        $gateway = $this->createMock(SmsGateway::class);
        $gateway->expects($this->once())
            ->method('send')
            ->with('0612345678', 'Promo', 'STORE')
            ->willReturn(SmsResult::success('OK'));

        $this->app->instance(SmsGateway::class, $gateway);

        (new SendCampaignSmsJob('0612345678', 'Promo', 'STORE'))->handle($gateway);
    }

    public function test_activate_account_job_activates_owner_and_store_users(): void
    {
        $owner = User::factory()->owner()->create(['is_active' => 0]);
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $employer = User::factory()->employer()->create([
            'store_id' => $store->id,
            'is_active' => 0,
        ]);

        (new ActivateAccountJob($owner->id, [$store->id]))->handle();

        $this->assertSame(1, $owner->fresh()->is_active);
        $this->assertSame(1, $employer->fresh()->is_active);
    }

    public function test_payment_confirmation_jobs_implement_should_queue(): void
    {
        $jobs = [
            new SendPaymentConfirmationEmailJob(1, [1, 2], 'CMD-1'),
            new SendPaymentConfirmationSmsJob(1, [1, 2], 'CMD-1'),
            new ActivateAccountJob(1, [1]),
            new SendCampaignSmsJob('06', 'msg', 'ID'),
        ];

        foreach ($jobs as $job) {
            $this->assertInstanceOf(ShouldQueue::class, $job);
        }
    }
}
