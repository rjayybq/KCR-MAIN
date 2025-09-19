<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;

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
        Notification::truncate(); // clears the entire table
        return redirect()->back()->with('success', 'All notifications cleared successfully.');
    }
}
