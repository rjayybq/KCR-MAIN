<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function unread()
    {
        return response()->json([
            'count' => Notification::where('is_read', false)->count(),
            'notifications' => Notification::where('is_read', false)->latest()->take(5)->get()
        ]);
    }

    public function markAsRead($id)
    {
        Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function clearAll()
    {
        Notification::truncate();
        return redirect()->back()->with('success', 'All notifications cleared successfully.');
    }

    public static function createSeasonalBestSellerNotification()
    {
        $month = now()->month;

        if (in_array($month, [3, 4, 5])) {
            $season = 'Summer';
            $startDate = now()->copy()->startOfYear()->setMonth(3)->startOfMonth();
            $endDate = now()->copy()->startOfYear()->setMonth(5)->endOfMonth();
        } elseif (in_array($month, [6, 7, 8, 9])) {
            $season = 'Rainy Season';
            $startDate = now()->copy()->startOfYear()->setMonth(6)->startOfMonth();
            $endDate = now()->copy()->startOfYear()->setMonth(9)->endOfMonth();
        } else {
            $season = 'Holiday Season';
            $startDate = now()->copy()->startOfYear()->setMonth(10)->startOfMonth();
            $endDate = now()->copy()->endOfYear();
        }

        $bestSeller = \App\Models\Order::select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_sold')
            ->first();

        if (!$bestSeller || !$bestSeller->product) {
            return;
        }

        $title = 'Seasonal Best Seller';
        $message = "{$bestSeller->product->ProductName} sold {$bestSeller->total_sold} items this {$season}.";

        $exists = \App\Models\Notification::where('title', $title)
            ->where('message', $message)
            ->exists();

        if (!$exists) {
            \App\Models\Notification::create([
                'title' => $title,
                'message' => $message,
                'is_read' => false,
            ]);
        }
    }
}
