@extends('layouts.customerLayout')

@section('content')
<div class="container my-5">

    <h2>Checkout</h2>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf

        @foreach($cartItemIds as $id)
            <input type="hidden" name="cart_items[]" value="{{ $id }}">
        @endforeach

        <div class="mb-3">
            <label>Shipping Address</label>
            <input type="text" name="shipping_address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mobile Number</label>
            <input type="text" name="mobile_number" class="form-control" required>
        </div>

        <h4>Order Summary</h4>

        @foreach($items as $item)
            <p>
                {{ $item->product->name }} -
                {{ $item->quantity }} × ${{ $item->product->price }}
            </p>
        @endforeach

        <h3>Total: ${{ $total }}</h3>

        <button class="btn btn-success">Place Order</button>
    </form>

</div>
@endsection