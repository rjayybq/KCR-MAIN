@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-warning fw-bold display-5 ms-3">Edit Product</h1>

        <a href="{{ route('inventories.index') }}" class="btn btn-secondary me-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow rounded">
        <div class="card-body">
            <form action="{{ route('inventories.update', $inventory->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="ProductName" class="form-control" value="{{ old('ProductName', $inventory->ProductName) }}" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Weight --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Weight</label>
                        <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $inventory->weight) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $inventory->unit) }}" required>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $inventory->stock) }}" required>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $inventory->price) }}" required>
                </div>

                <button type="submit" class="btn btn-warning w-100">
                    <i class="fa-solid fa-save me-1"></i> Update Product
                </button>
            </form>
        </div>
    </div>
@endsection
