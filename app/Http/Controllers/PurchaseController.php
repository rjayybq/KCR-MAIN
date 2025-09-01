<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'product']);

        // Filter by customer name
        if ($request->filled('customer')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        // Filter by product name
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('ProductName', 'like', '%' . $request->product . '%');
            });
        }

        // Filter by date range
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59',
            ]);
        }

        $purchases = $query->latest()->paginate(10)->appends($request->query());

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
