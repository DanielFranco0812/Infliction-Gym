<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_legal_draft_pages_are_available_and_identified_as_drafts(): void
    {
        $this->get(route('legal.terms'))
            ->assertOk()
            ->assertSee('Terms of Service')
            ->assertSee('Draft for legal review');

        $this->get(route('legal.privacy'))
            ->assertOk()
            ->assertSee('Privacy Notice')
            ->assertSee('Draft for legal review');
    }

    public function test_application_sets_baseline_security_headers(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_unknown_page_uses_the_custom_not_found_view(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee("We couldn't find that page.", false);
    }

    public function test_public_chat_rejects_messages_over_the_size_limit(): void
    {
        $this->postJson(route('api.chat'), [
            'session_id' => 'test-session',
            'message' => str_repeat('a', 2001),
        ])->assertUnprocessable()->assertJsonValidationErrors('message');
    }
}