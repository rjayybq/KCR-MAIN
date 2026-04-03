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

    <h1 class="text-success fw-bold display-5 mb-4">📊 Ingredient Stock In-Out-Balance</h1>

    <div class="mb-3 d-flex justify-content-end gap-2 no-print">
        <a href="{{ route('inventory.export.csv') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>

        <button type="button" onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>

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
