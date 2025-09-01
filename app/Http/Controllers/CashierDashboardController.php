<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierDashboardController extends Controller
{
   public function index()
    {
        $products = Product::paginate(8); // show product cards
        return view('dashboard.cashier', compact('products'));
    }

    public function storeOrder(Request $request, Product $product)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'quantity'       => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Save order
        Order::create([
            'product_id'     => $product->id,
            'cashier_id'     => Auth::id(),
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'quantity'       => $request->quantity,
            'total_price'    => $product->price * $request->quantity,
        ]);

        // Reduce stock
        $product->decrement('stock', $request->quantity);

        return back()->with('success', 'Order placed successfully!');
    }
}
