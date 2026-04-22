<?php
namespace App\Services;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutService
{
    public function createOrder($user, $cart, $items, $request)
    {
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
            'total_amount' => $total # change this, the total should be computed first before placing the order
        ]);
        return $order;
    }

    public function clearCart($cart, $cartItemIds)
    {
        $cart->items()
        ->whereIn('product_id', $cartItemIds)
        ->delete();
    }
}