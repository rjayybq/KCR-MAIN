<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierPurchaseController extends Controller
{
    // Show cashier's sales + history
     public function index()
    {
        $cashierId = Auth::id();

        // Get only this cashier’s purchases
        $purchases = Order::with('product')
            ->where('cashier_id', $cashierId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Income calculations
        $todayIncome = Order::where('cashier_id', $cashierId)
            ->whereDate('created_at', today())
            ->sum('total_price');

        $monthIncome = Order::where('cashier_id', $cashierId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $totalSales =Order::where('cashier_id', $cashierId)->sum('total_price');

        return view('cashier.purchaseHistory', compact(
            'purchases', 'todayIncome', 'monthIncome', 'totalSales'
        ));
    }

}
