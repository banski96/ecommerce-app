<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
    public function createCheckoutSession($order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                'price_data' => [
                    'currency' => 'aed',
                    'product_data' => [
                        'name' => 'Order #' .$order->reference_number,
                    ],
                    'unit_amount' => $order->total_amount * 100,  # This is needed since in stripe 1.00 -> 100.00 
                ],
                'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            
            // This serve as the link of order to stripe
            'metadata' => [
                'order_id' => $order->order_id,
            ],
        ]);
        return $session;
    }
}