<div class="card border shadow-sm rounded-3 bg-white order-summary">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-4">
            Order Summary
        </h4>


        <div class="d-flex justify-content-between mb-3 text-muted">

            <span>
                Selected Items Count:
            </span>

            <span
                id="{{ $countId }}"
                class="fw-semibold"
            >
                0
            </span>

        </div>


        <div class="d-flex justify-content-between mb-3 text-muted">

            <span>
                Shipping:
            </span>

            <span class="text-success fw-semibold">
                FREE
            </span>

        </div>


        <hr class="my-3">


        <div class="d-flex justify-content-between align-items-baseline mb-4">

            <span class="fw-bold text-dark fs-5">
                Total Amount:
            </span>

            <span class="fw-bold text-dark fs-3">
                $<span id="{{ $totalId }}">0.00</span>
            </span>

        </div>


        <button
            type="submit"
            @if($formId)
                form="{{ $formId }}"
            @endif
            class="btn w-100 rounded-pill fw-bold text-white shadow-sm py-3 btn-checkout-cta"
        >
            Proceed to Checkout

            <i class="bi bi-arrow-right ms-2"></i>
        </button>

    </div>

</div>
