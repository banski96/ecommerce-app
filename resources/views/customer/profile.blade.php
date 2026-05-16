@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Your Orders</h2>
    @foreach($orders as $order)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between">
                <div>
                    <span class="text-muted small uppercase">Order Placed</span>
                    <p class="mb-0 fw-bold">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="badge bg-info">{{ $order->status }}</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    @foreach($order->items as $item)
                        <img src="{{ $item->product->product_image }}" class="rounded border me-2" style="width: 60px; height: 60px; object-fit: cover;">
                    <!-- TODO: handle this -->
                        <!-- @endforeach
                    @if($order->order_items_count > 3)
                        <span class="text-muted">+ {{ $order->order_items_count - 3 }} more</span>
                    @endif -->
                </div>

                <a href="" class="btn btn-outline-primary">
                    Track / View Order
                </a>
            </div>
        </div>
    @endforeach
</div>

@endsection
