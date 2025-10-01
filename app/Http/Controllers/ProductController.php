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
        'ProductName'             => 'required|string|max:255',
        'category_id'             => 'required|exists:categories,id',
        'stock'                   => 'required|numeric|min:0',
        'price'                   => 'required|numeric|min:0',
        'expiration_date'         => 'nullable|date',
        'image'                   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',

        // Ingredients
        'ingredients'             => 'array',
        'ingredients.*.name'      => 'nullable|string|max:255',
        'ingredients.*.stock'     => 'nullable|numeric|min:0',
        'ingredients.*.unit'      => 'nullable|string|max:50',
        'ingredients.*.quantity'  => 'nullable|numeric|min:0',
    ]);

    // ✅ Upload image if exists
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // ✅ Create Product
    $product = Product::create([
        'ProductName'     => $request->ProductName,
        'category_id'     => $request->category_id,
        'stock'           => $request->stock,
        'price'           => $request->price,
        'expiration_date' => $request->expiration_date,
        'image'           => $imagePath,
    ]);

    // ✅ Process ingredients
    if ($request->has('ingredients')) {
        foreach ($request->ingredients as $ingredientData) {
            if (!empty($ingredientData['name']) && isset($ingredientData['stock'])) {
                
                // 🔹 Create or update ingredient
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    [
                        'stock' => $ingredientData['stock'],
                        'unit'  => $ingredientData['unit'] ?? 'pcs',
                    ]
                );

                // 🔹 Update stock value of ingredient
                if (isset($ingredientData['stock'])) {
                    $ingredient->update(['stock' => $ingredientData['stock']]);
                }

                // 🔹 Create Stock Transaction
                $stock = Stock::create([
                    'type'     => 'in',
                    'quantity' => $ingredientData['stock'],
                    'date'     => now()->toDateString(),
                ]);

                // 🔹 Attach ingredient to stock via pivot
                $stock->ingredients()->attach($ingredient->id);

                // 🔹 Attach ingredient to product with quantity per product
                $product->ingredients()->attach($ingredient->id, [
                    'quantity' => $ingredientData['quantity'] ?? 1,
                ]);
            }
        }
    }

    return redirect()->route('products.index')
        ->with('success', '✅ Product, ingredients, and stock records created successfully!');
}






    // Show edit form
    public function edit(Product $product)
{
    $categories = Category::all();

    
    $ingredients = $product->ingredients()->get();

    return view('products.edit', compact('product', 'categories', 'ingredients'));
}

    // Update product
   public function update(Request $request, Product $product)
{
    $request->validate([
        'ProductName'         => 'required|string|max:255',
        'category_id'         => 'required|exists:categories,id',
        'stock'               => 'required|integer|min:0',
        'price'               => 'required|numeric|min:0',
        'expiration_date'     => 'nullable|date',
        'image'               => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'ingredients'         => 'array', // for pivot usage qty
        'ingredients.*'       => 'nullable|numeric|min:0',
        'ingredient_stock'    => 'array', // for global inventory stock
        'ingredient_stock.*'  => 'nullable|numeric|min:0',
    ]);

    // ✅ Store old stock before updating
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

    // ✅ Compare old vs new stock (Stock History Logs)
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

    // ✅ Update Global Ingredient Stock (ingredients table)
   if ($request->has('ingredients')) {
        $syncData = [];
        foreach ($request->ingredients as $ingredientId => $qty) {
            if ($qty > 0) {
                $syncData[$ingredientId] = ['quantity' => $qty];
            }
        }
        $product->ingredients()->sync($syncData); // attach or update pivot
    }

    
    // ✅ Product Low Stock Notification
    if ($product->stock <= 5) {
        $existing = Notification::where('product_id', $product->id)
                    ->where('is_read', false)
                    ->first();

        if (!$existing) {
            Notification::create([
                'product_id' => $product->id,
                'title'      => 'Low Stock Alert',
                'message'    => "⚠️ Product '{$product->ProductName}' is running low. Only {$product->stock} left!",
            ]);
        }
    }

    return redirect()->route('products.index')->with('success', '✅ Product & ingredients updated successfully!');
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
