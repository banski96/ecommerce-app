<?php

namespace App\Http\Controllers\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('Webhook received');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            );
        } catch (\Exception $e) {
            return response('Invalid webhook', 400);
        }

        // ✅ Payment successful
        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $orderId = $session->metadata->order_id;

            $order = Order::find($orderId);

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_intent_id' => $session->payment_intent,
                ]);
            }
        }
        \Log::info('Webhook fired', ['type' => $event->type]);

        return response('Webhook handled', 200);
    }

}