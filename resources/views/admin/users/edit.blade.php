@extends('admin.layouts.admin')

@section('title', 'Edit Staff Member: ' . $user->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Edit Staff Account: {{ $user->name }}</h1>
        <p class="text-muted mb-0">Update profile details, role assignment, or change login password.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Assigned Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }} ({{ $role->description }})
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card bg-light border-0 p-3 mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-key me-1 text-agro-forest"></i>Change Password (Optional)</h6>
                    <p class="small text-muted mb-3">Leave both fields empty if you do not want to alter the current password.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type new password">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck" {{ old('is_active', $user->is_active) ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActiveCheck">
                            Account is Active & Permitted to Login
                        </label>
                    </div>
                    @if($user->id === auth()->id())
                        <div class="form-text text-muted small">You cannot deactivate your own current login account.</div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-agro-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Update User Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 bg-light">
            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-agro-forest"></i>Account Details</h5>
            <ul class="list-unstyled small mb-0">
                <li class="mb-2"><strong>Account Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</li>
                <li class="mb-2"><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</li>
                <li class="mb-2"><strong>User ID:</strong> #{{ $user->id }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
