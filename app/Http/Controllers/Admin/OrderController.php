<?php

namespace App\Http\Controllers\Admin;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with('items.product')->get();
        return  view('admin.orders.view-orders', compact('orders'));
    }

    public function show($id){
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.view-order-items', compact('order'));
    }

    public function destroy($id)
{
    $order = Order::findOrFail($id);

    // This ensures the children are gone before the parent
    $order->orderItems()->delete();
    $order->delete();

    return redirect()->route('orders.index')->with('success', 'Order and its items removed.');
}
}
