<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
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
            'ProductName'      => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'stock'            => 'required|integer|min:0',
            'price'            => 'required|numeric|min:0',
            'expiration_date'  => 'nullable|date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'ProductName'     => $request->ProductName,
            'category_id'     => $request->category_id,
            'stock'           => $request->stock,
            'price'           => $request->price,
            'expiration_date' => $request->expiration_date,
            'image'           => $product->image,
        ]);

        if ($request->stock > $oldStock) {
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $request->stock - $oldStock,
                'date'       => now()->toDateString(),
            ]);
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
