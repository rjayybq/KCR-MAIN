@extends('layouts.app')

@section('content')

<h1 class="fw-bold text-success">Create Product</h1>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Product Info -->
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="ProductName" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Expiration Date</label>
        <input type="date" name="expiration_date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <hr>

    <!-- Ingredients Section -->
    <div class="mb-4 card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-basket-fill me-2"></i> Raw Meats / Ingredients</h5>
        </div>

        <div class="card-body" id="ingredient-wrapper">
            <div class="row mb-2 ingredient-row align-items-center">
                <div class="col-md-3">
                    <input type="text" name="ingredients[0][name]" class="form-control"
                        placeholder="Ingredient name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="any" name="ingredients[0][stock]" class="form-control"
                        placeholder="Stock qty" min="0" required>
                </div>
                <div class="col-md-2">
                    <select name="ingredients[0][unit]" class="form-select" required>
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="g">g</option>
                        <option value="L">L</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="any" name="ingredients[0][quantity]" class="form-control"
                        placeholder="Qty per product" min="0" required>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <!-- Add Ingredient Button -->
                    <button type="button" id="add-row" class="btn btn-success btn-sm" title="Add Ingredient">
                        <i class="bi bi-plus-circle me-1"></i> Add
                    </button>

                    <!-- Remove Button -->
                    <button type="button" class="btn btn-danger btn-sm remove-row" title="Remove">
                        <i class="bi bi-trash me-1"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('ingredient-wrapper');
        const addBtn  = document.getElementById('add-row');
        let rowIndex = wrapper.querySelectorAll('.ingredient-row').length;

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row mb-2 ingredient-row';
            row.innerHTML = `
                <div class="col-md-3">
                    <input type="text" name="ingredients[${rowIndex}][name]" class="form-control"
                        placeholder="Ingredient name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="any" name="ingredients[${rowIndex}][stock]" class="form-control"
                        placeholder="Stock qty" min="0" required>
                </div>
                <div class="col-md-2">
                    <select name="ingredients[${rowIndex}][unit]" class="form-select" required>
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="g">g</option>
                        <option value="L">L</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="any" name="ingredients[${rowIndex}][quantity]" class="form-control"
                        placeholder="Qty per product" min="0" required>
                </div>
                <div class="col-md-2 d-flex">
                    <button type="button" class="btn btn-outline-danger remove-row rounded-circle" title="Remove">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(row);
            rowIndex++;
        });

        wrapper.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-row');
            if (btn) {
                btn.closest('.ingredient-row').remove();
            }
        });
    });
    </script>
    @endpush




    <button type="submit" class="btn btn-success mt-3">Save Product</button>
</form>
@endsection
