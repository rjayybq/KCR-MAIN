<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashierPurchaseController extends Controller
{
    // Show cashier's sales + history with optional date filter
   public function index(Request $request)
{
    $cashierId = Auth::id();

    // Base purchases query
    $purchasesQuery = Order::with('product')
        ->where('cashier_id', $cashierId);

    $filterDate = null;

    if ($request->filled('sales_date')) {
        try {
            $filterDate = Carbon::parse($request->sales_date);
            $purchasesQuery->whereDate('created_at', $filterDate->toDateString());
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid date format.');
        }
    }

    $purchases = $purchasesQuery->orderBy('created_at', 'desc')->paginate(10);

    // ✅ Today’s Income (always today, not affected by filter)
    $todayIncome = Order::where('cashier_id', $cashierId)
        ->whereDate('created_at', today())
        ->sum('total_price');

    // ✅ Monthly Income (changes based on filter month if date is selected)
    $monthIncomeQuery = Order::where('cashier_id', $cashierId);
    if ($filterDate) {
        $monthIncomeQuery->whereMonth('created_at', $filterDate->month)
                         ->whereYear('created_at', $filterDate->year);
    } else {
        $monthIncomeQuery->whereMonth('created_at', now()->month)
                         ->whereYear('created_at', now()->year);
    }
    $monthIncome = $monthIncomeQuery->sum('total_price');

    // ✅ Total Sales (affected by filter if selected)
    $totalSalesQuery = Order::where('cashier_id', $cashierId);
    if ($filterDate) {
        $totalSalesQuery->whereDate('created_at', $filterDate->toDateString());
    }
    $totalSales = $totalSalesQuery->sum('total_price');

    return view('cashier.purchaseHistory', compact(
        'purchases', 'todayIncome', 'monthIncome', 'totalSales', 'filterDate'
    ));
}



}
