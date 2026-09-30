<?php

namespace Tests\Support;

use App\Models\User;

trait TestHelpers
{
    private function createCustomer(): User
    {
        return User::factory()->create();
    }
}
