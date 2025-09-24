@extends('layouts.app1') {{-- Use cashier layout, not admin/app layout --}}

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3 mb-4">Cashier Profile</h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-body text-center py-5">

            {{-- Profile Picture --}}
            <div class="position-relative d-inline-block mb-4">
                <img id="profilePreview"
                     src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('images/default-avatar.png') }}"
                     alt="Profile Picture"
                     class="rounded-circle shadow"
                     style="width: 160px; height: 160px; object-fit: cover; background:#e9ecef;">

                {{-- Camera Icon Overlay --}}
                <label for="profile_pic" 
                       class="btn btn-light btn-sm rounded-circle shadow position-absolute"
                       style="bottom: 10px; right: 10px; cursor: pointer;">
                    <i class="fa-solid fa-camera text-success"></i>
                </label>
            </div>

            {{-- Profile Update Form --}}
            <form action="{{ route('cashier.profile.update') }}" method="POST" enctype="multipart/form-data"
                  class="text-start mx-auto" style="max-width: 700px;">
                @csrf
                @method('PUT')

                {{-- Hidden file input --}}
                <input type="file" name="profile_pic" id="profile_pic" class="d-none" accept="image/*">

                {{-- Full Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                {{-- Username (read-only for cashier) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->username }}" disabled>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                {{-- New Password --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank if unchanged">
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter new password">
                </div>

                {{-- Role (read-only) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <input type="text" class="form-control bg-light" value="{{ ucfirst($user->role) }}" disabled>
                </div>

                {{-- Save Button --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fa-solid fa-save me-1"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Real-time Preview Script --}}
    <script>
        document.getElementById('profile_pic').addEventListener('change', function(event) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
@endsection
