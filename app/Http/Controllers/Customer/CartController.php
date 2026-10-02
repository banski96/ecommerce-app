<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuantityRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class CartController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id]
        );
        $cartItems = $cart->items()->with('product')->get();

        return view('customer.cart.index', compact('cartItems'));
    }

    public function addToCart(int $productId): RedirectResponse
    {
        $product = Product::find($productId);

        if ( !$product ){
            return redirect()->route('customer.home')->with('error', 'This product is no longer available.');
        }

        if ( $product->stock_quantity <= 0 ) {
            return redirect()->route('customer.home')->with('error', 'The product is out of stock!');
        }

        $user = Auth::user(); // get the logged-in user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id]
        );

        // Check if the product already exists in the cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $productId)
            ->first();

        $availableStock = $product->stock_quantity - $product->reserved_stock;

        if ($availableStock <= 0 || ($cartItem && $cartItem->quantity >= $availableStock)) {
            return redirect()->route('customer.home')->with('error', 'You cannot add more than the available stock!');
        }

        if ($cartItem) {
            // Increment quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Add new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('customer.home')->with('success', 'Product added to cart!');
    }
    public function removeCartItem(int $productId): RedirectResponse
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->user_id)->firstOrFail();
        // This ensures the children are gone before the parent
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $productId)->first();
        if ( !$cartItem ) {
            return redirect()->route('cart.view')->with('error', 'You cannot delete nonexistent cart item!');
        }

        $cartItem->delete();

        // This will delete the cart with zero item
        if ( $cart->items()->count() === 0 ){
            $cart->delete();
        }

        return redirect()->route('cart.view')->with('success', 'Product removed from cart.');
    }

    public function updateQuantity(UpdateQuantityRequest $request)
    {
        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $validated = $request->validated();
        $productId = $validated['product_id'];
        $validatedQuantity = $validated['quantity'];
        $product = Product::findOrFail($productId);
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $productId)->first();
        if ( !$cartItem ) {
            return response()->json(['message' => 'This product is not in your cart.'], 404);
        }
        if ($product->stock_quantity <= 0) {
            return response()->json(['message' => 'Product is out of stock.'], 422);
        }

        $availableStock = $product->stock_quantity - $product->reserved_stock;

        if ( $validatedQuantity > $availableStock) {
            return response()->json(['message' => 'You cannot add more than the available stock!'], 422);
        }
            $cartItem->update([
                'quantity' => $validatedQuantity
            ]);

        return response()->json(['message' => 'Quantity updated successfully.']);
    }
}
