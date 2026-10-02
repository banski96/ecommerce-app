<?php

namespace Tests\Feature\Customer\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    use TestHelpers;

    public function test_customer_can_view_cart(): void
    {
        $response = $this->actingAs($this->createCustomer())
            ->get(route('cart.view'));
        $response->assertOk();
    }

    public function test_guest_cannot_access_customer_cart(): void
    {
        $response = $this->get(route('cart.view'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_view_cart_items(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response = $this->actingAs($customer)
            ->get(route('cart.view'));

        $response->assertOk();
        $response->assertSee($product->product_image);
    }

    // ADD TO CART TESTS
    public function test_customer_can_add_to_cart(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        // Send the request to add the product to the customer's cart
        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $cart = Cart::where('user_id', $customer->user_id)->first();

        $this->assertNotNull($cart);

        // Verify that the product was actually added to the cart.
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
    }

    public function test_customer_can_add_multiple_products_to_cart(): void
    {
        $customer = $this->createCustomer();
        $products = Product::factory()->count(3)->create();

        foreach ($products as $product) {
            $this->actingAs($customer)
                ->post(route('cart.add', $product->product_id));
        }
        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        foreach ($products as $product) {
            $this->assertDatabaseHas('cart_items', [
                'cart_id' => $cart->cart_id,
                'product_id' => $product->product_id,
                'quantity' => 1,
            ]);
        }

    }
    public function test_customer_cannot_add_nonexistent_products_to_cart(): void
    {
        $customer = $this->createCustomer();

        // Send the request to add the product to the customer's cart
        $response = $this->actingAs($customer)
            ->post(route('cart.add', 2));
        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas(
            'error',
            'This product is no longer available.'
        );
    }

    public function test_customer_cannot_add_out_of_stock_item_to_cart(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 0,]);

        $response = $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas(
            'error',
            'The product is out of stock!'
        );
    }

    public function test_customer_cannot_add_item_exceeds_stock_to_cart(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 1,]);

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response = $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas(
            'error',
            'You cannot add more than the available stock!'
        );
    }

    public function test_customer_cannot_add_item_when_quantity_reaches_available_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create([
                'stock_quantity' => 2,
                'reserved_stock' => 1,
            ]);

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response = $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas(
            'error',
            'You cannot add more than the available stock!'
        );
    }

    public function test_customer_cannot_add_item_when_all_stock_is_reserved(): void
    {
        $customer = $this->createCustomer();

        $product = Product::factory()
            ->create([
                'stock_quantity' => 10,
                'reserved_stock' => 10,
            ]);

        $response = $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $response->assertRedirect(route('customer.home'));
        $response->assertSessionHas(
            'error',
            'You cannot add more than the available stock!'
        );

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->product_id,
        ]);
    }

    public function test_add_same_product_twice(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));
        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));
        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);
    }

    // DELETE TO CART TESTS

    public function test_customer_can_delete_cart_item(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
        $this->actingAs($customer)
            ->delete(route('cart.delete', $product->product_id));
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
        ]);

    }

    public function test_customer_cannot_delete_nonexistent_cart_item(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();
        Cart::factory()->create(['user_id' => $customer->user_id]);

        $response = $this->actingAs($customer)
            ->delete(route('cart.delete', $product->product_id));
        $response->assertSessionHas(
            'error',
            'You cannot delete nonexistent cart item!'
            );

    }

    public function test_customer_cannot_delete_another_customer_cart_item(): void
    {
        $customer1 = $this->createCustomer();
        $customer2 = $this->createCustomer();
        $product = Product::factory()->create();

        Cart::factory()->create([
            'user_id' => $customer1->user_id,
        ]);

        $cart2 = Cart::factory()->create([
            'user_id' => $customer2->user_id,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart2->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer1)
            ->delete(route('cart.delete', $product->product_id));
        $response->assertRedirect(route('cart.view'));
        $response->assertSessionHas(
            'error',
            'You cannot delete nonexistent cart item!'
            );

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart2->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
    }

    public function test_deleting_last_item_removes_cart(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));

        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
        $this->actingAs($customer)
            ->delete(route('cart.delete', $product->product_id));
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
        ]);
        $this->assertDatabaseMissing('carts', [
            'cart_id' => $cart->cart_id,
        ]);

    }

    // UPDATE TO CART TESTS

    public function test_customer_can_update_item_quantity(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create(['stock_quantity' => 5,]);

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));
        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 2]);

        $response->assertOk()
            ->assertJson([ 'message' => 'Quantity updated successfully.', ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);
    }

    public function test_customer_cannot_update_item_quantity_to_zero(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));
        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 0]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
    }

    public function test_customer_cannot_update_quantity_exceeds_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 5,]);

        $this->actingAs($customer)
            ->post(route('cart.add', $product->product_id));
        $cart = Cart::where('user_id', $customer->user_id)->first();
        $this->assertNotNull($cart);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 6]);

        $response->assertUnprocessable()
            ->assertJson([ 'message' => 'You cannot add more than the available stock!', ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
    }

    public function test_customer_cannot_update_quantity_when_out_of_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 0,]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 1]);

        $response->assertUnprocessable()
            ->assertJson([ 'message' => 'Product is out of stock.', ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 1,
        ]);
    }

    public function test_customer_cannot_update_with_quantity_greater_than_available_stock(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()
            ->create([
                'stock_quantity' => 10,
                'reserved_stock' => 9,
            ]);
        $cart = Cart::factory()->create(['user_id' => $customer->user_id,]);

        CartItem::factory()->create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 2]);

        $response->assertUnprocessable()
            ->assertJson([ 'message' => 'You cannot add more than the available stock!', ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
        ]);
    }

    public function test_customer_cannot_update_nonexistent_cart_item(): void
    {
        $customer = $this->createCustomer();
        $product = Product::factory()->create(['stock_quantity' => 0,]);
        Cart::factory()->create(['user_id' => $customer->user_id,]);

        $response = $this->actingAs($customer)
            ->patchJson(route('cart.update.quantity'), ['product_id' => $product->product_id,'quantity' => 1]);

        $response->assertNotFound()
            ->assertJson([ 'message' => 'This product is not in your cart.', ]);
    }

}
