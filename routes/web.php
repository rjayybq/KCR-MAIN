<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CashierProfileController;
use App\Http\Controllers\CashierPurchaseController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\CashierDashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();





Route::get('admin/dashboard/stats', function () {
    return response()->json([
        'totalProducts'  => Product::count(),
        'totalInventory' => Product::sum('stock'),
        'totalAccounts'  => User::count(),
        'totalPurchases' => Order::count(),
    ]);
})->name('dashboard.stats');



// -------------------- ADMIN ROUTES --------------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/order', [ProductController::class, 'order'])
        ->name('products.order');
    
    //Account Management
    Route::resource('users', UserController::class); 

    // Inventories
    Route::resource('inventories', InventoryController::class);

    // Account list (users)
    Route::get('/account-list', [UserController::class, 'index'])->name('accountList');

    // Purchase history (all purchases)
    Route::get('/purchase-history', [PurchaseController::class, 'index'])->name('purchaseHistory');

    // Profile
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');


    
});




// -------------------- CASHIER ROUTES --------------------
Route::middleware(['auth', 'role:cashier'])->group(function () {

    // Cashier Dashboard
    Route::get('/cashier/dashboard', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');
    Route::post('/cashier/order/{product}', [CashierDashboardController::class, 'storeOrder'])->name('cashier.order');   
     
    // Cashier purchase history (own handled purchases)
    // Route::get('/cashier/purchase-history', [CashierPurchaseController::class, 'index'])
    //     ->name('cashier.purchaseHistory');
    Route::post('/cashier/purchase', [CashierPurchaseController::class, 'store'])->name('cashier.purchase.store');
//    Route::get('/cashier/purchase-history', [CashierPurchaseController::class, 'index'])
//     ->name('cashier.purchaseHistory');
    Route::get('/cashier/purchase-history', [CashierPurchaseController::class, 'index'])
        ->name('cashier.purchase.history');


    // Cart
    Route::post('/cashier/cart/add/{product}', [CashierDashboardController::class, 'addToCart'])->name('cashier.cart.add');
    Route::get('/cashier/cart', [CashierDashboardController::class, 'viewCart'])->name('cashier.cart.view');
    Route::post('/cashier/cart/checkout', [CashierDashboardController::class, 'checkout'])->name('cashier.cart.checkout');
    Route::delete('/cashier/cart/remove/{id}', [CashierDashboardController::class, 'removeFromCart'])->name('cashier.cart.remove');
    Route::delete('/cashier/cart/clear', [CashierDashboardController::class, 'clearCart'])->name('cashier.cart.clear');
    Route::post('/cart/update', [CashierDashboardController::class, 'updateCartAjax'])->name('cashier.update.ajax');
    Route::post('/cart/remove', [CashierDashboardController::class, 'removeCartAjax'])->name('cashier.remove.ajax');


   
});


Route::middleware(['auth'])->group(function () {
   Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/profile', [CashierProfileController::class, 'profile'])->name('cashier.profile');
    Route::put('/profile', [CashierProfileController::class, 'updateProfile'])->name('cashier.profile.update');
});