@extends('layouts.app1')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5 ms-3">Account List</h1>

        {{-- Create Account Button --}}
        <a href="{{ route('users.create') }}" class="btn btn-success me-3">
            <i class="fa-solid fa-user-plus me-1"></i> Create Account
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="bg-success text-white">
                <tr>
                    <th scope="col">Account Status</th>
                    <th scope="col">Account Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Email</th>
                    <th scope="col">Cashier No.</th>
                    <th scope="col" style="width: 160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="{{ $loop->odd ? 'table-success' : 'table-secondary' }}">
                        <td class="fw-semibold">{{ ucfirst($user->status) }}</td>
                        <td class="fw-semibold">{{ $user->name ?? 'N/A' }}</td>
                        <td class="fw-semibold">{{ ucfirst($user->role) }}</td>
                        <td class="fw-semibold">{{ $user->email }}</td>
                        <td class="fw-semibold">{{ $user->username }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Update Button --}}
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Update
                                </a>

                                {{-- Remove Button --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to remove this account?');">
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
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
