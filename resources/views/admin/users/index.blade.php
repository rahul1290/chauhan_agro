@extends('admin.layouts.admin')

@section('title', 'Manage Admin Users & Staff')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Admin Users & Access Control</h1>
        <p class="text-muted mb-0">Manage internal administrative accounts, roles, and operational permissions.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-agro-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Add New User
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach($roles as $role)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold">{{ $role->name }}</div>
                        <div class="h4 fw-bold mb-0 text-dark">{{ $role->users()->count() }}</div>
                    </div>
                    <div class="p-2 bg-light rounded text-agro-forest">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-agro-forest"></i>Administrative Staff Directory</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>User Details</th>
                    <th>Assigned Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-agro-forest text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($user->role->slug ?? '') {
                                    'super-admin' => 'bg-danger text-white',
                                    'content-manager' => 'bg-primary text-white',
                                    'product-manager' => 'bg-success text-white',
                                    'enquiry-manager' => 'bg-warning text-dark',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-2 py-1">
                                <i class="bi bi-shield-check me-1"></i>{{ $user->role->name ?? 'None' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $user->phone ?? '—' }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-success' : 'btn-outline-danger' }}" title="Click to toggle status" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <i class="bi {{ $user->is_active ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary" title="Edit User">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this user account?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
