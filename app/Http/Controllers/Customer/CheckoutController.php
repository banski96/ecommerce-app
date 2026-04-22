<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StripeService;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    protected $stripeService;
    protected $checkoutService;

    public function __construct(StripeService $stripeService, CheckoutService $checkoutService)
    {
        $this->stripeService = $stripeService;
        $this->checkoutService = $checkoutService;
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();

        $cartItemIds = $request->cart_items;
        if (!$cartItemIds) {
            return redirect()->back()->with('error', 'No items selected');
        }

        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
        $items = $cart->items->whereIn('product_id', $cartItemIds);
        // calculate total (server-side safe)
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.checkout', compact('items', 'total', 'cartItemIds'));
    }

    public function placeOrder(Request $request)
    {
        $cartItemIds = $request->cart_items; # add validations
        $user = auth()->user();

        // 1. Get cart with items + products
        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
        if (!$cart) {
            return redirect()->back()->with('error', 'Cart not found');
        }
        $items = $cart->items->whereIn('product_id', $cartItemIds);

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No Item found!');
        }
        // Create order
        $order = $this->checkoutService->createOrder($user, $cart, $items, $request);
        //create session for stripe
        try{
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
                'stripe_session' => $session->id
            ]);
            // clear cart
            $this->checkoutService->clearCart($cart, $cartItemIds);
            return redirect($session->url);
            
        }catch (\Exception $e) {
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