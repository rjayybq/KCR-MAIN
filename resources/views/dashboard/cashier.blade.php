@extends('layouts.app1')

@section('content')
<h1 class="text-success fw-bold display-5 mb-4">Cashier Order Dashboard</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    @foreach($products as $product)
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top" 
                     alt="{{ $product->ProductName }}" 
                     style="height:150px; object-fit:cover;">

                <div class="card-body text-center">
                    <h5 class="fw-bold">{{ $product->ProductName }}</h5>
                    <p class="mb-1">₱{{ number_format($product->price, 2) }}</p>
                    <p class="text-muted">Stock: {{ $product->stock }}</p>

                    <!-- Order Button (opens modal) -->
                    <button class="btn btn-success btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#orderModal{{ $product->id }}">
                        Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Modal -->
        <div class="modal fade" id="orderModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('cashier.order', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Order: {{ $product->ProductName }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Customer Email</label>
                                <input type="email" name="customer_email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" 
                                       min="1" max="{{ $product->stock }}" value="1" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Confirm Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection
