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
use Illuminate\Support\Facades\DB;
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

    $bestSellers = Order::select('product_id', DB::raw('SUM(quantity) as total_sold'))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(3)
        ->pluck('product_id')
        ->toArray();

    $products = $query->paginate(12);
    $cart = session()->get('cart', []);

    return view('dashboard.cashier', compact('products', 'cart', 'bestSellers'));
}

    // ✅ Add product to cart
        public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newQty = $currentQty + $request->quantity;

        // Limit based on product stock
        if ($newQty > $product->stock) {
            return back()->with('error', 'Only ' . $product->stock . ' stocks available for ' . $product->ProductName . '.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name'       => $product->ProductName,
            'price'      => $product->price,
            'quantity'   => $newQty,
            'stock'      => $product->stock, // optional, useful sa UI
        ];

        Session::put('cart', $cart);

        return back()->with('success', $product->ProductName . ' added to cart!');
    }

        public function increaseQuantity($id)
    {
        $cart = Session::get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Product not found in cart.');
        }

        $product = Product::find($id);

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        if ($cart[$id]['quantity'] >= $product->stock) {
            return back()->with('error', 'Maximum stock reached for ' . $product->ProductName . '.');
        }

        $cart[$id]['quantity']++;
        Session::put('cart', $cart);

        return back();
    }

    public function decreaseQuantity($id)
    {
        $cart = Session::get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Product not found in cart.');
        }

        $cart[$id]['quantity']--;

        if ($cart[$id]['quantity'] <= 0) {
            unset($cart[$id]);
        }

        Session::put('cart', $cart);

        return back();
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
        'customer_type' => 'required|in:regular,senior,pwd',
    ]);

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Cart is empty!');
    }

    $customerType = $request->customer_type;
    $discountRate = in_array($customerType, ['senior', 'pwd']) ? 0.20 : 0;

    // ✅ Validate all products and ingredients first
    foreach ($cart as $item) {
        $product = Product::with('ingredients')->find($item['product_id']);

        if (!$product) {
            return back()->with('error', "Product '{$item['name']}' not found.");
        }

        if ($item['quantity'] > $product->stock) {
            return back()->with('error', "Only {$product->stock} stock(s) available for {$product->ProductName}.");
        }

        foreach ($product->ingredients as $ingredient) {
            $requiredQty = $ingredient->pivot->quantity * $item['quantity'];
            $unit = $ingredient->unit ?? '';

            if ($ingredient->stock < $requiredQty) {
                return back()->with(
                    'error',
                    "Order unsuccessful. Not enough stock for ingredient '{$ingredient->name}'. Required: {$requiredQty} {$unit}, Available: {$ingredient->stock} {$unit}."
                );
            }
        }
    }

    DB::beginTransaction();

    try {
        foreach ($cart as $item) {
            $product = Product::with('ingredients')->find($item['product_id']);

            $subtotal = $product->price * $item['quantity'];
            $discountAmount = $subtotal * $discountRate;
            $finalTotal = $subtotal - $discountAmount;

            Order::create([
                'product_id'      => $product->id,
                'cashier_id'      => Auth::id(),
                'customer_name'   => $request->customer_name,
                'customer_type'   => $customerType,
                'quantity'        => $item['quantity'],
                'discount'        => $discountAmount,
                'original_price'  => $subtotal,
                'total_price'     => $finalTotal,
            ]);

            // Decrease product stock
            $product->decrement('stock', $item['quantity']);

            // Log product stock out
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'out',
                'quantity'   => $item['quantity'],
                'date'       => now()->toDateString(),
            ]);

            // Deduct ingredient stock
            foreach ($product->ingredients as $ingredient) {
                $requiredQty = $ingredient->pivot->quantity * $item['quantity'];

                $ingredient->decrement('stock', $requiredQty);

                \App\Models\IngredientStock::create([
                    'ingredient_id' => $ingredient->id,
                    'type'          => 'out',
                    'movement_qty'  => $requiredQty,
                    'date'          => now()->toDateString(),
                ]);

                $ingredient->refresh();

                if ($ingredient->stock <= 5) {
                    $existingNotif = Notification::where('ingredient_id', $ingredient->id)
                        ->where('is_read', false)
                        ->first();

                    if (!$existingNotif) {
                        Notification::create([
                            'ingredient_id' => $ingredient->id,
                            'title'         => 'Low Stock Ingredient Alert',
                            'message'       => "⚠️ Ingredient '{$ingredient->name}' is running low. Only {$ingredient->stock} " . ($ingredient->unit ?? '') . " left after cashier order.",
                            'is_read'       => false,
                        ]);
                    }
                }
            }
        }

        DB::commit();

        session()->forget('cart');

        return redirect()->route('cashier.dashboard')
            ->with('success', '✅ Order placed successfully and stocks updated!');
    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Order unsuccessful. ' . $e->getMessage());
    }
}








    // ✅ Remove single item
   public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart.');
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

