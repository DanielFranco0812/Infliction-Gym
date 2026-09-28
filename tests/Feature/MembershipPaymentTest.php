<?php

namespace Tests\Feature;

use App\Models\MembershipPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MembershipPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_membership_checkout_uses_server_price_and_does_not_activate_before_webhook(): void
    {
        config(['services.paymongo.secret_key' => 'sk_test_example']);
        Http::fake([
            'https://api.paymongo.com/v2/checkout_sessions' => Http::response([
                'data' => [
                    'id' => 'cs_test_123',
                    'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test-session'],
                ],
            ]),
        ]);

        $user = User::factory()->create(['membership_status' => 'inactive']);

        $response = $this->actingAs($user)->post(route('membership.upgrade'), ['plan' => 'Base']);

        $response->assertRedirect('https://checkout.paymongo.com/test-session');
        $this->assertDatabaseHas('membership_payments', ['plan' => 'Base', 'amount' => 265000, 'status' => 'pending']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'membership_status' => 'inactive']);
        Http::assertSent(fn (ClientRequest $request) => $request['data']['attributes']['line_items'][0]['amount'] === 265000);
    }

    public function test_invalid_membership_plan_is_rejected(): void
    {
        $user = User::factory()->create(['membership_status' => 'inactive']);

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('membership.upgrade'), ['plan' => 'Free'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('plan');

        $this->assertDatabaseCount('membership_payments', 0);
    }

    public function test_signed_paid_webhook_activates_membership_once(): void
    {
        config(['services.paymongo.webhook_secret' => 'webhook-secret']);
        $user = User::factory()->create(['membership_status' => 'inactive']);
        $payment = MembershipPayment::create([
            'user_id' => $user->id,
            'plan' => 'FITPASS',
            'credits' => 1,
            'amount' => 48300,
            'reference_number' => 'IG-TEST123',
            'checkout_session_id' => 'cs_test_123',
            'status' => 'pending',
        ]);
        $payload = [
            'data' => [
                'id' => 'evt_test_123',
                'type' => 'event',
                'attributes' => [
                    'type' => 'checkout_session.payment.paid',
                    'data' => [
                        'id' => 'cs_test_123',
                            'attributes' => [
                                'reference_number' => $payment->reference_number,
                                'metadata' => ['membership_payment_id' => (string) $payment->id],
                            ],
                    ],
                ],
            ],
        ];
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $body, 'webhook-secret');

        $this->call('POST', route('webhooks.paymongo'), [], [], [], [
            'HTTP_PAYMONGO_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $body)->assertOk();

        $this->call('POST', route('webhooks.paymongo'), [], [], [], [
            'HTTP_PAYMONGO_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $body)->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'membership_status' => 'active',
            'membership_plan' => 'FITPASS',
            'fitpass_credits' => 1,
        ]);
        $this->assertDatabaseHas('membership_payments', ['id' => $payment->id, 'status' => 'paid']);
    }

    public function test_invalid_webhook_signature_is_rejected(): void
    {
        config(['services.paymongo.webhook_secret' => 'webhook-secret']);

        $this->call('POST', route('webhooks.paymongo'), [], [], [], [
            'HTTP_PAYMONGO_SIGNATURE' => 'invalid',
            'CONTENT_TYPE' => 'application/json',
        ], '{"data":{}}')->assertUnauthorized();
    }
}