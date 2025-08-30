@extends('layouts.app')

@section('content')
    <h1 class="text-primary fw-bold display-5 mb-4">Orders</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-striped table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->product->ProductName }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
