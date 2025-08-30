@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5">Products</h1>

        <!-- Create Product Button -->
        <a href="{{ route('products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Create Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="table-responsive shadow rounded">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Categories</th>
                    <th scope="col">Kg/gram</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->ProductName }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->weight . " " . $product->unit }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                       <td>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Order Button -->
                             <form action="{{ route('products.order', $product->id) }}" method="POST" class="d-flex align-items-center justify-content-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        ORDER
                                    </button>
                            </form>


                                <!-- Edit Button -->
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="pt-3">
                        <div class="d-flex justify-content-center">
                            {!! $products->links('vendor.pagination.bootstrap-4') !!}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
