<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CartController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        // Categories CRUD
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create'); 
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store'); 
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit'); 
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); 
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Products CRUD
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('product/create', [ProductController::class, 'create'])->name('product.create'); 
        Route::post('product', [ProductController::class, 'store'])->name('product.store');
        Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
});

/*
|--------------------------------------------------------------------------
| User Routes (Customer Side)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/home', function () {
            return view('customer.index');
        })->name('home');
        Route::get('/home', [CustomerProductController::class, 'index'])->name('home');

});
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.view');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';