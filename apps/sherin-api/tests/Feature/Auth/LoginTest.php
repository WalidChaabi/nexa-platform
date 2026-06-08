<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@sherin.test',
            'password' => 'password',
        ]);

        $response = $this
            ->withHeader('Origin', 'http://localhost:3000')
            ->postJson('/api/v1/auth/login', [
                'email' => 'admin@sherin.test',
                'password' => 'password',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('user.role', 'admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->inactive()->create([
            'email' => 'inactive@sherin.test',
            'password' => 'password',
        ]);

        $this
            ->withHeader('Origin', 'http://localhost:3000')
            ->postJson('/api/v1/auth/login', [
                'email' => 'inactive@sherin.test',
                'password' => 'password',
            ])
            ->assertForbidden();
    }

    public function test_login_is_throttled_after_repeated_failures(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this
                ->withHeader('Origin', 'http://localhost:3000')
                ->postJson('/api/v1/auth/login', [
                    'email' => 'missing@sherin.test',
                    'password' => 'wrong-password',
                ]);
        }

        $this
            ->withHeader('Origin', 'http://localhost:3000')
            ->postJson('/api/v1/auth/login', [
                'email' => 'missing@sherin.test',
                'password' => 'wrong-password',
            ])
            ->assertStatus(429);
    }

    public function test_admin_only_route_rejects_employer(): void
    {
        $employer = User::factory()->employer()->create();

        $this->actingAs($employer)
            ->getJson('/api/v1/admin/ping')
            ->assertForbidden();
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::factory()->owner()->create();

        $this->actingAs($user)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('user.role', 'owner');
    }
}
