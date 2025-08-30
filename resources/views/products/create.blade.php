@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">Create Product</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="ProductName" class="form-label">Product Name</label>
                    <input type="text" name="ProductName" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="weight" class="form-label">Weight</label>
                        <input type="number" step="0.01" name="weight" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <select name="unit" class="form-select">
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="lb">lb</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
