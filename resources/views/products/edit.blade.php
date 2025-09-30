@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">✏️ Edit Product</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="ProductName" class="form-label fw-semibold">Product Name</label>
                    <input type="text" 
                           name="ProductName" 
                           class="form-control @error('ProductName') is-invalid @enderror"
                           value="{{ old('ProductName', $product->ProductName) }}" required>
                    @error('ProductName') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Category</label>
                    <select name="category_id" 
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label for="stock" class="form-label fw-semibold">Stock</label>
                    <input type="number" 
                           name="stock" 
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', $product->stock) }}" required>
                    @error('stock') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" step="0.01" 
                           name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $product->price) }}" required>
                    @error('price') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Expiration Date --}}
                <div class="mb-3">
                    <label for="expiration_date" class="form-label fw-semibold">Expiration Date</label>
                    <input type="date" 
                           name="expiration_date" 
                           class="form-control @error('expiration_date') is-invalid @enderror"
                           value="{{ old('expiration_date', $product->expiration_date ? \Carbon\Carbon::parse($product->expiration_date)->format('Y-m-d') : '') }}">
                    @error('expiration_date') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

               
                {{-- Ingredients Section --}}
                <div class="mb-4">
                    <h5 class="text-success fw-bold">Ingredients (Raw Meat / Ingredients)</h5>
                    <p class="text-muted small">Update stock (global inventory) and required qty per product.</p>

                   @foreach($ingredients as $ingredient)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>{{ $ingredient->name }} ({{ $ingredient->unit }})</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="0.01"
                                    name="ingredients[{{ $ingredient->id }}]"
                                    value="{{ old('ingredients.' . $ingredient->id, $product->ingredients->find($ingredient->id)->pivot->quantity ?? '') }}"
                                    class="form-control" placeholder="Qty per product">
                            </div>
                        </div>
                    @endforeach
                </div>



                {{-- Product Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" 
                           class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @if($product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->ProductName }}" 
                                 width="120" 
                                 class="img-thumbnail border">
                        </div>
                    @endif
                    @error('image') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
