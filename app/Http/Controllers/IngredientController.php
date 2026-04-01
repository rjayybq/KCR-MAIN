<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->paginate(20);
        return view('ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:ingredients,name',
            'stock' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
        ]);

        Ingredient::create([
            'name'  => $request->name,
            'stock' => $request->stock,
            'unit'  => $request->unit,
        ]);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient created successfully.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'stock' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
        ]);

        $ingredient->update([
            'name'  => $request->name,
            'stock' => $request->stock,
            'unit'  => $request->unit,
        ]);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingredient deleted successfully.');
    }
}
