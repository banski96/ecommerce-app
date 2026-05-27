<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('customer.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Calls our Postgres fuzzy search scope
        $products = Product::fuzzySearch($query)->paginate(12);

        return view('customer.search-results', compact('products', 'query'));
    }
}
