@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 mb-4">📊 Ingredient Stock In-Out-Balance</h1>

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
                    <th>Ingredient</th>
                    <th>In Quantity</th>
                    <th>Date</th>
                    <th>Ingredient</th>
                    <th>Out Quantity</th>
                    <th>Ingredient</th>
                    <th>Balance Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredients as $ingredient)
                    <tr>
                        {{-- Stock In --}}
                        <td>
                            @forelse(($ingredient->movements ?? collect())->where('type', 'in') as $in)
                                {{ $in->date }} <br>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td>{{ $ingredient->name }}</td>
                        <td>
                            @forelse(($ingredient->movements ?? collect())->where('type', 'in') as $in)
                                {{ number_format($in->movement_qty, 2) }} {{ $ingredient->unit }} <br>
                            @empty
                                <span class="text-muted">0</span>
                            @endforelse
                        </td>

                        {{-- Stock Out --}}
                        <td>
                            @forelse(($ingredient->movements ?? collect())->where('type', 'out') as $out)
                                {{ $out->date }} <br>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td>{{ $ingredient->name }}</td>
                        <td>
                            @forelse(($ingredient->movements ?? collect())->where('type', 'out') as $out)
                                {{ number_format($out->movement_qty, 2) }} {{ $ingredient->unit }} <br>
                            @empty
                                <span class="text-muted">0</span>
                            @endforelse
                        </td>

                        {{-- Balance --}}
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ number_format($ingredient->stock ?? 0, 2) }} {{ $ingredient->unit }}</td>
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
