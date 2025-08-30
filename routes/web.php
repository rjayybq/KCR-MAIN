<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\CashierDashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'role:cashier'])
    ->get('/cashier/dashboard', [CashierDashboardController::class, 'index'])
    ->name('cashier.dashboard');


Route::get('admin/dashboard/stats', function () {
    return response()->json([
        'totalProducts'  => Product::count(),
        'totalInventory' => Product::sum('stock'),
        'totalAccounts'  => User::count(),
        'totalPurchases' => Order::count(),
    ]);
})->name('dashboard.stats');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Products Index (list all)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::post('/products/{id}/order', [ProductController::class, 'order'])->name('products.order');

// Create Product (form)
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Store Product (save new)
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Edit Product (form)
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Update Product (save changes)
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// Delete Product
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

Route::get('/accountList', [UserController::class, 'index'])->name('accountList');

Route::get('/purchaseHistory', [PurchaseHistoryController::class, 'index'])->name('purchaseHistory');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');