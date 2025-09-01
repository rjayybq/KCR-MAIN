<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
       public function index(Request $request)
    {
        $query = Order::with(['product', 'cashier']);

        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('ProductName', 'like', '%' . $request->product . '%');
            });
        }

        $purchases = $query->latest()->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

        public function cashierHistory(Request $request)
    {
        $query = Purchase::with(['user', 'product']);

        // Only show purchases handled by this cashier
        $query->where('cashier_id', auth()->id());

        // Filters (optional, same as admin’s)
        if ($request->filled('customer')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('ProductName', 'like', '%' . $request->product . '%');
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $purchases = $query->orderBy('created_at', 'desc')->paginate(10);

       return view('cashier.purchaseHistory', compact('purchases'));

    }

  
}
