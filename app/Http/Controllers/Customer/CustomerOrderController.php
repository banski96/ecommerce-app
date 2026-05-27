<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->user_id)
            ->with(['items' => function ($query) {
                $query->limit(3); // Just get a few items for the preview
            }, 'items.product'])
            ->latest()
            ->get();

        return view('customer.profile', compact('orders'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $order = Order::where('order_id', $id)
            ->where('user_id', $user->user_id)
            ->with('items.product')->firstOrFail();

        return view('customer.order-item', compact('order'));
    }
}
