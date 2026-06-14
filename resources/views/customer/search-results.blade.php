@extends('layouts.customerLayout')

@section('content')
    <h1 class="mb-4">Showing results for "{{ $query }}"</h1>

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

                <img src="{{ $product->product_image }}" class="card-img-top" alt="{{ $product->product_name }}">

                <div class="card-body p-2">
                    <h6 class="card-title">{{ $product->product_name }}</h6>
                    <p class="text-danger mb-1">${{ number_format($product->price, 2) }}</p>
                    <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm add-btn w-100">Add to Cart</button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="col-12 text-center my-5">
            <div class="alert alert-light border p-4 shadow-sm">
                <i class="bi bi-exclamation-circle text-muted fs-2"></i>
                <p class="mt-3 text-muted">We couldn't find any products matching "{{ $query }}".</p>
                <a href="{{ route('customer.home') }}" class="btn btn-sm btn-outline-secondary">Go Back Home</a>
            </div>
        </div>
    @endforelse
</div>
<!-- TODO: Add pagination -->
@endsection
