@extends('layouts.app1')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">💰 My Sales & Purchase History</h1>

    <!-- Income Summary -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="fw-bold text-muted">Today's Income</h6>
                    <h2 class="fw-bold text-success">₱{{ number_format($todayIncome ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="fw-bold text-muted">This Month's Income</h6>
                    <h2 class="fw-bold text-success">₱{{ number_format($monthIncome ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="fw-bold text-muted">Total Sales</h6>
                    <h2 class="fw-bold text-success">₱{{ number_format($totalSales ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    @if($filterDate)
    <div class="alert alert-info ms-3 me-3">
            Showing sales for: <strong>{{ $filterDate->format('M d, Y') }}</strong>
            (Monthly income based on {{ $filterDate->format('F Y') }})
        </div>
    @endif
    <!-- Date Filter -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('cashier.purchase.history') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="sales_date" class="form-label fw-bold text-success">Select Date</label>
                    <input type="date" name="sales_date" id="sales_date" class="form-control"
                        value="{{ request('sales_date') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchase Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold text-success mb-3">
                <i class="fa-solid fa-receipt me-2"></i> My Purchase Records ( {{ Auth::user()->name }} )
            </h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="bg-success text-white">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
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
                                <td>{{ $purchase->customer_name }}</td>
                                <td>{{ $purchase->product->ProductName ?? 'N/A' }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td class="fw-bold text-dark">₱{{ number_format($purchase->total_price, 2) }}</td>
                                <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No purchases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $purchases->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
