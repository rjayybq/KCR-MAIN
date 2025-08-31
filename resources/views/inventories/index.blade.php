@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5 ms-3">Inventory</h1>

        {{-- Create Product Button --}}
        <a href="{{ route('inventories.create') }}" class="btn btn-success me-3">
            <i class="fa-solid fa-plus me-1"></i> Add Product
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow rounded mt-4">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="bg-success text-white">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Kg/gram</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Price</th>
                    <th scope="col" style="width: 200px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($inventories as $inventory)
                    <tr class="{{ $loop->odd ? 'table-success' : 'table-secondary' }}">
                        <td class="fw-semibold">{{ $inventory->ProductName }}</td>
                        <td class="fw-semibold">{{ $inventory->category->name ?? 'N/A' }}</td>
                        <td class="fw-semibold">{{ $inventory->weight . " " . $inventory->unit }}</td>
                        <td class="fw-semibold">{{ $inventory->stock }}</td>
                        <td class="fw-semibold">₱{{ number_format($inventory->price, 2) }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Update Button --}}
                                <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Update
                                </a>

                                {{-- Remove Button --}}
                                <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="6" class="p-3">
                        <div class="d-flex justify-content-center">
                            {{-- Bootstrap 5 pagination --}}
                            {{ $inventories->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
