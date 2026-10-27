@extends('admin.layouts.admin')

@section('title', 'Product Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Product Categories & Lines</h2>
            <p class="text-muted small mb-0">Organize machinery products into structured categories and subcategories with multilingual descriptions.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-agro-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Category
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Icon</th>
                        <th>Category Name / Slug</th>
                        <th>Parent Category</th>
                        <th>Products</th>
                        <th>Translations</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <div class="bg-light p-2 rounded-3 text-center border text-success fs-5">
                                    <i class="bi {{ $cat->icon ?? 'bi-gear-wide' }}"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $cat->name }}</div>
                                <small class="text-muted"><code>/{{ $cat->slug }}</code></small>
                            </td>
                            <td>
                                @if($cat->parent)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $cat->parent->name }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success">Root Category</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $cat->products->count() }} Models</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($cat->hasTranslation('en')) <span class="badge bg-success bg-opacity-10 text-success">EN</span> @endif
                                    @if($cat->hasTranslation('hi')) <span class="badge bg-success bg-opacity-10 text-success">HI</span> @endif
                                </div>
                            </td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?');">
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
                            <td colspan="7" class="text-center py-4 text-muted">No categories created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
