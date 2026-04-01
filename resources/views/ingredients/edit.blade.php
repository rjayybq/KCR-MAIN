@extends('layouts.app')

@section('content')
<h1 class="text-success fw-bold display-5 mb-4">Edit Ingredient</h1>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Ingredient Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $ingredient->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Stock</label>
                <input type="number" step="0.01" name="stock" class="form-control"
                       value="{{ old('stock', $ingredient->stock) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Unit</label>
                <select name="unit" class="form-select" required>
                    <option value="pcs" {{ $ingredient->unit === 'pcs' ? 'selected' : '' }}>pcs</option>
                    <option value="kg" {{ $ingredient->unit === 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="g" {{ $ingredient->unit === 'g' ? 'selected' : '' }}>g</option>
                    <option value="L" {{ $ingredient->unit === 'L' ? 'selected' : '' }}>L</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Ingredient</button>
            </div>
        </form>
    </div>
</div>
@endsection
