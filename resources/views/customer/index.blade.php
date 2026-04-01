@extends('layouts.customerLayout')

@section('content')

<!-- ✅ Hero Banner -->
<div class="bg-primary text-white p-4 rounded mb-4 text-center">
    <h3>Welcome to MyShop</h3>
    <p>Best deals today 🔥</p>
</div>

<!-- ✅ Product Grid -->
<div class="row">
    @for ($i = 1; $i <= 8; $i++)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card product-card h-100 shadow-sm">

                <img src="https://via.placeholder.com/300" class="card-img-top">

                <div class="card-body p-2">
                    <h6 class="card-title">Product {{ $i }}</h6>
                    <p class="text-danger mb-1">$20</p>

                    <button class="btn btn-sm btn-primary w-100">
                        Add to Cart
                    </button>
                </div>

            </div>
        </div>
    @endfor
</div>

@endsection