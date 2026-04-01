@extends('admin.layout')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow rounded-4">
                <!-- Header -->
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-semibold mb-0">Add Product</h4>
                    <small class="text-muted">Fill in the product details below</small>
                </div>

                <!-- Body -->
                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" 
                                name="product_name"
                                class="form-control form-control-lg @error('product_name') is-invalid @enderror"
                                placeholder="e.g. iPhone 15 Pro">
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price & Stock -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" 
                                        name="price"
                                        class="form-control @error('price') is-invalid @enderror" 
                                        placeholder="0.00">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Stock</label>
                                <input type="number" min="0"
                                    name="stock_quantity"
                                    class="form-control @error('stock_quantity') is-invalid @enderror" 
                                    placeholder="Available quantity">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="" selected disabled>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="4" 
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Write something about the product..."></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Image</label>
                            <input type="file" name="product_image" 
                                class="form-control @error('product_image') is-invalid @enderror">
                            @error('product_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="reset" class="btn btn-light px-4">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-dark px-4">
                                Save Product
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection