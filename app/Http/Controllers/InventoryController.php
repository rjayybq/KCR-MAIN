<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

//     public function index()
// {
//    $purchases = Purchase::with(['product.ingredients', 'cashier'])->latest()->paginate(10);
//     return view('inventories.index', compact('purchases'));
// }

    public function index()
    {
        // $ingredients = Ingredient::with(['stocks' => function($q) {
        //     $q->orderBy('date', 'desc');
        // }])->get();

        $ingredients = Ingredient::with('movements')->get();

        foreach ($ingredients as $ingredient) {
            if (!$ingredient->movements) {
                $ingredient->setRelation('movements', collect());
            }
        }

        return view('inventories.index', compact('ingredients'));
    }

        public function create()
    {
        $categories = Category::all();
        return view('inventories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ProductName' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventories.index')->with('success', 'Product added successfully!');
    }

        public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $categories = Category::all();
        return view('inventories.edit', compact('inventory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'ProductName' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Product removed successfully!');
    }
}
