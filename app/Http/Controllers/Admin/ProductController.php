<?php

namespace App\Http\Controllers\Admin;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $path = null;
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'description'    => 'required|string|max:1000', // allow longer description
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id'    => 'required|exists:categories,category_id',
            'product_image'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // max 2MB
        ]);
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image'); // get uploaded file
            $filename = time() . '_' . $file->getClientOriginalName(); // create unique name
            $file->move(public_path('uploads/products'), $filename); // save to public/uploads/products   
            $path = 'uploads/products/' . $filename; // store path string in DB
            #TODO: directory of uploads/products
        }
        $product = Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'product_image' => $path,
        ]);
        return redirect()->route('admin.products')->with('success', 'Product successfully.');
    }

    public function destroy(string $id)
    {
        # TODO: add the deletion of image in the public folder
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }
}
