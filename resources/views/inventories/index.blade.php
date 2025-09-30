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

                {{-- ✅ Loop ingredients --}}
                @forelse($ingredients as $ingredient)
                    <tr>
                        {{-- Stock In --}}
                        <td>
                            @foreach($stocksIn->where('ingredient_id', $ingredient->id) as $in)
                                {{ $in->date }} <br>
                            @endforeach
                        </td>
                        <td>{{ $ingredient->name }}</td>
                        <td>
                            @foreach($stocksIn->where('ingredient_id', $ingredient->id) as $in)
                                {{ $in->quantity }} <br>
                            @endforeach
                        </td>

                        {{-- Stock Out --}}
                        <td>
                            @foreach($stocksOut->where('ingredient_id', $ingredient->id) as $out)
                                {{ $out->date }} <br>
                            @endforeach
                        </td>
                        <td>{{ $ingredient->name }}</td>
                        <td>
                            @foreach($stocksOut->where('ingredient_id', $ingredient->id) as $out)
                                {{ $out->quantity }} <br>
                            @endforeach
                        </td>

                        {{-- Balance --}}
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ $ingredient->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted">No ingredients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
