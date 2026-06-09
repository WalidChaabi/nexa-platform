<?php

namespace Tests\Feature\Scheduler;

use App\Enums\RdvStatus;
use App\Jobs\SendCampaignSmsJob;
use App\Models\Customer;
use App\Models\Rdv;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class SchedulerCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_birthday_campaign_command_exits_successfully(): void
    {
        $this->artisan('nexa:birthday-campaign')
            ->assertSuccessful();
    }

    public function test_cross_selling_command_exits_successfully(): void
    {
        Bus::fake();

        $this->artisan('nexa:cross-selling')
            ->assertSuccessful();
    }

    public function test_rendez_vous_command_exits_successfully(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'start' => Carbon::parse('2026-06-08 10:20:00'),
            'status' => RdvStatus::Active->value,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-06-08 10:00:00'));

        try {
            $this->artisan('nexa:rendez-vous')
                ->assertSuccessful();

            Bus::assertDispatched(SendCampaignSmsJob::class);
        } finally {
            Carbon::setTestNow();
        }
    }
}
