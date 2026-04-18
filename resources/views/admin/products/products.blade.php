@extends('admin.layout')

@section('content')
<h1 class="title-name">Products</h1>
<div>
    <a href="{{ route('admin.product.create') }}" class="add-btn btn mb-3">
        <i class="bi bi-plus"></i>Add Product
    </a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category ID</th>
            <th>Product Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->product_id }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock_quantity }}</td>
            <td>{{ $product->category_id }}</td>
            
            <td>
                @if($product->product_image)
                    <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" class="img-fluid" style="max-width:50px;">
                @else
                    <p>No image available</p>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.categories.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.product.destroy', $product) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection