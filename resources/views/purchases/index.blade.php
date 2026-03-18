@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h1 class="text-success fw-bold display-5 mb-4">All Purchase History</h1>

    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('purchaseHistory') }}"
        class="btn {{ empty($filter) ? 'btn-success' : 'btn-outline-success' }}">
            All
        </a>

        <a href="{{ route('purchaseHistory', ['filter' => 'daily']) }}"
        class="btn {{ $filter === 'daily' ? 'btn-success' : 'btn-outline-success' }}">
            Daily
        </a>

        <a href="{{ route('purchaseHistory', ['filter' => 'weekly']) }}"
        class="btn {{ $filter === 'weekly' ? 'btn-success' : 'btn-outline-success' }}">
            Weekly
        </a>

        <a href="{{ route('purchaseHistory', ['filter' => 'monthly']) }}"
        class="btn {{ $filter === 'monthly' ? 'btn-success' : 'btn-outline-success' }}">
            Monthly
        </a>

        <a href="{{ route('purchaseHistory', ['filter' => 'yearly']) }}"
        class="btn {{ $filter === 'yearly' ? 'btn-success' : 'btn-outline-success' }}">
            Yearly
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-success">
                        <tr>
                            <th style="width: 70px;">No.</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th style="width: 100px;">Qty</th>
                            <th style="width: 140px;">Customer Type</th>
                            <th>Original Price</th>
                            <th>Discount</th>
                            <th>Final Price</th>
                            <th>Cashier</th>
                            <th style="width: 200px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->id }}</td>
                                <td class="fw-semibold">
                                    {{ $purchase->customer_name ?: 'Walk-in Customer' }}
                                </td>
                                <td>{{ $purchase->product->ProductName ?? 'N/A' }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>
                                    <span class="badge 
                                        @if($purchase->customer_type === 'senior') bg-primary
                                        @elseif($purchase->customer_type === 'pwd') bg-warning text-dark
                                        @else bg-secondary
                                        @endif">
                                        {{ strtoupper($purchase->customer_type ?? 'regular') }}
                                    </span>
                                </td>
                                <td>₱{{ number_format($purchase->original_price ?? 0, 2) }}</td>
                                <td>₱{{ number_format($purchase->discount ?? 0, 2) }}</td>
                                <td class="fw-bold text-success">
                                    ₱{{ number_format($purchase->total_price ?? 0, 2) }}
                                </td>
                                <td>{{ $purchase->cashier->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->created_at ? $purchase->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted py-4">No purchase records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($purchases->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $purchases->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection