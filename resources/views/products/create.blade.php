@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">Create Product</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="ProductName" class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="ProductName" class="form-control @error('ProductName') is-invalid @enderror"
                           value="{{ old('ProductName') }}" required>
                    @error('ProductName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label for="stock" class="form-label fw-semibold">Stock</label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock') }}" required>
                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" step="0.01" name="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Expiration Date --}}
                <div class="mb-3">
                    <label for="expiration_date" class="form-label fw-semibold">Expiration Date</label>
                    <input type="date" name="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror"
                           value="{{ old('expiration_date') }}">
                    @error('expiration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Product Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
