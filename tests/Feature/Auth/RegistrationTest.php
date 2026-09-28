<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'terms_version' => '2026-09-29',
        ]);
        $this->assertNotNull(\App\Models\User::where('email', 'test@example.com')->value('terms_accepted_at'));
    }

    public function test_registration_requires_terms_acceptance(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/register')->assertSessionHasErrors('terms');

        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_logged_in_users_do_not_see_join_online_in_public_navigation(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertDontSee('data-i18n="nav.join">Join Online', false);
    }

    public function test_guests_can_still_see_join_online_in_public_navigation(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-i18n="nav.join">Join Online', false);
    }
}
