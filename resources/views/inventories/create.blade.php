@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5 ms-3">Add New Product</h1>

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
            <form action="{{ route('inventories.store') }}" method="POST">
                @csrf

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="ProductName" class="form-control" value="{{ old('ProductName') }}" required>
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Weight --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Weight</label>
                        <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', 'kg') }}" required>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', 0) }}" required>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-save me-1"></i> Save Product
                </button>
            </form>
        </div>
    </div>
@endsection
