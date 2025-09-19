<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with('category')->paginate(30);
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
            'ProductName'      => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'stock'            => 'required|integer|min:0',
            'price'            => 'required|numeric|min:0',
            'expiration_date'  => 'nullable|date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'ProductName'    => $request->ProductName,
            'category_id'    => $request->category_id,
            'stock'          => $request->stock,
            'price'          => $request->price,
            'expiration_date'=> $request->expiration_date,
            'image'          => $imagePath,
        ]);

        // Record stock in
        Stock::create([
            'product_id' => $product->id,
            'type'       => 'in',
            'quantity'   => $request->stock,
            'date'       => now()->toDateString(),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Show edit form
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'ProductName'     => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'stock'           => 'required|integer|min:0',
            'price'           => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // ✅ Store the old stock before updating
        $oldStock = $product->stock;

        // ✅ Handle image update
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // ✅ Update product
        $product->update([
            'ProductName'     => $request->ProductName,
            'category_id'     => $request->category_id,
            'stock'           => $request->stock,
            'price'           => $request->price,
            'expiration_date' => $request->expiration_date,
            'image'           => $product->image,
        ]);

        // ✅ Compare old vs new stock
        if ($request->stock > $oldStock) {
            // Stock In
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $request->stock - $oldStock,
                'date'       => now()->toDateString(),
            ]);
        } elseif ($request->stock < $oldStock) {
            // Stock Out
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'out',
                'quantity'   => $oldStock - $request->stock,
                'date'       => now()->toDateString(),
            ]);
        }

        // ✅ NEW: Create a notification if stock is low (≤ 5)
      if ($product->stock <= 5) {
            $existing = Notification::where('product_id', $product->id)
                        ->where('is_read', false)
                        ->first();

            if (!$existing) {
                Notification::create([
                    'product_id' => $product->id,
                    'title'      => 'Low Stock Alert',
                    'message'    => "Product '{$product->ProductName}' is running low. Only {$product->stock} left!",
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }



    // Delete product
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
