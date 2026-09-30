@extends('layouts.customerLayout')

@section('content')

<div class="container my-5">

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </div>
    @endif
    <div id="cart-message"></div>

    <h2 class="fw-bold mb-4">Your Shopping Cart</h2>

    @if($cartItems->count())

        <div class="row g-4">

            {{-- Cart Items --}}
            <div class="col-lg-8">

                <form
                    id="checkout-form"
                    action="{{ route('checkout.page') }}"
                    method="POST"
                >

                    @csrf

                    {{-- Select All --}}
                    <div class="select-all-container d-flex align-items-center bg-white p-3 rounded-3 shadow-sm mb-3 border">

                        <div class="form-check m-0 d-flex align-items-center gap-2">

                            <input
                                type="checkbox"
                                id="select-all"
                                class="form-check-input"
                            >

                            <label
                                for="select-all"
                                class="form-check-label fw-semibold ms-1"
                            >
                                Select All Items
                            </label>

                        </div>

                    </div>


                    {{-- Individual Cart Items --}}
                    @foreach($cartItems as $item)

                        @include('customer.cart.cart-item', [
                            'item' => $item
                        ])

                    @endforeach


                    {{-- Mobile Order Summary --}}
                    <div class="d-lg-none">

                        @include('customer.cart.order-summary', [
                            'countId' => 'selected-count-mobile',
                            'totalId' => 'cart-total-mobile',
                            'formId' => null
                        ])

                    </div>

                </form>

            </div>


            {{-- Desktop Order Summary --}}
            <div class="col-lg-4 d-none d-lg-block">

                @include('customer.cart.order-summary', [
                    'countId' => 'selected-count-desktop',
                    'totalId' => 'cart-total-desktop',
                    'formId' => 'checkout-form'
                ])

            </div>

        </div>

    @else

        {{-- Empty Cart --}}
        <div class="text-center py-5">

            <i class="bi bi-cart-x text-muted empty-cart-icon"></i>

            <p class="mt-3 text-muted fs-5">
                Your shopping cart is completely empty.
            </p>

        </div>

    @endif

</div>


@include('customer.cart.delete-modal')

@vite('resources/js/cart.js')
@vite('resources/css/cart.css')
@vite('resources/css/app.css')

@endsection
