@extends('layouts.app1')

@section('content')
<h1 class="text-success fw-bold display-5 mb-4">Cashier Order Dashboard</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Menu Filter Buttons -->
<div class="mb-4 text-center">
    <a href="{{ route('cashier.dashboard') }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == null ? 'active' : '' }}">
        All
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Appetizers / Pulutan']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Appetizers / Pulutan' ? 'active' : '' }}">
        Appetizers / Pulutan
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Main Dishes']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Main Dishes' ? 'active' : '' }}">
        Main Dishes
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Pasta & Pizza']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Pasta & Pizza' ? 'active' : '' }}">
        Pasta & Pizza
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Snacks / Bar Chow']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Snacks / Bar Chow' ? 'active' : '' }}">
        Snacks / Bar Chow
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Alcoholic Beverages']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Alcoholic Beverages' ? 'active' : '' }}">
        Alcoholic Drinks
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Cocktails']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Cocktails' ? 'active' : '' }}">
        Cocktails
    </a>
    <a href="{{ route('cashier.dashboard', ['category' => 'Non-Alcoholic Drinks']) }}" 
       class="btn btn-outline-success btn-sm mx-1 {{ request('category') == 'Non-Alcoholic Drinks' ? 'active' : '' }}">
        Non-Alcoholic
    </a>
</div>

<div class="row">
    <!-- Left: Product list -->
    <div class="col-md-6">
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-6"> 
                    <div class="card shadow-sm h-100 border-0">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x300?text=No+Image' }}" 
                             class="card-img-top" 
                             alt="{{ $product->ProductName }}" 
                             style="height:250px; object-fit:cover;"> 

                        <div class="card-body text-center d-flex flex-column">
                            <h4 class="fw-bold text-success">{{ $product->ProductName }}</h4>
                            <p class="mb-1 text-muted">{{ $product->category->name ?? 'N/A' }}</p>
                            <h5 class="mb-2">₱{{ number_format($product->price, 2) }}</h5>
                            <p class="text-muted">Stock: {{ $product->stock }}</p>

                            <!-- Add to Cart -->
                            <form action="{{ route('cashier.cart.add', $product->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-success w-100 btn-lg">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Cart</h5>
                @if(!empty($cart))
                    <form action="{{ route('cashier.cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger">Clear All</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @if(empty($cart))
                    <p class="text-muted">No items in cart</p>
                @else
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($cart as $item)
                                @php $grandTotal += $item['price'] * $item['quantity']; @endphp
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cashier.cart.remove', $item['product_id']) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">✕</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="2">Grand Total</td>
                                <td colspan="2">₱{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <form action="{{ route('cashier.cart.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 btn-lg">Confirm Order</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
