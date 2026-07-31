<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Switched file handling to use Laravel's standard Storage
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('admin.products.products', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create-product', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {

        $validatedData = $request->validated();

        if ($request->hasFile('product_image')) {
            # Change the file path to match the environment's default disk (local on laptop, s3 on Render)
            $path = $request->file('product_image')->store('products', config('filesystems.default'));
            # Generate public url dynamically
            $validatedData['product_image'] = Storage::url($path);
        }

        $product = Product::create($validatedData);

        return redirect()
        ->route('admin.products')
        ->with('success', 'Product created successfully.');
    }

    public function destroy(string $id)
    {

        $product = Product::findOrFail($id);
        if ($product->product_image) {
            // Converts 'storage/products/file.jpg' back to 'products/file.jpg' for the disk lookup
            $relativeStoragePath = str_replace('storage/', '', $product->product_image);
            Storage::disk('public')->delete($relativeStoragePath);
        }
        $product->delete();

        return redirect()
        ->route('admin.products')
        ->with('success', 'Product deleted successfully.');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        if (! $product) {
            return redirect()->route('admin.products')->with('error', 'Product not found.');
        } else {
            return view('admin.products.edit', compact('product', 'categories'));
        }
    }

    public function update(UpdateProductRequest $request, string $id)
    {

        $product = Product::findOrFail($id);
        $validatedData = $request->validated();

        if ($request->hasFile('product_image')) {
            # Change the file path to match the environment's default disk (local on laptop, s3 on Render)
            $path = $request->file('product_image')->store('products', config('filesystems.default'));
            # Generate public url dynamically
            $validatedData['product_image'] = Storage::url($path);
        }
        $product->update($validatedData);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }
}
