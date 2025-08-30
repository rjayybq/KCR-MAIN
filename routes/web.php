<?php

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

// Route::middleware(['auth', 'role:user'])
//     ->get('/user/dashboard', [UserDashboardController::class, 'index'])
//     ->name('user.dashboard');


// Route::middleware(['auth', 'role:user'])->group(function () {
//     Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
// });



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

Route::get('/accountList', [UserController::class, 'index'])->name('accountList');

Route::get('/purchaseHistory', [PurchaseHistoryController::class, 'index'])->name('purchaseHistory');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');