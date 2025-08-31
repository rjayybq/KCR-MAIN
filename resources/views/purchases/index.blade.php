@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">Purchase History</h1>

    {{-- Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('purchaseHistory') }}" class="row g-3">
                {{-- Customer --}}
                <div class="col-md-4">
                    <input type="text" name="customer" class="form-control"
                           placeholder="Search by Customer"
                           value="{{ request('customer') }}">
                </div>

                {{-- Product --}}
                <div class="col-md-4">
                    <input type="text" name="product" class="form-control"
                           placeholder="Search by Product"
                           value="{{ request('product') }}">
                </div>

                {{-- Date Range --}}
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control"
                           value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control"
                           value="{{ request('to') }}">
                </div>

                {{-- Buttons --}}
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('purchaseHistory') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Purchase History Table --}}
    <div class="table-responsive shadow rounded">
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
                        <td>{{ $purchase->user->name }}</td>
                        <td>{{ $purchase->product->ProductName }}</td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>₱{{ number_format($purchase->total_price, 2) }}</td>
                        <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No purchases found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="p-3">
                        <div class="d-flex justify-content-center">
                            {{ $purchases->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
