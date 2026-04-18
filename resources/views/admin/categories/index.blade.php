@extends('admin.layout')

@section('content')
<h1 class="title-name">Categories</h1>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.product.create') }}" class="add-btn btn">
        <i class="bi bi-plus"></i> Add Category
    </a>
</div>

<!-- TABLE WRAPPER -->
<div class="card shadow-sm border-0">
    <div class="card-body">

        <!-- Responsive wrapper -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->category_id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection