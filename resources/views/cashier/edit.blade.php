@extends('layouts.app1')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success fw-bold display-5 ms-3">Edit Account</h1>

        <a href="{{ route('users.index') }}" class="btn btn-secondary me-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow rounded">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" id="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password (optional) --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password <small>(leave blank if unchanged)</small></label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Account Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-save me-1"></i> Update Account
                </button>
            </form>
        </div>
    </div>
@endsection
