<?php

namespace Tests\Feature\Customer\Products;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBrowsingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_customer_can_access_product_home(): void
    {
        $response = $this->actingAs($this->createCustomer())
            ->get(route('customer.home'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_customer_home(): void
    {
        $response = $this->get(route('customer.home'));
        $response->assertRedirect(route('login'));
    }

    public function test_product_information_is_displayed_on_home(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->createCustomer())
            ->get(route('customer.home'));
        $response -> assertSee($product->product_name);
        $response -> assertSee($product->price);
        $response -> assertSee($product->product_image);
    }

    public function test_multiple_products_are_displayed(): void
    {
        $products = Product::factory()->count(10)->create();
        $response = $this->actingAs($this->createCustomer()) # TODO: Refactor repeated customer authentication setup into a shared helper.
            ->get(route('customer.home'));
        foreach ($products as $product)
        {
            $response -> assertSee($product->product_name);
            $response -> assertSee($product->price);
            $response -> assertSee($product->product_image);
        }
    }

    public function test_empty_product_list_is_handled(): void
    {
        $response = $this->actingAs($this->createCustomer())
            ->get(route('customer.home'));
        $response->assertOk();
        $response -> assertSee('No Products Available.');

    }

    private function createCustomer(): User  # TODO: Move to a shared test helper when multiple test classes need it.
    {
        return User::factory()->create();
    }
}
