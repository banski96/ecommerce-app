@extends('layouts.customerLayout')
@section('content')
<div class="container my-5 orders-container">

    <h2 class="mb-4 fw-bold">Your Orders</h2>

    <div class="card mb-4 shadow-sm border-0">

        {{-- Order Header --}}
        <div class="card-header bg-white d-flex justify-content-between order-card-header p-3">
            <div>
                <span class="text-muted small text-uppercase">Order Placed</span>

                <p class="mb-0 fw-bold fs-5">
                    {{ $order->created_at->format('M d, Y') }}
                </p>
            </div>

            <div>
                <span class="badge bg-info text-dark px-3 py-2">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        {{-- Order Items --}}
        @foreach($order->items as $item)
            <div class="order-item">

                {{-- Product Information --}}
                <div class="order-product">

                    <img
                        src="{{ $item->product->product_image }}"
                        alt="{{ $item->product->product_name }}"
                        class="order-product-image rounded border"
                    >

                    <div class="order-product-info">
                        <h6 class="text-dark fw-bold mb-1">
                            {{ $item->product->product_name }}
                        </h6>

                        <p class="text-muted small mb-0">
                            {{ $item->product->description }}
                        </p>
                    </div>

                </div>

                {{-- Pricing Information --}}
                <div class="order-pricing">

                    <div class="order-price-column">
                        <span class="order-label">Unit Price</span>

                        <span class="text-dark fw-medium order-price-value">
                            ${{ number_format($item->unit_price, 2) }}
                        </span>
                    </div>

                    <div class="order-price-column">
                        <span class="order-label">Quantity</span>

                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                            x{{ $item->quantity }}
                        </span>
                    </div>

                    <div class="order-subtotal">
                        <span class="order-label">Subtotal</span>

                        <span class="text-dark fw-bold order-price-value">
                            ${{ number_format($item->unit_price * $item->quantity, 2) }}
                        </span>
                    </div>

                </div>

            </div>
        @endforeach

        {{-- Grand Total --}}
        <div class="card-footer bg-white p-3 d-flex justify-content-end">
            <div class="text-end">
                <span class="text-muted small text-uppercase">
                    Grand Total
                </span>

                <div class="order-total">
                    ${{ number_format(
                        $order->items->sum(
                            fn($item) => $item->unit_price * $item->quantity
                        ),
                        2
                    ) }}
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
