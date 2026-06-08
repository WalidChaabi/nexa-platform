<?php

namespace Tests\Feature\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\CampaignHistorique;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_list_campaign_types(): void
    {
        Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson('/api/v1/campaigns')
            ->assertOk()
            ->assertJsonPath('data.0.type', 'birthday');
    }

    public function test_owner_can_list_store_campaign_details(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $otherStore = Store::factory()->create();
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);

        CampaignDetail::factory()->create([
            'store_id' => $store->id,
            'campaign_id' => $campaign->id,
        ]);
        CampaignDetail::factory()->create([
            'store_id' => $otherStore->id,
            'campaign_id' => $campaign->id,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/campaign-details')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_owner_can_create_campaign_detail_with_filter(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);

        $response = $this->actingAs($owner)
            ->postJson('/api/v1/campaign-details', [
                'store_id' => $store->id,
                'campaign_id' => $campaign->id,
                'reduction' => 15,
                'active' => 1,
                'filter' => [
                    'age_min' => 18,
                    'age_max' => 60,
                    'sexe' => 'both',
                    'points' => 50,
                    'last_visit' => 0,
                    'heure_start' => '09:00',
                    'heure_end' => '18:00',
                ],
                'messages' => [
                    ['message' => 'Happy birthday!', 'date_start' => 0],
                ],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.reduction', 15)
            ->assertJsonPath('data.campaign.type', 'birthday')
            ->assertJsonPath('data.filter.points', 50);

        $this->assertDatabaseHas('campaign_details', [
            'store_id' => $store->id,
            'campaign_id' => $campaign->id,
            'reduction' => 15,
        ]);
    }

    public function test_owner_cannot_create_campaign_for_other_store(): void
    {
        $owner = User::factory()->owner()->create();
        $otherStore = Store::factory()->create();
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);

        $this->actingAs($owner)
            ->postJson('/api/v1/campaign-details', [
                'store_id' => $otherStore->id,
                'campaign_id' => $campaign->id,
                'reduction' => 10,
                'filter' => [
                    'age_min' => 0,
                    'age_max' => 100,
                    'sexe' => 'both',
                    'points' => 0,
                    'last_visit' => 0,
                    'heure_start' => '00:00',
                    'heure_end' => '23:59',
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    public function test_owner_can_get_filter_defaults_for_store(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        Customer::factory()->create([
            'store_id' => $store->id,
            'points' => 42,
        ]);

        $this->actingAs($owner)
            ->getJson("/api/v1/campaign-details/filter-defaults?store_id={$store->id}")
            ->assertOk()
            ->assertJsonPath('data.max_points', 42)
            ->assertJsonPath('data.sexe', 'both');
    }

    public function test_owner_can_view_campaign_detail_with_audience_count(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);
        $filter = Filter::factory()->create(['points' => 100]);
        $detail = CampaignDetail::factory()->create([
            'store_id' => $store->id,
            'campaign_id' => $campaign->id,
            'filter_id' => $filter->id,
        ]);

        Customer::factory()->count(2)->create([
            'store_id' => $store->id,
            'points' => 10,
            'birth' => now()->subYears(30),
        ]);

        $this->actingAs($owner)
            ->getJson("/api/v1/campaign-details/{$detail->id}")
            ->assertOk()
            ->assertJsonPath('data.audience_count', 2);
    }

    public function test_owner_can_toggle_campaign_active_state(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $campaign = Campaign::query()->create(['name' => 'birthday', 'type' => 'birthday']);
        $detail = CampaignDetail::factory()->create([
            'store_id' => $store->id,
            'campaign_id' => $campaign->id,
            'active' => 1,
        ]);

        $this->actingAs($owner)
            ->putJson("/api/v1/campaign-details/{$detail->id}", ['active' => 0])
            ->assertOk()
            ->assertJsonPath('data.active', 0);
    }

    public function test_owner_can_list_campaign_historiques(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $customer = Customer::factory()->create(['store_id' => $store->id]);
        $invoice = Invoice::factory()->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
        ]);

        CampaignHistorique::query()->create([
            'camp_id' => 1,
            'invoice_id' => $invoice->id,
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'phone' => $customer->phone,
            'code_red' => 'ABC123',
            'start_date' => now(),
            'status' => 1,
            'is_payed' => 0,
        ]);

        $this->actingAs($owner)
            ->getJson('/api/v1/campaign-historiques')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code_red', 'ABC123');
    }

    public function test_admin_can_list_all_campaign_details(): void
    {
        $admin = User::factory()->admin()->create();
        CampaignDetail::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson('/api/v1/campaign-details')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
