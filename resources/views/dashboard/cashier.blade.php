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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="width:150px;">Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cartTable">
                            @php $grandTotal = 0; @endphp
                            @foreach($cart as $item)
                                @php $total = $item['price'] * $item['quantity']; $grandTotal += $total; @endphp
                                <tr data-id="{{ $item['product_id'] }}">
                                    <td>{{ $item['name'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="btn btn-sm btn-outline-danger update-cart" data-action="decrease">-</button>
                                            <span class="px-3 quantity">{{ $item['quantity'] }}</span>
                                            <button class="btn btn-sm btn-outline-success update-cart" data-action="increase">+</button>
                                        </div>
                                    </td>
                                    <td class="total">₱{{ number_format($total, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger remove-cart">✕</button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="fw-bold">Grand Total</td>
                                <td colspan="2" class="fw-bold" id="grandTotal">₱{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </tbody>
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

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Update quantity
    document.querySelectorAll(".update-cart").forEach(btn => {
        btn.addEventListener("click", function () {
            let tr = this.closest("tr");
            let id = tr.dataset.id;
            let action = this.dataset.action;

            fetch("{{ route('cashier.update.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id: id, action: action })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) return alert(data.error);

                if (data.quantity > 0) {
                    tr.querySelector(".quantity").textContent = data.quantity;
                    tr.querySelector(".total").textContent = "₱" + data.total;
                } else {
                    tr.remove();
                }
                document.getElementById("grandTotal").textContent = "₱" + data.grandTotal;
            });
        });
    });

    // Remove item
    document.querySelectorAll(".remove-cart").forEach(btn => {
        btn.addEventListener("click", function () {
            let tr = this.closest("tr");
            let id = tr.dataset.id;

            fetch("{{ route('cashier.remove.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                tr.remove();
                document.getElementById("grandTotal").textContent = "₱" + data.grandTotal;
            });
        });
    });
});
</script>