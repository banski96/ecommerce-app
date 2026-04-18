@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

    @if($cartItems->count())
    <form action="{{ route('checkout.page') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Select All -->
                <div class="mb-3">
                    <input type="checkbox" id="select-all" class="form-check-input">
                    <label for="select-all" class="form-check-label">Select All</label>
                </div>

                <!-- Cart Items -->
                @foreach($cartItems as $item)
                <div class="list-group mb-3" id="cart-item-{{ $item->product->product_id }}">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input item-checkbox" type="checkbox" name="cart_items[]" value="{{ $item->product->product_id }}">
                        </div>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($item->product->product_image)}}" class="img-thumbnail me-3" style="width:80px" alt="Product Image">
                            <div>
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="mb-0 text-muted">Price: ${{ number_format($item->product->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" name="quantities[{{ $item->product->product_id }}]" class="form-control form-control-sm" style="width:70px" value="{{ $item->quantity }}" min="1">
                        </div>
                    </div>
                </div>
                @endforeach
                <h4>Total Selected: $<span id="cart-total">0.00</span></h4>
                <button type="submit" class="btn add-btn">Checkout Selected Items</button>
            </div>
        </div>
    </form>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>

<!-- Optional JS for Select All -->
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const quantities = document.querySelectorAll('input[type="number"]');

    function updateTotal() {
    let total = 0;

    checkboxes.forEach((cb, i) => {
        if(cb.checked) {
            const price = parseFloat(cb.closest('.list-group-item')
                .querySelector('p.text-muted').innerText.replace('Price: $',''));
            const quantity = parseInt(quantities[i].value);
            total += price * quantity;
        }
    });

        document.getElementById('cart-total').innerText = total.toFixed(2);
    }

    // Add event listeners
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateTotal();
    });
    checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
    quantities.forEach(q => q.addEventListener('input', updateTotal));
    // Initial total
    updateTotal();
</script>
@endsection
