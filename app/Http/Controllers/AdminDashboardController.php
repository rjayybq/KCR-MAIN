<?php

namespace App\Http\Controllers;

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
        $totalPurchases = Order::count();          // total purchases

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalInventory',
            'totalAccounts',
            'totalPurchases'
        ));
    }


   public function dashboard()
    {
        $totalProducts = Product::count();

        return view('dashboard', compact('totalProducts'));
    }
}
