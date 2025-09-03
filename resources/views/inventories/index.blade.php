@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">📊 Stock In-Out-Balance</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="bg-success text-white">
                <tr>
                    <th colspan="3">Stock In</th>
                    <th colspan="3">Stock Out</th>
                    <th colspan="2">Stock Balance</th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Item Name</th>
                    <th>In Quantity</th>
                    <th>Date</th>
                    <th>Item Name</th>
                    <th>Out Quantity</th>
                    <th>Item Name</th>
                    <th>Balance Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        {{-- Stock In --}}
                        <td>
                            @foreach($stocksIn->where('product_id', $product->id) as $in)
                                {{ $in->date }} <br>
                            @endforeach
                        </td>
                        <td>{{ $product->ProductName }}</td>
                        <td>
                            @foreach($stocksIn->where('product_id', $product->id) as $in)
                                {{ $in->quantity }} <br>
                            @endforeach
                        </td>

                        {{-- Stock Out --}}
                        <td>
                            @foreach($stocksOut->where('product_id', $product->id) as $out)
                                {{ $out->date }} <br>
                            @endforeach
                        </td>
                        <td>{{ $product->ProductName }}</td>
                        <td>
                            @foreach($stocksOut->where('product_id', $product->id) as $out)
                                {{ $out->quantity }} <br>
                            @endforeach
                        </td>

                        {{-- Balance (from products.stock column) --}}
                        <td>{{ $product->ProductName }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
