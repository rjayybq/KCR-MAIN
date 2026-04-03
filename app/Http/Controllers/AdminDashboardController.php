<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NotificationController;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
   public function index()
    {
        // 🔥 AUTO GENERATE BEST SELLER NOTIFICATION
        NotificationController::createSeasonalBestSellerNotification();

        $totalProducts  = Product::count();
        $totalInventory = Product::sum('stock');
        $totalAccounts  = User::count();
        $totalPurchases = Order::count();
        $totalIngredientStock = Ingredient::sum('stock');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalInventory',
            'totalAccounts',
            'totalPurchases',
            'totalIngredientStock'
        ));
    }


   public function dashboard()
    {
        $totalProducts = Product::count();

        return view('dashboard', compact('totalProducts'));
    }
}
