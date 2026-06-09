<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_api_requests_are_rate_limited(): void
    {
        $user = User::factory()->employer()->create();

        for ($i = 0; $i < 60; $i++) {
            $this->actingAs($user)
                ->getJson('/api/v1/stores')
                ->assertOk();
        }

        $this->actingAs($user)
            ->getJson('/api/v1/stores')
            ->assertStatus(429);
    }
}
