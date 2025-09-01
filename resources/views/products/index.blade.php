@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5">Products</h1>

        <!-- Create Product Button -->
        <a href="{{ route('products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Create Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card shadow-sm h-100 border-0">
                    <!-- Product Image -->
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                         class="card-img-top" alt="{{ $product->ProductName }}" style="height:200px; object-fit:cover;">

                    <div class="card-body d-flex flex-column">
                        <!-- Product Info -->
                        <h5 class="card-title fw-bold text-success">{{ $product->ProductName }}</h5>
                        <p class="mb-1"><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Weight:</strong> {{ $product->weight . " " . $product->unit }}</p>
                        <p class="mb-1"><strong>Stock:</strong> {{ $product->stock }}</p>
                        <p class="fw-bold text-dark">₱{{ number_format($product->price, 2) }}</p>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <!-- Order Form -->
                            <form action="{{ route('products.order', $product->id) }}" method="POST" class="d-flex gap-2 mb-2">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                       class="form-control form-control-sm" style="width:70px;">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-cart-plus"></i> Order
                                </button>
                            </form>

                            <!-- Edit & Delete -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm w-50">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');" class="w-50">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">No products available.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {!! $products->links('vendor.pagination.bootstrap-4') !!}
    </div>
@endsection
