<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCartItemsException;
use App\Exceptions\CartNotFoundException;

class CheckoutService
{
    public function createOrder($user, $items, $total, $data)
    {
        $order = Order::create([
            'user_id' => $user->user_id,
            'reference_number' => 'ORD-' . time(),
            'total_amount' => $total,
            'status' => 'pending',
            'mobile_number' => $data['mobile_number'],
            'order_date' => now(),
            'shipping_address' => $data['shipping_address'],
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
            ]);
        }

        return $order;
    }
    public function clearCart($cart, $cartItemIds)
    {
        $cart->items()
            ->whereIn('cart_item_id', $cartItemIds)
            ->delete();
    }

    public function placeOrder($user, $data)
    {
        $cartItemIds = $data['cart_items'];

        return DB::transaction(function () use ($user, $cartItemIds, $data) {

            $cart = Cart::with('items')
                ->where('user_id', $user->user_id)
                ->first();

            if (! $cart) {
                throw new CartNotFoundException();
            }

            $items = $cart->items->whereIn('cart_item_id', $cartItemIds);

            if ($items->isEmpty()) {
                throw new InvalidCartItemsException();
            }

            if ($items->count() !== count($cartItemIds)) {
                throw new InvalidCartItemsException();
            }

            $total = 0;

            foreach ($items as $item) {

                $product = Product::where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                $availableStock = $product->stock_quantity - $product->reserved_stock;

                if ($item->quantity > $availableStock) {
                    throw new InsufficientStockException();
                }

                // Attach the locked product to this cart item.
                $item->setRelation('product', $product);

                $total += $item->quantity * $product->price;
            }

            $order = $this->createOrder(
                $user,
                $items,
                $total,
                $data
            );

            foreach ($items as $item) {
                $updatedReservedStocks = $item->quantity + $item->product->reserved_stock;
                $item->product->update([
                    'reserved_stock' => $updatedReservedStocks,
                ]);
            }

            $this->clearCart($cart, $cartItemIds);

            return $order;
        });
    }
}
