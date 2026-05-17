@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Your Orders</h2>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between">
                <div>
                    <span class="text-muted small uppercase">Order Placed</span>
                    <p class="mb-0 fw-bold">{{ $orders->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="badge bg-info">{{ $orders->status }}</span>
                </div>
            </div>
            @foreach($orders->items as $item)
                <div class="card-body d-flex align-items-center py-3">
                    <img src="{{ $item->product->product_image }}"
                        alt="Product Image"
                        class="rounded border me-3 bg-light"
                        style="width: 70px; height: 70px; object-fit: cover; flex-shrink: 0;">

                    <div class="flex-grow-1 min-w-0 me-3">
                        <h6 class="text-dark fw-bold mb-1 text-truncate">Product Name</h6>
                        <p class="text-muted small mb-0 text-truncate" style="max-width: 300px;">Description text goes here...</p>
                    </div>

                    <div class="d-flex align-items-center gap-4 text-nowrap">
                        <div class="text-center">
                            <span class="text-muted small d-block">Unit Price</span>
                            <span class="text-dark fw-medium">$10.00</span>
                        </div>

                        <div class="text-center px-2">
                            <span class="text-muted small d-block">Qty</span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded">x2</span>
                        </div>

                        <div class="text-end" style="min-width: 80px;">
                            <span class="text-muted small d-block">Total Price</span>
                            <span class="text-dark fw-bold fs-5">$20.00</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

</div>

@endsection
