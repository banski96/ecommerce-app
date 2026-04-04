<?php

namespace App\Http\Controllers\Customer;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // get the logged-in user  
        if (!$user) { # TODO: this is redundant below fix later
            return redirect()->route('login')->with('error', 'You must be logged in to add items.');
        }
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id]
        );
        $cartItems = $cart->items()->with('product')->get();
        return view('customer.cart', compact('cartItems'));
    }

    public function addToCart(Request $request, $productId)
    {
        $user = Auth::user(); // get the logged-in user

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items.');
        }

        // Find or create a cart for this user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id]
        );

        // Check if the product already exists in the cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
                            ->where('product_id', $productId)
                            ->first();

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

        return redirect()->back()->with('success', 'Product added to cart!');
    }
}
