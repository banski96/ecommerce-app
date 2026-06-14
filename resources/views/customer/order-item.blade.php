@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Your Orders</h2>
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
            @foreach($order->items as $item)
                <div class="card-body d-flex align-items-center py-3">
                    <img src="{{ $item->product->product_image }}"
                        alt="Product Image"
                        class="rounded border me-3 bg-light"
                        style="width: 70px; height: 70px; object-fit: cover; flex-shrink: 0;">

                    <div class="flex-grow-1 min-w-0 me-3">
                        <h6 class="text-dark fw-bold mb-1 text-truncate">{{$item->product->product_name}}</h6>
                        <p class="text-muted small mb-0 text-truncate" style="max-width: 300px;">{{$item->product->description}}</p>
                    </div>

                    <div class="d-flex align-items-center gap-4 text-nowrap">
                        <div class="text-center">
                            <span class="text-muted small d-block">Unit Price</span>
                            <span class="text-dark fw-medium">${{$item->product->price}}</span>
                        </div>

                        <div class="text-center px-2">
                            <span class="text-muted small d-block">Qty</span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded">X{{$item->quantity}}</span>
                        </div>

                        <div class="text-end" style="min-width: 80px;">
                            <span class="text-muted small d-block">Sub Total Price</span>
                            <span class="text-dark fw-bold fs-5">${{$item->product->price * $item->quantity}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="card-footer bg-white py-3 d-flex justify-content-end">
                <div class="text-end">
                    <span class="text-muted small text-uppercase">Grand Total:</span>
                    <h4 class="text-dark fw-bold mb-0">${{ number_format($order->items->sum(fn($item) => $item->product->price * $item->quantity), 2) }}</h4>
                </div>
            </div>

        </div>

</div>

@endsection
