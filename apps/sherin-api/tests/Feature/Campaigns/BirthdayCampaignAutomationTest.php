<?php

namespace Tests\Feature\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Message;
use App\Models\MessageCampaign;
use App\Models\MessageCustomer;
use App\Models\Store;
use App\Models\User;
use App\Services\BirthdayCampaignAutomationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BirthdayCampaignAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_automation_creates_unpaid_birthday_messages_for_matching_customers(): void
    {
        $reference = Carbon::parse('2026-06-09');
        $store = Store::factory()->create();
        $filter = Filter::factory()->create([
            'points' => 100,
            'age_min' => 0,
            'age_max' => 100,
            'sexe' => 'both',
        ]);
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);
        $detail = CampaignDetail::factory()->create([
            'campaign_id' => $campaign->id,
            'store_id' => $store->id,
            'filter_id' => $filter->id,
            'active' => 1,
        ]);
        $message = Message::query()->create([
            'message' => 'Happy birthday [FirstName]! Code: [code]',
            'date_start' => 0,
        ]);
        MessageCampaign::query()->create([
            'message_id' => $message->id,
            'campaign_detail_id' => $detail->id,
        ]);

        $matchingCustomer = Customer::factory()->create([
            'store_id' => $store->id,
            'firstname' => 'Sara',
            'lastname' => 'Test',
            'phone' => '0611223344',
            'points' => '10',
            'birth' => Carbon::parse('1990-06-09'),
        ]);

        Customer::factory()->create([
            'store_id' => $store->id,
            'birth' => Carbon::parse('1990-07-01'),
        ]);

        $result = app(BirthdayCampaignAutomationService::class)->runDaily($reference);

        $this->assertSame(1, $result['messages_created']);
        $this->assertDatabaseHas('messages_customers', [
            'store_id' => $store->id,
            'customer_id' => $matchingCustomer->id,
            'type' => 'birthday',
            'payed' => 0,
        ]);
        $this->assertDatabaseHas('codes', [
            'customer_id' => $matchingCustomer->id,
        ]);

        $record = MessageCustomer::query()->first();
        $this->assertStringContainsString('Sara', (string) $record?->message);
    }

    public function test_automation_skips_duplicate_messages_on_same_day(): void
    {
        $reference = Carbon::parse('2026-06-09');
        $store = Store::factory()->create();
        $filter = Filter::factory()->create(['points' => 100, 'age_min' => 0, 'age_max' => 100]);
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);
        $detail = CampaignDetail::factory()->create([
            'campaign_id' => $campaign->id,
            'store_id' => $store->id,
            'filter_id' => $filter->id,
            'active' => 1,
        ]);
        $message = Message::query()->create([
            'message' => 'Hello [FirstName]',
            'date_start' => 0,
        ]);
        MessageCampaign::query()->create([
            'message_id' => $message->id,
            'campaign_detail_id' => $detail->id,
        ]);
        $customer = Customer::factory()->create([
            'store_id' => $store->id,
            'points' => '10',
            'birth' => Carbon::parse('1990-06-09'),
        ]);

        $service = app(BirthdayCampaignAutomationService::class);
        $first = $service->runDaily($reference);
        $second = $service->runDaily($reference);

        $this->assertSame(1, $first['messages_created']);
        $this->assertSame(0, $second['messages_created']);
        $this->assertSame(1, MessageCustomer::query()->count());
    }

    public function test_payment_preview_counts_unpaid_birthday_messages(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        MessageCustomer::query()->create([
            'store_id' => $store->id,
            'customer_id' => Customer::factory()->create(['store_id' => $store->id])->id,
            'message' => 'Birthday SMS',
            'type' => 'birthday',
            'payed' => 0,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/payments/preview')
            ->assertOk()
            ->assertJsonPath('data.lines.1.quantity', 1);
    }
}
