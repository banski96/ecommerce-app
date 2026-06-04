<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Switched file handling to use Laravel's standard Storage
use App\Http\Requests\StoreProductRequest;

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
            $path = $request->file('product_image')->store('products', 'public');
            $validatedData['product_image'] = 'storage/' . $path;
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
}
