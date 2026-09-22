@extends('layouts.customerLayout')

@section('content')

<!-- ✅ Hero Banner -->
<div class="text-white p-4 rounded mb-4 text-center"
    style="background-image: url('/assets/banner.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 300px;">
</div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

<!-- ✅ Product Grid -->
<div class="row">
    @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card product-card h-100 shadow-sm">

                <img src="{{ $product->product_image }}" class="card-img-top">

                <div class="card-body p-2">
                    <h6 class="card-title">{{ $product->product_name }}</h6>
                    <p class="text-danger mb-1">${{ $product->price }}</p>
                    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                        @csrf
                        <x-primary-button class="w-full justify-center">Add to cart</x-primary-button>
                    </form>
                </div>

            </div>
        </div>
    @empty
    <div class="col-12 text-center my-5">
        <div class="alert alert-light border p-4 shadow-sm">
            <i class="bi bi-exclamation-circle text-muted fs-2"></i>
            <p class="mt-3 text-muted">No Products Available.".</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
