<?php

namespace App\Http\Controllers\Customer;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class CustomerOrderController extends Controller
{
    public function index(){
        $user = auth()->user();
        $orders = Order::where('user_id', $user->user_id)
        ->with(['items'=> function($query) {
                   $query->limit(3); // Just get a few items for the preview
        }, 'items.product'])
        ->latest()
        ->get();
        return view('customer.profile', compact('orders'));
    }
}
