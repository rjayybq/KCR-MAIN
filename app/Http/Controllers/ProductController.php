<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
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
    // public function create()
    // {
    //     $categories = Category::all();
    //     return view('products.create', compact('categories'));
    // }

    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::all(); // ✅ get raw meat & ingredients
        return view('products.create', compact('categories', 'ingredients'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        $ingredients = $product->ingredients()->get();

        return view('products.edit', compact('product', 'categories', 'ingredients'));
    }

    // Store new product
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'ProductName'      => 'required|string|max:255',
    //         'category_id'      => 'required|exists:categories,id',
    //         'stock'            => 'required|integer|min:0',
    //         'price'            => 'required|numeric|min:0',
    //         'expiration_date'  => 'nullable|date',
    //         'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //     ]);

    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('products', 'public');
    //     }

    //     $product = Product::create([
    //         'ProductName'    => $request->ProductName,
    //         'category_id'    => $request->category_id,
    //         'stock'          => $request->stock,
    //         'price'          => $request->price,
    //         'expiration_date'=> $request->expiration_date,
    //         'image'          => $imagePath,
    //     ]);

    //     // Record stock in
    //     Stock::create([
    //         'product_id' => $product->id,
    //         'type'       => 'in',
    //         'quantity'   => $request->stock,
    //         'date'       => now()->toDateString(),
    //     ]);

    //     return redirect()->route('products.index')->with('success', 'Product created successfully!');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'ProductName'     => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'stock'           => 'required|numeric|min:0',
            'price'           => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'ingredients'     => 'array',
            'ingredients.*.name'  => 'nullable|string|max:255',
            'ingredients.*.stock' => 'nullable|numeric|min:0',
            'ingredients.*.unit'  => 'nullable|string|max:50',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Upload image
        $imagePath = $request->hasFile('image') 
            ? $request->file('image')->store('products', 'public') 
            : null;

        // Create Product
        $product = Product::create([
            'ProductName'     => $request->ProductName,
            'category_id'     => $request->category_id,
            'stock'           => $request->stock,
            'price'           => $request->price,
            'expiration_date' => $request->expiration_date,
            'image'           => $imagePath,
        ]);

        if ($request->has('ingredients')) {
        foreach ($request->ingredients as $ingredientData) {
            if (!empty($ingredientData['name'])) {
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    ['stock' => $ingredientData['stock'], 'unit' => $ingredientData['unit']]
                );

                // attach with qty per product (recipe)
                $product->ingredients()->attach($ingredient->id, [
                    'quantity' => $ingredientData['quantity'] ?? 1
                ]);
            }
        }
    }


        return redirect()->route('products.index')
            ->with('success', '✅ Product with ingredients created successfully!');
    }

    

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'ProductName'     => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'stock'           => 'required|numeric|min:0',
            'price'           => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',

            // Pivot data (qty per product)
            'ingredients'     => 'array',
            'ingredients.*'   => 'nullable|numeric|min:0',  

            // Global stock update
            'ingredient_stock'    => 'array',
            'ingredient_stock.*'  => 'nullable|numeric|min:0',
        ]);

        $oldStock = $product->stock;

        // ✅ Handle product image update
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // ✅ Update product fields
        $product->update([
            'ProductName'     => $request->ProductName,
            'category_id'     => $request->category_id,
            'stock'           => $request->stock,
            'price'           => $request->price,
            'expiration_date' => $request->expiration_date,
            'image'           => $product->image,
        ]);

        // ✅ Log product stock in/out
        if ($request->stock > $oldStock) {
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $request->stock - $oldStock,
                'date'       => now()->toDateString(),
            ]);
        } elseif ($request->stock < $oldStock) {
            Stock::create([
                'product_id' => $product->id,
                'type'       => 'out',
                'quantity'   => $oldStock - $request->stock,
                'date'       => now()->toDateString(),
            ]);
        }

        // ✅ Update pivot (required qty per product)
        if ($request->has('ingredients')) {
            $syncData = [];
            foreach ($request->ingredients as $ingredientId => $qty) {
                if ($qty > 0) {
                    $syncData[$ingredientId] = ['quantity' => $qty];
                }
            }
            $product->ingredients()->sync($syncData);
        }

        // ✅ Update global ingredient stock (inventory)
        if ($request->has('ingredient_stock')) {
            foreach ($request->ingredient_stock as $ingredientId => $newStock) {
                $ingredient = Ingredient::find($ingredientId);
                if ($ingredient) {
                    $oldIngredientStock = $ingredient->stock;

                    $ingredient->update(['stock' => $newStock]);

                    // Record ingredient stock movement
                    if ($newStock > $oldIngredientStock) {
                        \App\Models\IngredientStock::create([
                            'ingredient_id' => $ingredient->id,
                            'type'          => 'in',
                            'movement_qty'  => $newStock - $oldIngredientStock,
                            'date'          => now()->toDateString(),
                        ]);
                    } elseif ($newStock < $oldIngredientStock) {
                        \App\Models\IngredientStock::create([
                            'ingredient_id' => $ingredient->id,
                            'type'          => 'out',
                            'movement_qty'  => $oldIngredientStock - $newStock,
                            'date'          => now()->toDateString(),
                        ]);
                    }

                    // Low stock alert
                    if ($ingredient->stock <= 5) {
                        Notification::firstOrCreate(
                            [
                                'ingredient_id' => $ingredient->id,
                                'is_read'       => false,
                            ],
                            [
                                'title'   => 'Low Stock Ingredient Alert',
                                'message' => "⚠️ Ingredient '{$ingredient->name}' is running low. Only {$ingredient->stock} left in stock!",
                            ]
                        );
                    }
                }
            }
        }

        return redirect()->route('products.index')
            ->with('success', '✅ Product & ingredients updated successfully!');
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
