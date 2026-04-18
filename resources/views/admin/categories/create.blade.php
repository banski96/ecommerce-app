@extends('admin.layout')

@section('content')
<h1>Add Category</h1>

<form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Category Name</label>
        <input type="text" name="category_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
</form>
@endsection