<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    protected $stripeService;

    protected $checkoutService;

    public function __construct(StripeService $stripeService, CheckoutService $checkoutService)
    {
        $this->stripeService = $stripeService;
        $this->checkoutService = $checkoutService;
    }

    public function checkout(CheckoutRequest $request)
    {
        $user = auth()->user();
        $cartItemIds = $request->validated()['cart_items'];
        // TODO: decide if we put the stock check in this or in place order
        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
        if ( !$cart ){
            return redirect()
                ->route('cart.view')
                ->with('error', 'You dont have existing cart.');
        }
        $items = $cart->items->whereIn('cart_item_id', $cartItemIds);
        // calculate total (server-side safe)
        if ( $items->isEmpty() ){
            return redirect()
                ->route('cart.view')
                ->with('error', 'Cart item does not exist.');
        }
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        // TODO: this will get the total price but since the item price is dependent to product so if
        //the price change and we send an invoice the total will not match prices but it is correct.
        return view('customer.checkout', compact('items', 'total', 'cartItemIds'));
    }

    public function placeOrder(Request $request)
    {
        // TODO: handle the decrement in stocks and out of stocks
        $cartItemIds = $request->cart_items; // add validations
        $user = auth()->user();

        // 1. Get cart with items + products
        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
        if (! $cart) {
            return redirect()->back()->with('error', 'Cart not found');
        }
        $items = $cart->items->whereIn('product_id', $cartItemIds);

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No Item found!');
        }
        // Create order
        $order = $this->checkoutService->createOrder($user, $cart, $items, $request);
        // create session for stripe
        try {
            \Log::info('Creating Stripe session', [
                'order_id' => $order->order_id,
                'total' => $order->total_amount,
            ]);
            $session = $this->stripeService->createCheckoutSession($order);
            \Log::info(
                'Stripe session created',
                [
                    'order_id' => $order->order_id,
                    'stripe_session' => $session->id,
                ]
            );
            $order->update([
                'stripe_session' => $session->id,
            ]);
            // clear cart
            $this->checkoutService->clearCart($cart, $cartItemIds);

            return redirect($session->url);

        } catch (\Exception $e) {
            \Log::error(
                'Stripe session failed',
                [
                    'order_id' => $order->order_id,
                    'error' => $e->getMessage(),
                ]
            );

            return redirect()->back()->with('error', 'Payment failed. Please try again.');
        }

    }
}
