@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-success fw-bold display-5">Raw Ingredients</h1>

    <a href="{{ route('ingredients.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add Ingredient
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead class="table-success">
                    <tr>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Unit</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $ingredient)
                        <tr>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ number_format($ingredient->stock, 2) }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this ingredient?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No ingredients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white">
        {{ $ingredients->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
