<td>
    <div class="d-flex gap-2 justify-content-center">

        {{-- Edit Button --}}
        <a href="{{ route('products.edit', $product->id) }}" 
           class="btn btn-sm btn-outline-warning d-flex align-items-center"
           title="Edit Product">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>

        {{-- Delete Form --}}
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
              onsubmit="return confirm('⚠️ Are you sure you want to delete {{ $product->ProductName }}? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" title="Delete Product">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </form>

    </div>
</td>
