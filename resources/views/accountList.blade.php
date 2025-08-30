@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">Account List</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-success text-white">
                <tr>
                    <th scope="col">Account Status</th>
                    <th scope="col">Account Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="{{ $loop->odd ? 'table-success' : 'table-secondary' }}">
                        <td class="fw-semibold">{{ $user->status }}</td>
                        <td class="fw-semibold">{{ $user->name ?? 'N/A' }}</td>
                        <td class="fw-semibold">{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fa-solid fa-trash me-1"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4" class="p-3">
                        <div class="d-flex justify-content-center">
                            {!! $users->links('vendor.pagination.custom') !!}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
