@extends('admin.layouts.admin')

@section('title', 'Create Admin User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Add New Staff Member</h1>
        <p class="text-muted mb-0">Create administrative access account with custom role authorization.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Ramesh Patel" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="staff@chauhanagroindustries.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+91 98765 43210">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Assigned Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        <option value="">-- Select Access Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }} ({{ $role->description }})
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck" checked>
                        <label class="form-check-label fw-semibold" for="isActiveCheck">
                            Account is Active & Permitted to Login
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-agro-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Create Staff Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 bg-light">
            <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-agro-forest"></i>Role Permissions Guide</h5>
            <div class="mb-3">
                <span class="badge bg-danger mb-1">Super Admin</span>
                <p class="small text-muted mb-0">Full access to settings, database, role management, staff directory, translations, and all modules.</p>
            </div>
            <div class="mb-3">
                <span class="badge bg-primary mb-1">Content Manager</span>
                <p class="small text-muted mb-0">Can edit dynamic pages, homepage hero sliders, reusable sections, navigation menus, and media.</p>
            </div>
            <div class="mb-3">
                <span class="badge bg-success mb-1">Product Manager</span>
                <p class="small text-muted mb-0">Can manage product categories, implement machinery specifications, manage brochures and photos.</p>
            </div>
            <div>
                <span class="badge bg-warning text-dark mb-1">Enquiry Manager</span>
                <p class="small text-muted mb-0">Can view leads, respond to inquiries, download CSV export, and record sales follow-up notes.</p>
            </div>
        </div>
    </div>
</div>
@endsection
