<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Base Membership');
        $response->assertSee('Premium Membership');
        $response->assertSee('FITPASS');
        $response->assertSee('data-membership-price="2800"', false);
        $response->assertSee('id="personal-training"', false);
        $response->assertSee('id="fitness-first"', false);
        $response->assertSee('id="training-goals"', false);
        $response->assertSee('id="highlights"', false);
        $response->assertSee('Leaner');
        $response->assertSee('Well-being');
        $response->assertSee('Athletic');
        $response->assertSee('Stronger');
        $response->assertSee('Flexible ways to train');
    }

    public function test_authenticated_user_can_still_view_public_homepage(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_inactive_members_do_not_show_as_active_on_dashboard(): void
    {
        $user = User::factory()->create([
            'membership_status' => 'inactive',
            'membership_plan' => 'none',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Inactive member');
        $response->assertDontSee('Active member');
    }
}
