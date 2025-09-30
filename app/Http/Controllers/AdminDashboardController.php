<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
   public function index()
    {
        $totalProducts  = Product::count();        // total products
        $totalInventory = Product::sum('stock');   // total stock
        $totalAccounts  = User::count();           // total users
        $totalPurchases = Order::count();  
        $totalIngredientStock = Ingredient::sum('stock');       // total purchases

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
