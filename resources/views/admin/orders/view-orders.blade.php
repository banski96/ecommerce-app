@extends('admin.layout')

@section('content')
<h1 class="title-name">Orders</h1>

<table class="table">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Order ID</th>
            <th>Reference Number</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Mobile Number</th>
            <th>Shipping Address</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->user_id }}</td>
            <td>{{ $order->order_id  }}</td>
            <td>{{ $order->reference_number }}</td>
            <td>{{ $order->total_amount }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->mobile_number }}</td>
            <td>{{ $order->shipping_address }}</td>
            <td>{{ $order->payment_status }}</td>
            <td>
                <a href="{{route('admin.orders.view', $order)}}"
                    class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-eye"></i> View Details
                </a>
                <a href=""
                    class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection
