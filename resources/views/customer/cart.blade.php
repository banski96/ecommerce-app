@extends('layouts.customerLayout')
@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">Your Shopping Cart</h2>

    @if($cartItems->count())
    <form action="{{ route('checkout.page') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- Left Column: Items List (8 Columns) --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm mb-3 border">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                        <input type="checkbox" id="select-all" class="form-check-input">
                        <label for="select-all" class="form-check-label fw-semibold ms-1">Select All Items</label>
                    </div>
                </div>

                @foreach($cartItems as $item)
                <div class="card border shadow-sm rounded-3 mb-3 bg-white list-group-item-container" id="cart-item-{{ $item->product->product_id }}">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3 list-group-item border-0 p-0 m-0">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input item-checkbox" type="checkbox" name="cart_items[]" value="{{ $item->product->product_id }}">
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="p-2 border rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                                    <img src="{{ $item->product->product_image }}" class="img-fluid rounded" alt="{{ $item->product->name }}" style="max-height: 100%; object-fit: contain;">
                                </div>
                            </div>

                            <div class="col">
                                <h6 class="fw-bold mb-1" style="font-size: 1.05rem;">{{ $item->product->name }}</h6>
                                <p class="mb-0 text-muted item-price-display" data-raw-price="{{ $item->product->price }}">
                                    Price: ${{ number_format($item->product->price, 2) }}
                                </p>
                            </div>

                            <div class="col-auto text-end d-flex flex-column align-items-end justify-content-center">
                                <label class="small text-muted mb-1">Qty</label>
                                <input type="number" name="quantities[{{ $item->product->product_id }}]"
                                    class="form-control form-control-sm text-center fw-bold item-quantity-input"
                                    style="width:75px; border-radius: 8px;"
                                    value="{{ $item->quantity }}"
                                    min="1">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right Column: Sticky Order Summary Sidebar (4 Columns) --}}
            <div class="col-lg-4">
                <div class="card border shadow-sm rounded-3 bg-white sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Order Summary</h4>

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Selected Items Count:</span>
                            <span id="selected-count" class="fw-semibold">0</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Shipping:</span>
                            <span class="text-success fw-semibold">FREE</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fw-bold text-dark fs-5">Total Amount:</span>
                            <span class="fw-extrabold text-dark fs-3">$<span id="cart-total">0.00</span></span>
                        </div>

                        <button type="submit" class="btn w-100 rounded-pill fw-bold text-white shadow-sm py-3 btn-checkout-cta"
                                style="background-color: #d97706; border: none;">
                            Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <div class="text-center py-5">
        <i class="bi bi-cart-x text-muted" style="font-size: 3.5rem;"></i>
        <p class="mt-3 text-muted fs-5">Your shopping cart is completely empty.</p>
    </div>
    @endif
</div>

<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const quantities = document.querySelectorAll('.item-quantity-input');
    const selectedCountDisplay = document.getElementById('selected-count');

    function updateTotal() {
        let total = 0;
        let checkedCount = 0;

        checkboxes.forEach((cb, i) => {
            if(cb.checked) {
                checkedCount++;

                // Pull price safely straight out of our dataset attribute, ignoring commas or symbols
                const priceElement = cb.closest('.list-group-item-container').querySelector('.item-price-display');
                const price = parseFloat(priceElement.dataset.rawPrice);

                const quantity = parseInt(quantities[i].value) || 1;
                total += price * quantity;
            }
        });

        // Format total back seamlessly with thousand-separator commas
        document.getElementById('cart-total').innerText = total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Dynamic summary layout updater
        if (selectedCountDisplay) {
            selectedCountDisplay.innerText = checkedCount;
        }
    }

    // Event Listeners setup
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateTotal();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', function() {
        // Uncheck 'Select All' manually if an individual card gets unchecked
        if(!this.checked) selectAll.checked = false;
        // Turn on 'Select All' if everything ends up ticked manually
        if(document.querySelectorAll('.item-checkbox:checked').length === checkboxes.length) selectAll.checked = true;

        updateTotal();
    }));

    quantities.forEach(q => q.addEventListener('input', updateTotal));

    // Core structural load initializer
    updateTotal();
</script>

<style>
    /* Premium touch handling feedback styling overrides */
    .btn-checkout-cta:hover {
        background-color: #b45309 !important;
    }
    .btn-checkout-cta:active {
        transform: scale(0.98);
    }
</style>
@endsection
