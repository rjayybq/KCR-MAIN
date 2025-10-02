<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CashierDashboardController extends Controller
{
    // ✅ Show cashier dashboard with products + cart
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

    // ✅ View cart (separate view if needed)
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

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty!');
        }

        foreach ($cart as $item) {
            $product = Product::with('ingredients')->find($item['product_id']); // load product + ingredients

            if ($product && $product->stock >= $item['quantity']) {

                // ✅ Save Order
                $order = Order::create([
                    'product_id'    => $product->id,
                    'cashier_id'    => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'quantity'      => $item['quantity'],
                    'total_price'   => $product->price * $item['quantity'],
                ]);

                // ✅ Decrease product stock
                $product->decrement('stock', $item['quantity']);

                // ✅ Record PRODUCT stock OUT (optional, kung may hiwalay kang product stock history)
                Stock::create([
                    'product_id' => $product->id,
                    'type'       => 'out',
                    'quantity'   => $item['quantity'],
                    'date'       => now()->toDateString(),
                ]);

                // ✅ Deduct INGREDIENTS
                foreach ($product->ingredients as $ingredient) {
                    $requiredQty = $ingredient->pivot->quantity * $item['quantity']; 
                    // recipe_qty × order_qty


                    // Update ingredient stock
                    $ingredient->decrement('stock', $requiredQty);

                    // ✅ Log ingredient stock OUT sa ingredient_stock table
                    \App\Models\IngredientStock::create([
                        'ingredient_id' => $ingredient->id,
                        'type'          => 'out',
                        'movement_qty'  => $requiredQty,
                        'date'          => now()->toDateString(),
                    ]);

                    // ✅ Low stock notification
                    if ($ingredient->stock <= 5) {
                        $existingNotif = Notification::where('ingredient_id', $ingredient->id)
                            ->where('is_read', false)
                            ->first();

                        if (!$existingNotif) {
                            Notification::create([
                                'ingredient_id' => $ingredient->id,
                                'title'         => 'Low Stock Ingredient Alert',
                                'message'       => "⚠️ Ingredient '{$ingredient->name}' is running low. Only {$ingredient->stock} left after cashier order.",
                                'is_read'       => false,
                            ]);
                        }
                    }
                }
            }
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('cashier.dashboard')->with('success', '✅ Order placed successfully and stocks updated!');
    }








    // ✅ Remove single item
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Item removed from cart.');
    }

    // ✅ Clear all items
    public function clearCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

   public function purchaseHistory(Request $request)
{
    $cashierId = Auth::id(); // logged-in cashier
    
    $purchases = Order::with('product')
        ->where('cashier_id', $cashierId);

    // ✅ Apply date filter if provided
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $purchases->whereBetween('created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    $purchases = $purchases->orderBy('created_at', 'desc')->paginate(10);

    // ✅ Income Calculations (respecting date filters if applied)
    $todayIncome = Order::where('cashier_id', $cashierId)
        ->when($request->filled('from_date') && $request->filled('to_date'), function($q) use ($request) {
            $q->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        })
        ->whereDate('created_at', today())
        ->sum('total_price');

    $monthIncome = Order::where('cashier_id', $cashierId)
        ->when($request->filled('from_date') && $request->filled('to_date'), function($q) use ($request) {
            $q->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price');

    $totalSales = Order::where('cashier_id', $cashierId)
        ->when($request->filled('from_date') && $request->filled('to_date'), function($q) use ($request) {
            $q->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        })
        ->sum('total_price');

    return view('cashier.purchaseHistory', compact(
        'purchases', 'todayIncome', 'monthIncome', 'totalSales'
    ));
}

    public function updateCartAjax(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $action = $request->action;

        if (!isset($cart[$id])) {
            return response()->json(['error' => 'Product not found in cart.'], 404);
        }

        if ($action === 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($action === 'decrease') {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        // Recalculate totals
        $grandTotal = 0;
        $total = isset($cart[$id]) ? $cart[$id]['price'] * $cart[$id]['quantity'] : 0;
        foreach ($cart as $c) {
            $grandTotal += $c['price'] * $c['quantity'];
        }

        return response()->json([
            'quantity' => $cart[$id]['quantity'] ?? 0,
            'total' => number_format($total, 2),
            'grandTotal' => number_format($grandTotal, 2),
        ]);
    }

    public function removeCartAjax(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        $grandTotal = 0;
        foreach ($cart as $c) {
            $grandTotal += $c['price'] * $c['quantity'];
        }

        return response()->json([
            'grandTotal' => number_format($grandTotal, 2),
        ]);
    }

}

