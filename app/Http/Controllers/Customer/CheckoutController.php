<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{

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
        $cartItemIds = $request->cart_items;
        $user = auth()->user();

        // 1. Get cart with items + products
        $cart = Cart::with('items.product')
            ->where('user_id', $user->user_id)
            ->first();
        $items = $cart->items->whereIn('product_id', $cartItemIds);

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No Item found!');
        }
        $total = 0;
        // 2. Create Order
        $order = Order::create([
            'user_id' => $user->user_id,
            'reference_number' => 'ORD-' . time(),
            'total_amount' => $total,
            'status' => 'pending',
            'mobile_number' => $request->mobile_number,
            'order_date' => now(),
            'shipping_address' => $request->shipping_address,
        ]);

        // 4. Create Order Items
        foreach ($cart->items as $item) {
            $subtotal = $item->quantity * $item->product->price;
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
            ]);
            $total += $subtotal;
        }
        
        $order->update([
            'total_amount' => $total
        ]);
        // 5. remove the selected item in the cart
        $cart->items()
        ->whereIn('product_id', $cartItemIds)
        ->delete();
        // 6. Redirect
        return redirect()->route('customer.home')
            ->with('success', 'Order placed successfully!');
    }
}