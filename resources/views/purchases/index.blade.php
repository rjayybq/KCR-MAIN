@extends('layouts.app')

@section('content')
<style>
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    .btn,
    form,
    nav,
    .card:first-of-type,
    .pagination,
    .card-footer,
    .no-print {
        display: none !important;
    }

    body {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 10px !important;
    }

    .container-fluid,
    .container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .card-body {
        padding: 0 !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important;
        font-size: 10px !important;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 4px !important;
        font-size: 10px !important;
        word-break: break-word !important;
        white-space: normal !important;
    }

    h1 {
        font-size: 24px !important;
        margin-bottom: 15px !important;
    }

    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: none !important;
        font-size: 9px !important;
        padding: 2px 4px !important;
    }
}
</style>

<div class="container-fluid py-3">
    <h1 class="text-success fw-bold display-5 mb-4">All Sales History</h1>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('purchaseHistory') }}">
                <div class="row align-items-end g-3">

                    <div class="col-md-5">
                        <label class="form-label fw-bold text-success">Start Date</label>
                        <input
                            type="date"
                            name="from"
                            class="form-control form-control-lg"
                            value="{{ request('from') }}"
                        >
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold text-success">End Date</label>
                        <input
                            type="date"
                            name="to"
                            class="form-control form-control-lg"
                            value="{{ request('to') }}"
                        >
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                    </div>

                </div>

                @if(request('from') || request('to') || request('filter'))
                    <div class="mt-3">
                        <a href="{{ route('purchaseHistory') }}" class="btn btn-outline-secondary">
                            Clear Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('purchaseHistory', array_filter(['from' => request('from'), 'to' => request('to')])) }}"
           class="btn {{ empty($filter) ? 'btn-success' : 'btn-outline-success' }}">
            All
        </a>

        <a href="{{ route('purchaseHistory', array_filter(['filter' => 'daily', 'from' => request('from'), 'to' => request('to')])) }}"
           class="btn {{ $filter === 'daily' ? 'btn-success' : 'btn-outline-success' }}">
            Daily
        </a>

        <a href="{{ route('purchaseHistory', array_filter(['filter' => 'weekly', 'from' => request('from'), 'to' => request('to')])) }}"
           class="btn {{ $filter === 'weekly' ? 'btn-success' : 'btn-outline-success' }}">
            Weekly
        </a>

        <a href="{{ route('purchaseHistory', array_filter(['filter' => 'monthly', 'from' => request('from'), 'to' => request('to')])) }}"
           class="btn {{ $filter === 'monthly' ? 'btn-success' : 'btn-outline-success' }}">
            Monthly
        </a>

        <a href="{{ route('purchaseHistory', array_filter(['filter' => 'yearly', 'from' => request('from'), 'to' => request('to')])) }}"
           class="btn {{ $filter === 'yearly' ? 'btn-success' : 'btn-outline-success' }}">
            Yearly
        </a>
    </div>

    <div class="mb-3 d-flex justify-content-end gap-2 no-print">
        <a href="{{ route('sales.export.csv', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>

        <button type="button" onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Print
        </button>
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
