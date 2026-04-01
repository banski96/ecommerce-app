<?php

namespace App\Http\Controllers\Customer;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('customer.index', compact('products'));
    }
}
