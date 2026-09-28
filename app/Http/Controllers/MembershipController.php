<?php

namespace App\Http\Controllers;

use App\Models\MembershipPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MembershipController extends Controller
{
    public function upgrade(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:Base,Premium',
        ]);

        if ($request->user()->membership_status === 'active') {
            return redirect()->route('dashboard')->with('status', 'Your membership is already active.');
        }

        $plan = $validated['plan'];
        $amount = $plan === 'Base' ? 2650 : 2800;

        return $this->createCheckout($request, $plan, 0, $amount, $plan.' Membership');
    }

    public function buyFitpass(Request $request)
    {
        $validated = $request->validate([
            'credits' => 'required|integer|in:1',
        ]);

        return $this->createCheckout($request, 'FITPASS', (int) $validated['credits'], 483, 'FITPASS credit');
    }

    public function paymentReturn()
    {
        return redirect()->route('dashboard')->with('status', 'Payment received by the checkout page. Your membership will update after PayMongo confirms the payment.');
    }

    public function webhook(Request $request)
    {
        $secret = (string) config('services.paymongo.webhook_secret');
        $signature = (string) $request->header('Paymongo-Signature');
        $rawBody = $request->getContent();

        if ($secret === '' || $signature === '' || ! hash_equals(hash_hmac('sha256', $rawBody, $secret), $signature)) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        $payload = json_decode($rawBody, true);
        $event = data_get($payload, 'data', $payload);
        $attributes = data_get($event, 'attributes', $event);
        $eventType = data_get($attributes, 'type');

        if ($eventType !== 'checkout_session.payment.paid') {
            return response()->json(['received' => true]);
        }

        $session = data_get($attributes, 'data', data_get($event, 'data'));
        $reference = data_get($session, 'attributes.reference_number');
        $sessionId = data_get($session, 'id');
        $paymentId = data_get($session, 'attributes.metadata.membership_payment_id');

        if (! is_string($reference) || ! is_string($sessionId) || ! is_string($paymentId)) {
            return response()->json(['message' => 'Invalid checkout event.'], 400);
        }

        DB::transaction(function () use ($event, $reference, $sessionId, $paymentId): void {
            $payment = MembershipPayment::where('reference_number', $reference)
                ->whereKey($paymentId)
                ->lockForUpdate()
                ->first();

            if (! $payment || $payment->checkout_session_id !== $sessionId || $payment->status === 'paid') {
                return;
            }

            $user = $payment->user()->lockForUpdate()->first();

            if (! $user) {
                return;
            }

            $payment->forceFill([
                'status' => 'paid',
                'webhook_event_id' => data_get($event, 'id'),
            ])->save();

            if ($payment->plan === 'FITPASS') {
                $user->fitpass_credits += $payment->credits;
            }

            $user->membership_plan = $payment->plan;
            $user->membership_status = 'active';
            $user->save();
        });

        return response()->json(['received' => true]);
    }

    private function createCheckout(Request $request, string $plan, int $credits, int $amount, string $description)
    {
        $secretKey = (string) config('services.paymongo.secret_key');

        if ($secretKey === '') {
            return back()->withErrors(['payment' => 'Online payments are not configured yet. Please contact the gym.']);
        }

        $payment = MembershipPayment::create([
            'user_id' => $request->user()->id,
            'plan' => $plan,
            'credits' => $credits,
            'amount' => $amount * 100,
            'reference_number' => 'IG-'.Str::upper(Str::random(16)),
            'status' => 'pending',
        ]);

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->withHeaders(['Idempotency-Key' => $payment->reference_number])
                ->timeout(15)
                ->post(config('services.paymongo.checkout_url'), [
                    'data' => [
                        'attributes' => [
                            'line_items' => [[
                                'name' => $description,
                                'amount' => $payment->amount,
                                'currency' => 'PHP',
                                'quantity' => 1,
                            ]],
                            'payment_method_types' => config('services.paymongo.payment_method_types'),
                            'success_url' => route('membership.payment.return'),
                            'cancel_url' => route('dashboard').'#membership',
                            'reference_number' => $payment->reference_number,
                            'send_email_receipt' => true,
                            'metadata' => ['membership_payment_id' => (string) $payment->id],
                        ],
                    ],
                ]);

            $checkoutUrl = $response->json('data.attributes.checkout_url');
            $sessionId = $response->json('data.id');

            if (! $response->successful() || ! is_string($checkoutUrl) || ! is_string($sessionId) || parse_url($checkoutUrl, PHP_URL_HOST) !== 'checkout.paymongo.com') {
                throw new \RuntimeException('PayMongo did not return a valid checkout session.');
            }

            $payment->update(['checkout_session_id' => $sessionId]);

            return redirect()->away($checkoutUrl);
        } catch (Throwable $exception) {
            $payment->update(['status' => 'failed']);
            Log::warning('PayMongo checkout session creation failed.', ['payment_id' => $payment->id]);

            return back()->withErrors(['payment' => 'Unable to start checkout right now. Please try again.']);
        }
    }
}
