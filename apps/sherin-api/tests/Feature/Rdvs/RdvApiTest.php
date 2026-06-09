<?php

namespace Tests\Feature\Rdvs;

use App\Enums\RdvStatus;
use App\Jobs\SendCampaignSmsJob;
use App\Models\Customer;
use App\Models\Epingles;
use App\Models\Rdv;
use App\Models\Store;
use App\Models\User;
use App\Services\RdvReminderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class RdvApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_store_appointments(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
        ]);
        Rdv::factory()->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/rdvs')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_employer_can_create_appointment(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        $start = now()->addDay()->format('Y-m-d H:i:s');

        $this->actingAs($employer)
            ->postJson('/api/v1/rdvs', [
                'customer_id' => $customer->id,
                'title' => 'Coupe',
                'event_id' => '#ef4444',
                'start' => $start,
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Coupe')
            ->assertJsonPath('data.store_id', $store->id);
    }

    public function test_employer_cannot_create_appointment_for_other_store_customer(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $customer = Customer::factory()->create();

        $this->actingAs($employer)
            ->postJson('/api/v1/rdvs', [
                'customer_id' => $customer->id,
                'title' => 'Blocked',
                'event_id' => '#000000',
                'start' => now()->addDay()->toDateTimeString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['customer_id']);
    }

    public function test_employer_can_reschedule_appointment(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $rdv = Rdv::factory()->create(['store_id' => $store->id]);

        $newStart = now()->addDays(3)->format('Y-m-d H:i:s');

        $this->actingAs($employer)
            ->putJson("/api/v1/rdvs/{$rdv->id}", ['start' => $newStart])
            ->assertOk()
            ->assertJsonPath('data.id', $rdv->id);
    }

    public function test_employer_can_list_store_services(): void
    {
        $store = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);

        Epingles::query()->create([
            'store_id' => $store->id,
            'name' => 'Coloration',
            'color' => '#3b82f6',
        ]);

        $this->actingAs($employer)
            ->getJson("/api/v1/epingles?store_id={$store->id}")
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Coloration');
    }

    public function test_reminder_service_queues_sms_for_upcoming_appointments(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $customer = Customer::factory()->create([
            'store_id' => $store->id,
            'phone' => '0611002200',
            'firstname' => 'Leila',
        ]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'title' => 'Brushing',
            'start' => Carbon::parse('2026-06-08 10:15:00'),
            'status' => RdvStatus::Active->value,
        ]);

        $result = app(RdvReminderService::class)
            ->sendUpcomingReminders(Carbon::parse('2026-06-08 10:00:00'));

        $this->assertSame(1, $result['queued']);
        Bus::assertDispatched(SendCampaignSmsJob::class);
    }

    public function test_reminder_service_skips_appointments_outside_thirty_minute_window(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'start' => Carbon::parse('2026-06-08 11:00:00'),
            'status' => RdvStatus::Active->value,
        ]);

        $result = app(RdvReminderService::class)
            ->sendUpcomingReminders(Carbon::parse('2026-06-08 10:00:00'));

        $this->assertSame(0, $result['queued']);
        Bus::assertNothingDispatched();
    }

    public function test_reminder_service_deduplicates_within_same_hour(): void
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

        $reference = Carbon::parse('2026-06-08 10:00:00');
        Carbon::setTestNow($reference);

        try {
            $service = app(RdvReminderService::class);

            $first = $service->sendUpcomingReminders($reference);
            $second = $service->sendUpcomingReminders($reference);

            $this->assertSame(1, $first['queued']);
            $this->assertSame(0, $second['queued']);
            Bus::assertDispatchedTimes(SendCampaignSmsJob::class, 1);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_reminder_service_skips_cancelled_appointments(): void
    {
        Bus::fake();

        $store = Store::factory()->create(['sender_id' => 'DEMO']);
        $customer = Customer::factory()->create(['store_id' => $store->id]);

        Rdv::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'start' => Carbon::parse('2026-06-08 10:15:00'),
            'status' => RdvStatus::Cancelled->value,
        ]);

        $result = app(RdvReminderService::class)
            ->sendUpcomingReminders(Carbon::parse('2026-06-08 10:00:00'));

        $this->assertSame(0, $result['queued']);
        Bus::assertNothingDispatched();
    }

    public function test_admin_can_list_all_appointments(): void
    {
        $admin = User::factory()->admin()->create();
        Rdv::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/rdvs')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
