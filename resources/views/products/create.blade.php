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
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-basket-fill me-2"></i> Raw Meats / Ingredients</h5>
        <button type="button" id="add-row" class="btn btn-light btn-sm rounded-circle" title="Add Ingredient">
            <i class="bi bi-plus-circle text-success fs-5"></i>
        </button>
    </div>

    <div class="card-body" id="ingredient-wrapper">
        <div class="row mb-2 ingredient-row">
            <div class="col-md-4">
                <input type="text" name="ingredients[0][name]" class="form-control"
                       placeholder="Enter ingredient name" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="any" name="ingredients[0][stock]" class="form-control"
                       placeholder="Stock qty" min="0" required>
            </div>
            <div class="col-md-3">
                <select name="ingredients[0][unit]" class="form-select" required>
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="L">L</option>
                </select>
            </div>
            <div class="col-md-2 d-flex">
                <button type="button" class="btn btn-outline-danger remove-row rounded-circle" title="Remove">
                    <i class="bi bi-trash fs-5"></i>
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

    // Add row event
    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row mb-2 ingredient-row';
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="ingredients[${rowIndex}][name]" class="form-control"
                       placeholder="Enter ingredient name" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="any" name="ingredients[${rowIndex}][stock]" class="form-control"
                       placeholder="Stock qty" min="0" required>
            </div>
            <div class="col-md-3">
                <select name="ingredients[${rowIndex}][unit]" class="form-select" required>
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="L">L</option>
                </select>
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

    // Remove row event (delegation)
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
