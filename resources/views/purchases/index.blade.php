@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">All Purchase History</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Cashier</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
          @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->customer_name }}</td>
                    <td>{{ $purchase->product->ProductName ?? 'N/A' }}</td>
                    <td>{{ $purchase->quantity }}</td>
                    <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                    <td>{{ $purchase->cashier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted">No purchase records found.</td>
                    </tr>
            @endforelse

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="p-3">
                        <div class="d-flex justify-content-center">
                            {{ $purchases->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
