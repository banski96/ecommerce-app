@extends('layouts.customerLayout')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold text-dark">Checkout</h2>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf

        @foreach($cartItemIds as $id)
            <input type="hidden" name="cart_items[]" value="{{ $id }}">
        @endforeach

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                    <h4 class="mb-4 fw-semibold text-dark border-bottom pb-2">Shipping Information</h4>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Shipping Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                            <input type="text" name="shipping_address" class="form-control bg-light border-start-0 ps-0" placeholder="123 Main St, Apt 4B" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                            <input type="tel" name="mobile_number" class="form-control bg-light border-start-0 ps-0" placeholder="e.g., +1234567890" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3 position-sticky" style="top: 20px;">
                    <h4 class="mb-4 fw-bold text-dark">Order Summary</h4>

                    <div class="order-items-list mb-3 max-vh-50 overflow-auto pe-1">
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0 text-dark fw-semibold">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <span class="fw-medium text-dark">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Shipping</span>
                        <span class="text-success fw-bold">FREE</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center my-3">
                        <span class="fs-5 fw-bold text-dark">Total Amount:</span>
                        <span class="fs-4 fw-bold text-dark">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn w-100 py-3 mt-2 fw-semibold text-white d-flex align-items-center justify-content-center gap-2 transition-all"
                            style="background-color: #d97706; border: none; border-radius: 2rem;">
                        Place Order
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
