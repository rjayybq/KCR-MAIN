<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'ProductName' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'weight'      => 'nullable|numeric',
            'unit'        => 'required|string',
            'stock'       => 'required|integer',
            'price'       => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Show edit form
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }



    public function order(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;

        if ($product->stock >= $quantity) {
            // Reduce stock
            $product->decrement('stock', $quantity);

            // (Optional) Save to orders table if you’re tracking orders
            // Order::create([
            //     'product_id'  => $product->id,
            //     'quantity'    => $quantity,
            //     'total_price' => $product->price * $quantity,
            // ]);

            return redirect()->route('products.index')
                ->with('success', $quantity . ' ' . $product->ProductName . '(s) ordered successfully!');
        }

        return redirect()->route('products.index')
            ->with('error', 'Not enough stock for ' . $product->ProductName);
    }



    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'ProductName' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'weight'      => 'nullable|numeric',
            'unit'        => 'required|string',
            'stock'       => 'required|integer',
            'price'       => 'required|numeric',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
