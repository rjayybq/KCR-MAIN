<?php

namespace App\Http\Controllers;

use Storage;
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
            'weight'      => 'nullable|numeric|min:0',
            'unit'        => 'nullable|string|in:kg,g,lb',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public'); 
            // stored in storage/app/public/products
        }

        Product::create([
            'ProductName' => $request->ProductName,
            'category_id' => $request->category_id,
            'weight'      => $request->weight,
            'unit'        => $request->unit,
            'stock'       => $request->stock,
            'price'       => $request->price,
            'image'       => $imagePath,
        ]);

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
            'weight'      => 'nullable|numeric|min:0',
            'unit'        => 'nullable|string|in:kg,g,lb',
            'stock'       => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle new image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Update other fields
        $product->update([
            'ProductName' => $request->ProductName,
            'category_id' => $request->category_id,
            'weight'      => $request->weight,
            'unit'        => $request->unit,
            'stock'       => $request->stock,
            'price'       => $request->price,
            'image'       => $product->image, // updated if new uploaded
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }


    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
