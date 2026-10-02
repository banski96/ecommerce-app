<?php

namespace Tests\Feature\Customer\Checkout;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CheckoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    use TestHelpers;
    public function test_customer_can_checkout_selected_cart_item(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 9,]);
        $product2 = Product::factory()->create(['stock_quantity' => 10,]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
        $cartItem2 = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product2->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),['cart_items' => [$cartItem2->cart_item_id]],);

        $response->assertOk();

        $response->assertViewHas('items', function ($items) use ($cartItem2) {
            return $items->contains('cart_item_id', $cartItem2->cart_item_id);
        });
    }

    public function test_customer_cannot_checkout_without_cart_items_field(): void
    {
        $customer = $this->createCustomer();

        $response = $this->actingAs($customer)
            ->from(route('cart.view'))
            ->post(route('checkout.page'));

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHasErrors('cart_items');
    }

    public function test_customer_cannot_checkout_with_empty_cart_items(): void
    {
        $customer = $this->createCustomer();

        $response = $this->actingAs($customer)
            ->from(route('cart.view'))
            ->post(route('checkout.page'), [
                'cart_items' => [],
            ]);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHasErrors('cart_items');
    }

    public function test_customer_cannot_checkout_with_cart_item_not_an_array(): void
    {
        $customer = $this->createCustomer();

        $response = $this->actingAs($customer)
            ->from(route('cart.view'))
            ->post(route('checkout.page'), ['cart_items' => 1]);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHasErrors('cart_items');
    }

    public function test_customer_cannot_checkout_with_cart_item_with_non_integer(): void
    {
        $customer = $this->createCustomer();

        $response = $this->actingAs($customer)
            ->from(route('cart.view'))
            ->post(route('checkout.page'), ['cart_items' => ['3', '2', 'f']]);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHasErrors('cart_items.2');
    }

    public function test_multiple_selected_items_work(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 9,]);
        $product2 = Product::factory()->create(['stock_quantity' => 10,]);
        $product3 = Product::factory()->create(['stock_quantity' => 1,]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
        $cartItem2 = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product2->product_id,
            'quantity' => 1,
        ]);

        $cartItem3 = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product3->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),[
                'cart_items' => [
                    $cartItem->cart_item_id,
                    $cartItem2->cart_item_id,
                    $cartItem3->cart_item_id
                ]
            ],);

        $response->assertOk();

        $response->assertViewHas('cartItemIds', [
            $cartItem->cart_item_id,
            $cartItem2->cart_item_id,
            $cartItem3->cart_item_id,
        ]);
    }

    public function test_total_is_calculated_correctly(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create(['price' => 10,]);

        $product2 = Product::factory()
            ->create(['price' => 50,]);

        $product3 = Product::factory()
            ->create(['price' => 100,]);

        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);
        $cartItem2 = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product2->product_id,
            'quantity' => 1,
        ]);

        $cartItem3 = CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product3->product_id,
            'quantity' => 1,
        ]);

        $cartItemIds = [
            $cartItem->cart_item_id,
            $cartItem2->cart_item_id,
            $cartItem3->cart_item_id
        ];

        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),[
                'cart_items' => $cartItemIds
            ]);

        $response->assertViewHas( 'total', 170 );
    }

    public function test_invalid_cart_item_checkout(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        CartItem::factory()->create([
            'cart_item_id' => 1,
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),['cart_items' => [2]],);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHas(
            'error',
            'Cart item does not exist.'
        );
    }

    public function test_customer_without_a_cart_cannot_checkout(): void
    {
        $customer = $this->createCustomer();
        $customer2 = $this->createCustomer();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        $cartItem = CartItem::factory()->create([
            'cart_item_id' => 1,
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer2)
            ->post(route('checkout.page'),['cart_items' => [$cartItem->cart_item_id]],);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHas(
            'error',
            'You dont have existing cart.'
        );
    }

    public function test_customer_cannot_checkout_another_customer_cart_item(): void
    {
        $customer = $this->createCustomer();
        $customer2 = $this->createCustomer();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);
        Cart::factory()->create(['user_id' => $customer2->user_id,]);

        $cartItem = CartItem::factory()->create([
            'cart_item_id' => 1,
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer2)
            ->post(route('checkout.page'),['cart_items' => [$cartItem->cart_item_id]],);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHas(
            'error',
            'Cart item does not exist.'
        );
    }

    public function test_customer_cannot_checkout_with_quantity_greater_than_available_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create([
                'stock_quantity' => 10,
                'reserved_stock' => 8,
            ]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);
        $cartItem = CartItem::factory()->create([
            'cart_item_id' => 1,
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 9,
        ]);
        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),['cart_items' => [$cartItem->cart_item_id]],);

        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHas(
            'error',
            'Cart has greater quantity than available stock.'
        );
    }

    public function test_customer_can_checkout_with_quantity_equal_to_available_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create([
                'stock_quantity' => 10,
                'reserved_stock' => 8,
            ]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);
        $cartItem = CartItem::factory()->create([
            'cart_item_id' => 1,
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);
        $response = $this->actingAs($customer)
            ->post(route('checkout.page'),['cart_items' => [$cartItem->cart_item_id]],);

        $response->assertOk();

        $response->assertViewHas('cartItemIds', [
            $cartItem->cart_item_id,
        ]);
    }
}
