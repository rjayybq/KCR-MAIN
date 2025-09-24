@extends('layouts.app1')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">Cashier Profile</h1>

    {{-- Profile Card --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body text-center p-5">
            {{-- Profile Icon --}}
            <div class="position-relative d-inline-block">
                @if(auth()->user()->profile_pic)
                    <img src="{{ asset('storage/' . auth()->user()->profile_pic) }}"
                         alt="Profile Picture"
                         class="rounded-circle shadow"
                         style="width:150px; height:150px; object-fit:cover;">
                @else
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center shadow-lg"
                         style="width: 150px; height: 150px;">
                        <i class="fa-solid fa-user fs-1 text-white"></i>
                    </div>
                @endif

                {{-- Edit Button (Opens Modal) --}}
                <button type="button" class="btn btn-light btn-sm rounded-circle shadow position-absolute bottom-0 end-0"
                        data-bs-toggle="modal" data-bs-target="#editProfileModal" title="Edit Profile">
                    <i class="fa-solid fa-pen text-success"></i>
                </button>
            </div>

            <h3 class="mt-3 fw-bold text-dark">{{ auth()->user()->name }}</h3>
            <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
            <span class="badge bg-success mt-2 px-3 py-2">{{ ucfirst(auth()->user()->role) }}</span>
        </div>
    </div>

    {{-- Account Information Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white fw-semibold">
            Account Information
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 text-center align-middle">
                <thead class="table-success text-dark">
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">{{ ucfirst(auth()->user()->role) }}</td>
                        <td class="fw-semibold">{{ auth()->user()->name }}</td>
                        <td class="fw-semibold">{{ auth()->user()->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Logout --}}
    <div class="text-end mt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger shadow-sm px-4">
                <i class="fa-solid fa-power-off me-1"></i> Log Out
            </button>
        </form>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('cashier.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Profile Picture --}}
                        <div class="mb-3">
                            <label for="profile_pic" class="form-label fw-semibold">Profile Picture</label>
                            <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*">
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ auth()->user()->name }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ auth()->user()->email }}" required>
                        </div>

                        {{-- Password (optional) --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                New Password <small>(leave blank if unchanged)</small>
                            </label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
