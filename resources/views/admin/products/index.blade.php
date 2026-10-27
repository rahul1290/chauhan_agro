@extends('admin.layouts.admin')

@section('title', 'Machinery Catalogue')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Machinery & Product Catalogue</h2>
            <p class="text-muted small mb-0">Manage agricultural implements, technical specs, brochures, and multilingual descriptions.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Machinery Item
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, SKU, or slug...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm">Filter</button>
                @if(request()->hasAny(['search', 'category_id', 'status', 'featured']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm border">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Card -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Model Name / SKU</th>
                        <th>Category</th>
                        <th>Tractor HP</th>
                        <th>Translations</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>
                                <div class="rounded-3 border overflow-hidden bg-light" style="width: 60px; height: 48px;">
                                    <img src="{{ asset($p->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg') }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="text-dark text-decoration-none">
                                        {{ $p->name ?: 'Untitled Machine' }}
                                    </a>
                                    @if($p->is_featured)
                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.68rem;">Featured</span>
                                    @endif
                                </div>
                                <small class="text-muted"><code>{{ $p->sku ?: $p->slug }}</code></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->category->name ?? 'None' }}</span>
                            </td>
                            <td>
                                @if($p->hp_range)
                                    <span class="badge bg-dark bg-opacity-10 text-dark fw-bold"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>{{ $p->hp_range }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($p->hasTranslation('en')) <span class="badge bg-success bg-opacity-10 text-success">EN</span> @endif
                                    @if($p->hasTranslation('hi')) <span class="badge bg-success bg-opacity-10 text-success">HI</span> @endif
                                </div>
                            </td>
                            <td>
                                @if($p->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ loc_url('/products/' . $p->slug) }}" target="_blank" class="btn btn-sm btn-light border" title="Preview Product">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this machinery model?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No machinery models found matching filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
