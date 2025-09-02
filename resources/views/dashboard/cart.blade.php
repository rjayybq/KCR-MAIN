@extends('layouts.app1')

@section('content')
<h1 class="text-success fw-bold display-5 mb-4">Cart</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(empty($cart))
    <div class="alert alert-warning">Your cart is empty.</div>
@else
    <table class="table table-bordered text-center">
        <thead class="bg-success text-white">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>₱{{ number_format($item['price'], 2) }}</td>
                    <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Checkout Form -->
    <form action="{{ route('cashier.cart.checkout') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Confirm Order</button>
    </form>
@endif
@endsection
