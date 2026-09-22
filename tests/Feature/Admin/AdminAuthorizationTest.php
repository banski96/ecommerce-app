<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get('admin/categories');

        $response->assertStatus(200);
    }
    public function test_customer_can_access_admin_dashboard(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->get('admin/categories');

        $response->assertStatus(403); # The result should be forbidden
    }
}
