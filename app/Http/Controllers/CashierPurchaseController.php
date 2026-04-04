<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // Today’s Income
        $todayIncome = Order::where('cashier_id', $cashierId)
            ->whereDate('created_at', today())
            ->sum('total_price');

        // Monthly Income
        $monthIncomeQuery = Order::where('cashier_id', $cashierId);
        if ($filterDate) {
            $monthIncomeQuery->whereMonth('created_at', $filterDate->month)
                             ->whereYear('created_at', $filterDate->year);
        } else {
            $monthIncomeQuery->whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year);
        }
        $monthIncome = $monthIncomeQuery->sum('total_price');

        // Total Sales
        $totalSalesQuery = Order::where('cashier_id', $cashierId);
        if ($filterDate) {
            $totalSalesQuery->whereDate('created_at', $filterDate->toDateString());
        }
        $totalSales = $totalSalesQuery->sum('total_price');

        return view('cashier.purchaseHistory', compact(
            'purchases', 'todayIncome', 'monthIncome', 'totalSales', 'filterDate'
        ));
    }

    public function exportCsv(Request $request)
    {
        $cashierId = Auth::id();

        $query = Order::with('product')
            ->where('cashier_id', $cashierId);

        if ($request->filled('sales_date')) {
            try {
                $filterDate = Carbon::parse($request->sales_date);
                $query->whereDate('created_at', $filterDate->toDateString());
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid date format.');
            }
        }

        $purchases = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'cashier_sales_history_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return new StreamedResponse(function () use ($purchases) {
            $handle = fopen('php://output', 'w');

            // Optional for Excel UTF-8 support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No.',
                'Customer',
                'Product',
                'Quantity',
                'Total Price',
                'Date',
            ]);

            foreach ($purchases as $index => $purchase) {
                fputcsv($handle, [
                    $index + 1,
                    $purchase->customer_name ?? $purchase->customer ?? 'N/A',
                    $purchase->product->product_name ?? $purchase->product->name ?? 'N/A',
                    $purchase->quantity ?? $purchase->qty ?? 0,
                    $purchase->total_price ?? 0,
                    optional($purchase->created_at)->format('M d, Y h:i A'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }


}
