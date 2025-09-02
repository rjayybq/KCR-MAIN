<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CashierDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category') && $request->category != null) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $products = $query->paginate(12);
        $cart = session()->get('cart', []);

        return view('dashboard.cashier', compact('products', 'cart'));
    }

    // ✅ Add product to cart
    public function addToCart(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name'       => $product->ProductName,
            'price'      => $product->price,
            'quantity'   => isset($cart[$product->id]) 
                            ? $cart[$product->id]['quantity'] + $request->quantity 
                            : $request->quantity,
        ];

        Session::put('cart', $cart);

        return back()->with('success', $product->ProductName . ' added to cart!');
    }

    // ✅ View cart
    public function viewCart()
    {
        $cart = Session::get('cart', []);
        return view('dashboard.cart', compact('cart'));
    }

    // ✅ Checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty!');
        }

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->stock >= $item['quantity']) {
                // Save order
                Order::create([
                    'product_id'    => $product->id,
                    'cashier_id'    => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'quantity'      => $item['quantity'],
                    'total_price'   => $product->price * $item['quantity'],
                ]);

                // Decrease stock
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Clear cart after checkout
        Session::forget('cart');

        return redirect()->route('cashier.dashboard')->with('success', 'Order placed successfully!');
    }

    // Remove single item
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Item removed from cart.');
    }

    // Clear all items
    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }
}
