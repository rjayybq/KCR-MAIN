<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
class InventoryController extends Controller
{

    public function exportInventoryCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $ingredients = Ingredient::with('movements')->get();

        $filename = 'inventory-records.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ingredients) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Ingredient',
                'Stock In Date(s)',
                'Stock In Quantity',
                'Stock Out Date(s)',
                'Stock Out Quantity',
                'Balance Quantity',
                'Unit',
            ]);

            foreach ($ingredients as $ingredient) {
                $stockInDates = ($ingredient->movements ?? collect())
                    ->where('type', 'in')
                    ->pluck('date')
                    ->implode(' | ');

                $stockInQty = ($ingredient->movements ?? collect())
                    ->where('type', 'in')
                    ->map(function ($in) {
                        return number_format($in->movement_qty, 2);
                    })
                    ->implode(' | ');

                $stockOutDates = ($ingredient->movements ?? collect())
                    ->where('type', 'out')
                    ->pluck('date')
                    ->implode(' | ');

                $stockOutQty = ($ingredient->movements ?? collect())
                    ->where('type', 'out')
                    ->map(function ($out) {
                        return number_format($out->movement_qty, 2);
                    })
                    ->implode(' | ');

                fputcsv($file, [
                    $ingredient->name ?? 'N/A',
                    $stockInDates ?: '-',
                    $stockInQty ?: '0',
                    $stockOutDates ?: '-',
                    $stockOutQty ?: '0',
                    number_format($ingredient->stock ?? 0, 2),
                    $ingredient->unit ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


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
