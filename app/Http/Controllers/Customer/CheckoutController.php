<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\CheckoutService;
use App\Services\StripeService;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\PlaceOrderRequest;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCartItemsException;
use App\Exceptions\CartNotFoundException;

class CheckoutController extends Controller
{
    protected $stripeService;

    protected $checkoutService;

    public function __construct(StripeService $stripeService, CheckoutService $checkoutService) {
        $this->stripeService = $stripeService;
        $this->checkoutService = $checkoutService;
    }

    public function checkout(CheckoutRequest $request)
    {
        $user = auth()->user();

        $cartItemIds = $request->validated()['cart_items'];

        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();

        if (! $cart) {
            return redirect()
                ->route('cart.view')
                ->with('error', 'You dont have existing cart.');
        }

        $items = $cart->items->whereIn('cart_item_id', $cartItemIds);

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.view')
                ->with('error', 'Cart item does not exist.');
        }

        foreach ($items as $item) {
            $availableStock = $item->product->stock_quantity - $item->product->reserved_stock;

            if ($item->quantity > $availableStock) {
                return redirect()
                    ->route('cart.view')
                    ->with('error', 'Cart has greater quantity than available stock.');
            }
        }

        $total = $items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view(
            'customer.checkout',
            compact('items', 'total', 'cartItemIds')
        );
    }

    public function placeOrder(PlaceOrderRequest $request)
    {
        try{
            $order = $this->checkoutService->placeOrder(
            auth()->user(),
            $request,
        );

        } catch(InsufficientStockException){
            return redirect()
                ->route('cart.view')
                ->with('error', 'Cart has greater quantity than available stock.');
        } catch(InvalidCartItemsException){
            return redirect()
                ->route('cart.view')
                ->with('error', 'Some selected items are no longer available.');
        } catch(CartNotFoundException){
            return redirect()
                    ->route('cart.view')
                    ->with('error', 'Cart not found.');
        }

        try {
            \Log::info('Creating Stripe session', [
                'order_id' => $order->order_id,
                'total' => $order->total_amount,
            ]);

            $session = $this->stripeService->createCheckoutSession($order);

            \Log::info('Stripe session created', [
                'order_id' => $order->order_id,
                'stripe_session_id' => $session->id,
            ]);

            $order->update([
                'stripe_session_id' => $session->id,
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {

            \Log::error('Stripe session failed', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Payment failed. Please try again.');
        }
    }
}
