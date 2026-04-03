<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseController extends Controller
{
    public function exportSalesCsv(Request $request): StreamedResponse
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

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Quick filters
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

        $orders = $query->latest()->get();

        $filename = 'sales-history.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No.',
                'Customer',
                'Product',
                'Qty',
                'Customer Type',
                'Original Price',
                'Discount',
                'Final Price',
                'Cashier',
                'Date',
            ]);

            foreach ($orders as $index => $order) {
                fputcsv($file, [
                    $index + 1,
                    $order->customer_name ?? '',
                    $order->product->ProductName ?? '',
                    $order->quantity ?? $order->qty ?? '',
                    strtoupper($order->customer_type ?? ''),
                    $order->original_price ?? 0,
                    $order->discount ?? 0,
                    $order->total_price ?? 0,
                    $order->cashier->name ?? 'Cashier Account',
                    $order->created_at ? $order->created_at->format('M d, Y h:i A') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

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

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Quick filters
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

        $purchases = $query->latest()->paginate(10)->appends($request->all());

        return view('purchases.index', compact('purchases', 'filter'));
    }

        public function cashierHistory(Request $request)
    {
        $query = Purchase::with(['user', 'product']);
        $filter = $request->get('filter');

        $query->where('cashier_id', auth()->id());

        // Customer filter
        if ($request->filled('customer')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        // Product filter
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('ProductName', 'like', '%' . $request->product . '%');
            });
        }

        // ✅ DATE RANGE FILTER (PRIORITY)
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        } else {
            // ✅ QUICK FILTERS (only if NO date range)
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
        }

        $purchases = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('cashier.purchaseHistory', compact('purchases', 'filter'));
}

}
