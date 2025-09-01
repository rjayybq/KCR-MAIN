@extends('layouts.app1')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">Cashier Purchase History</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="bg-success text-white">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->product->ProductName }}</td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                        <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No purchases found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="p-3">
                        <div class="d-flex justify-content-center">
                            {{ $purchases->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
