<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_profile(): void
    {
        $owner = User::factory()->owner()->create([
            'firstname' => 'Old',
            'lastname' => 'Name',
            'phone' => '0600000000',
        ]);

        $this->actingAs($owner)
            ->putJson('/api/v1/auth/profile', [
                'firstname' => 'Karim',
                'lastname' => 'Owner',
                'phone' => '0611223344',
            ])
            ->assertOk()
            ->assertJsonPath('user.firstname', 'Karim')
            ->assertJsonPath('user.lastname', 'Owner')
            ->assertJsonPath('user.phone', '0611223344');

        $this->assertDatabaseHas('users', [
            'id' => $owner->id,
            'firstname' => 'Karim',
            'lastname' => 'Owner',
            'phone' => '0611223344',
        ]);
    }

    public function test_employer_can_update_profile(): void
    {
        $employer = User::factory()->employer()->create();

        $this->actingAs($employer)
            ->putJson('/api/v1/auth/profile', [
                'phone' => '0699887766',
            ])
            ->assertOk()
            ->assertJsonPath('user.phone', '0699887766');
    }

    public function test_profile_update_cannot_change_protected_fields(): void
    {
        $owner = User::factory()->owner()->create([
            'email' => 'owner@nexa.test',
            'is_admin' => 2,
        ]);

        $this->actingAs($owner)
            ->putJson('/api/v1/auth/profile', [
                'firstname' => 'Safe',
                'email' => 'hacked@nexa.test',
                'is_admin' => 1,
                'store_id' => 99,
            ])
            ->assertOk()
            ->assertJsonPath('user.firstname', 'Safe')
            ->assertJsonPath('user.email', 'owner@nexa.test')
            ->assertJsonPath('user.role', 'owner');

        $this->assertDatabaseHas('users', [
            'id' => $owner->id,
            'email' => 'owner@nexa.test',
            'is_admin' => 2,
        ]);
    }

    public function test_user_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->owner()->create([
            'password' => 'old-password',
        ]);

        $this->actingAs($user)
            ->putJson('/api/v1/auth/password', [
                'current_password' => 'old-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Password updated.');

        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_password_change_rejects_invalid_current_password(): void
    {
        $user = User::factory()->owner()->create([
            'password' => 'old-password',
        ]);

        $this->actingAs($user)
            ->putJson('/api/v1/auth/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);
    }

    public function test_guest_cannot_update_profile_or_password(): void
    {
        $this->putJson('/api/v1/auth/profile', ['firstname' => 'Nope'])
            ->assertUnauthorized();

        $this->putJson('/api/v1/auth/password', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertUnauthorized();
    }
}
