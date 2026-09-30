<div
    class="card border shadow-sm rounded-3 mb-3 bg-white list-group-item-container position-relative
        {{ $item->product->stock_quantity == 0 ? 'cart-item-out-of-stock' : '' }}"
    id="cart-item-{{ $item->product->product_id }}"
>

    {{-- Delete Button --}}
    <button
        type="button"
        class="btn btn-sm btn-outline-danger delete-cart-item position-absolute top-0 end-0 mt-2 me-2"
        data-bs-toggle="modal"
        data-bs-target="#deleteCartModal"
        data-product-id="{{ $item->product->product_id }}"
        data-delete-url="{{ route('cart.delete', $item->product->product_id) }}"
        title="Remove from cart"
    >
        <i class="bi bi-trash"></i>
    </button>


    <div class="card-body p-3">

        <div class="row align-items-center g-3">

            {{-- Select Item --}}
            <div class="col-auto">

                <div class="form-check">

                    <input
                        class="form-check-input item-checkbox {{ $item->product->stock_quantity == 0 ? 'out-of-stock-checkbox' : '' }}"
                        type="checkbox"
                        name="cart_items[]"
                        value="{{ $item->product->product_id }}"
                        {{ $item->product->stock_quantity == 0 ? 'disabled' : '' }}
                    >

                </div>

            </div>


            {{-- Product Image --}}
            <div class="col-auto">

                <div class="product-image-container">

                    <img
                        src="{{ $item->product->product_image }}"
                        class="img-fluid rounded"
                        alt="{{ $item->product->name }}"
                    >

                </div>

            </div>


            {{-- Product Information --}}
            <div class="col">

                <h6 class="fw-bold mb-1 product-name">
                    {{ $item->product->name }}
                </h6>

                <p
                    class="mb-0 text-muted item-price-display"
                    data-raw-price="{{ $item->product->price }}"
                >
                    Price: ${{ number_format($item->product->price, 2) }}
                </p>

            </div>


            {{-- Quantity --}}
            <div class="col-auto">

                <label class="small text-muted mb-1 d-block">
                    Qty
                </label>

                <input
                    type="number"
                    name="quantities[{{ $item->product->product_id }}]"
                    data-update-url="{{ route('cart.update.quantity') }}"
                    data-product-id="{{ $item->product->product_id }}"
                    class="form-control form-control-sm text-center fw-bold item-quantity-input"
                    value="{{ $item->quantity }}"
                    min="1"
                    {{ $item->product->stock_quantity == 0 ? 'disabled' : '' }}
                >

            </div>

        </div>

    </div>

</div>
