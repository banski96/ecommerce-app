<div
    class="modal fade"
    id="deleteCartModal"
    tabindex="-1"
    aria-labelledby="deleteCartModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title fw-bold"
                    id="deleteCartModalLabel"
                >
                    Remove Item
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                Are you sure you want to remove this product from your cart?

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <form
                    id="delete-cart-form"
                    method="POST"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Remove
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
