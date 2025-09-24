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
                @php $lastCustomer = null; @endphp
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>

                        {{-- Only show customer name if it's different from last row --}}
                        @if($lastCustomer !== $purchase->customer_name)
                            <td class="fw-bold">{{ $purchase->customer_name }}</td>
                            @php $lastCustomer = $purchase->customer_name; @endphp
                        @else
                            <td></td>
                        @endif

                        <td>{{ $purchase->product->ProductName ?? 'N/A' }}</td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                        <td>{{ $purchase->cashier->name ?? 'N/A' }}</td>
                        <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No purchase records found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="p-3">
                        <div class="d-flex justify-content-center">
                            {{ $purchases->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
