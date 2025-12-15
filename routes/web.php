<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// FRONTEND CONTROLLERS
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------------------
// PUBLIC SHOP ROUTES
// ----------------------------

// Home page
Route::get('/', [ShopController::class, 'index'])->name('shop.home');

// Product detail
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.product');

// Category page
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');


// ----------------------------
// CART ROUTES
// ----------------------------

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');


// ----------------------------
// CHECKOUT ROUTES (login required)
// ----------------------------

Route::get('/checkout', [OrderController::class, 'checkout'])
    ->middleware('auth')
    ->name('checkout');

Route::post('/checkout', [OrderController::class, 'place'])
    ->middleware('auth')
    ->name('checkout.place');

// User order detail
Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->middleware('auth')
    ->name('user.orders.show');

// User order history
Route::get('/my-orders', [OrderController::class, 'index'])
    ->middleware('auth')
    ->name('user.orders.index');


// ----------------------------
// USER DASHBOARD (default Breeze)
// ----------------------------

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ----------------------------
// USER PROFILE (default Breeze)
// ----------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ----------------------------
// ADMIN ROUTES (must login)
// ----------------------------

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', function () {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_categories' => \App\Models\Category::count(),
            'total_revenue' => \App\Models\Order::where('status', 'completed')->sum('total_price'),
        ];
        
        $recent_orders = \App\Models\Order::with('items')->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recent_orders'));
    })->name('admin.dashboard');

    // Category CRUD
    Route::resource('categories', CategoryController::class);

    // Product CRUD
    Route::resource('products', ProductController::class);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
});


// ----------------------------
// AUTH ROUTES (Breeze)
// ----------------------------

require __DIR__.'/auth.php';
