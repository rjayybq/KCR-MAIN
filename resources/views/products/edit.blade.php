@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">Edit Product</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="ProductName" class="form-control"
                           value="{{ old('ProductName', $product->ProductName) }}" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Weight & Unit --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Weight</label>
                        <input type="number" step="0.01" name="weight" class="form-control"
                               value="{{ old('weight', $product->weight) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit</label>
                        <select name="unit" class="form-select">
                            <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="g" {{ $product->unit == 'g' ? 'selected' : '' }}>g</option>
                            <option value="lb" {{ $product->unit == 'lb' ? 'selected' : '' }}>lb</option>
                        </select>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock) }}" required>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control"
                           value="{{ old('price', $product->price) }}" required>
                </div>

                {{-- Current Image --}}
                <div class="mb-3">
                    <label class="form-label d-block">Current Image</label>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->ProductName }}" 
                             class="img-thumbnail mb-2" style="max-height: 150px;">
                    @else
                        <p class="text-muted">No image uploaded</p>
                    @endif
                </div>

                {{-- Upload New Image --}}
                <div class="mb-3">
                    <label class="form-label">Change Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
