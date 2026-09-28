<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_gym_conversations_are_sent_to_ai_instead_of_faq(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Start with 3 strength sessions a week and 20 minutes of cardio. Focus on good form, hydration, and a simple meal plan that keeps protein high.',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/chat', [
            'session_id' => 'session-1',
            'message' => 'I am a beginner and want to build muscle and lose fat.',
        ]);

        $response->assertOk();
        $response->assertJsonPath('reply', 'Start with 3 strength sessions a week and 20 minutes of cardio. Focus on good form, hydration, and a simple meal plan that keeps protein high.');
    }
}
