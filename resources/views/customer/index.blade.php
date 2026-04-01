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

<!-- ✅ Product Grid -->
<div class="row">
    @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card product-card h-100 shadow-sm">

                <img src="{{ asset($product->product_image) }}" class="card-img-top">

                <div class="card-body p-2">
                    <h6 class="card-title">{{ $product->product_name }}</</h6>
                    <p class="text-danger mb-1">${{ $product->price }}</</p>

                    <button class="btn btn-sm add-btn w-100">
                        Add to Cart
                    </button>
                </div>

            </div>
        </div>
    @endforeach
</div>

@endsection