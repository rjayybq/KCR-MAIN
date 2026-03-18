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
            $filter = $request->get('filter');

            if ($request->filled('customer')) {
                $query->where('customer_name', 'like', '%' . $request->customer . '%');
            }

            if ($request->filled('product')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('ProductName', 'like', '%' . $request->product . '%');
                });
            }

            //  Date picker filter
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            //  Button filters
            if ($filter === 'daily') {
                $query->whereDate('created_at', today());
            } elseif ($filter === 'weekly') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'monthly') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($filter === 'yearly') {
                $query->whereYear('created_at', now()->year);
            }

            $purchases = $query->latest()->paginate(10)->appends($request->all());

            return view('purchases.index', compact('purchases', 'filter'));
        }

        public function cashierHistory(Request $request)
            {
                $query = Purchase::with(['user', 'product']);
                $filter = $request->get('filter');

                $query->where('cashier_id', auth()->id());

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

                if ($filter === 'daily') {
                    $query->whereDate('created_at', today());
                } elseif ($filter === 'weekly') {
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                } elseif ($filter === 'monthly') {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                } elseif ($filter === 'yearly') {
                    $query->whereYear('created_at', now()->year);
                }

                if ($request->filled('from') && $request->filled('to')) {
                    $query->whereBetween('created_at', [$request->from, $request->to]);
                }

                $purchases = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

                return view('cashier.purchaseHistory', compact('purchases', 'filter'));
            }

  
}
