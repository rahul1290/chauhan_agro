@extends('admin.layouts.admin')

@section('title', 'Product Categories')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Product Categories & Lines</h2>
            <p class="text-muted small mb-0">Organize machinery products into structured categories and subcategories with multilingual descriptions.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-agro-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Category
            </a>
        </div>
    </div>

    <!-- Filters & Sorting Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name or slug...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="parent_id" class="form-select">
                    <option value="">-- All Categories (Hierarchy) --</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Root Categories Only</option>
                    @foreach($parentCategories as $pCat)
                        <option value="{{ $pCat->id }}" {{ request('parent_id') == $pCat->id ? 'selected' : '' }}>Under {{ $pCat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- All Status --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="order_asc" {{ ($sort === 'order' && $direction === 'asc') ? 'selected' : '' }}>Order (1 to 9)</option>
                    <option value="order_desc" {{ ($sort === 'order' && $direction === 'desc') ? 'selected' : '' }}>Order (9 to 1)</option>
                    <option value="name_asc" {{ ($sort === 'name' && $direction === 'asc') ? 'selected' : '' }}>Name (A - Z)</option>
                    <option value="name_desc" {{ ($sort === 'name' && $direction === 'desc') ? 'selected' : '' }}>Name (Z - A)</option>
                    <option value="products_desc" {{ ($sort === 'products' && $direction === 'desc') || ($sort === 'products_count' && $direction === 'desc') ? 'selected' : '' }}>Most Products</option>
                    <option value="products_asc" {{ ($sort === 'products' && $direction === 'asc') || ($sort === 'products_count' && $direction === 'asc') ? 'selected' : '' }}>Fewest Products</option>
                    <option value="newest" {{ $sort === 'newest' || ($sort === 'created_at' && $direction === 'desc') ? 'selected' : '' }}>Newest Added</option>
                    <option value="oldest" {{ $sort === 'oldest' || ($sort === 'created_at' && $direction === 'asc') ? 'selected' : '' }}>Oldest Added</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm px-3">Filter</button>
                @if(request()->hasAny(['search', 'parent_id', 'status', 'sort', 'direction']))
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm border" title="Reset Filters & Sorting">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-grid-fill text-success me-2"></i>Categories List ({{ $categories->total() }})</h5>
            </div>
            <div class="small text-muted d-flex align-items-center gap-2">
                @if($sort === 'order' && $direction === 'asc')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2">
                        <i class="bi bi-arrows-move me-1"></i> Drag rows or use arrows to change sequence
                    </span>
                @else
                    <a href="{{ route('admin.categories.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'order', 'direction' => 'asc'])) }}" class="btn btn-sm btn-light border py-1 px-2" title="Switch to display order mode to reorder items">
                        <i class="bi bi-sort-numeric-down me-1"></i> Switch to Display Order Mode
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table" id="sortableCategoryTable">
                <thead>
                    <tr>
                        @php
                            $orderSortUrl = route('admin.categories.index', array_merge(request()->query(), [
                                'sort' => 'order',
                                'direction' => ($sort === 'order' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $nameSortUrl = route('admin.categories.index', array_merge(request()->query(), [
                                'sort' => 'name',
                                'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $productSortUrl = route('admin.categories.index', array_merge(request()->query(), [
                                'sort' => 'products_count',
                                'direction' => ($sort === 'products_count' && $direction === 'desc') ? 'asc' : 'desc'
                            ]));
                            $statusSortUrl = route('admin.categories.index', array_merge(request()->query(), [
                                'sort' => 'status',
                                'direction' => ($sort === 'status' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                        @endphp
                        <th style="width: 130px;">
                            <a href="{{ $orderSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by display order">
                                <span># Order</span>
                                @if($sort === 'order')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width: 60px;">Icon</th>
                        <th>
                            <a href="{{ $nameSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by category name">
                                <span>Category Name / Slug</span>
                                @if($sort === 'name')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th>Parent Category</th>
                        <th>
                            <a href="{{ $productSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by number of products">
                                <span>Products</span>
                                @if($sort === 'products_count' || $sort === 'products')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th>Translations</th>
                        <th>
                            <a href="{{ $statusSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by status">
                                <span>Status</span>
                                @if($sort === 'status' || $sort === 'is_active')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    @forelse($categories as $cat)
                        <tr data-id="{{ $cat->id }}" class="category-row {{ ($sort === 'order' && $direction === 'asc') ? 'is-reorderable' : '' }}" draggable="{{ ($sort === 'order' && $direction === 'asc') ? 'true' : 'false' }}">
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="drag-handle text-muted me-1 {{ ($sort === 'order' && $direction === 'asc') ? 'cursor-grab' : 'opacity-25' }}" title="{{ ($sort === 'order' && $direction === 'asc') ? 'Drag to change sequence' : 'Drag reordering active in Display Order view' }}">
                                        <i class="bi bi-grip-vertical fs-5"></i>
                                    </span>
                                    <span class="badge bg-light text-dark border order-badge px-2 py-1 fw-bold">#{{ $cat->order }}</span>
                                    <div class="d-flex flex-column gap-1 ms-1">
                                        <form action="{{ route('admin.categories.reorder', [$cat, 'up']) }}" method="POST" class="d-inline reorder-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border py-0 px-1 btn-move" title="Move Up"><i class="bi bi-chevron-up" style="font-size: 0.7rem;"></i></button>
                                        </form>
                                        <form action="{{ route('admin.categories.reorder', [$cat, 'down']) }}" method="POST" class="d-inline reorder-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border py-0 px-1 btn-move" title="Move Down"><i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
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
                                <span class="badge bg-light text-dark border">{{ $cat->products_count ?? $cat->products->count() }} Models</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($cat->hasTranslation('en')) <span class="badge bg-success bg-opacity-10 text-success">EN</span> @endif
                                    @if($cat->hasTranslation('hi')) <span class="badge bg-success bg-opacity-10 text-success">HI</span> @endif
                                </div>
                            </td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">Inactive</span>
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
                            <td colspan="8" class="text-center py-4 text-muted">No categories found matching your filters.</td>
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

    <!-- Reorder Feedback Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="reorderToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span id="reorderToastMsg">Category sequence updated successfully!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('categoryTableBody');
    if (!tableBody) return;

    const isReorderable = {{ ($sort === 'order' && $direction === 'asc') ? 'true' : 'false' }};
    const toastEl = document.getElementById('reorderToast');
    const toastMsg = document.getElementById('reorderToastMsg');
    const bsToast = toastEl ? new bootstrap.Toast(toastEl, { delay: 3500 }) : null;

    function showToast(msg, isError = false) {
        if (!toastEl) return;
        toastEl.className = 'toast align-items-center text-white border-0 shadow-lg ' + (isError ? 'bg-danger' : 'bg-success');
        if (toastMsg) toastMsg.innerText = msg;
        if (bsToast) bsToast.show();
    }

    if (isReorderable) {
        let draggedRow = null;

        tableBody.querySelectorAll('tr.category-row').forEach(row => {
            row.addEventListener('dragstart', function (e) {
                draggedRow = this;
                this.classList.add('is-dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', this.dataset.id);
            });

            row.addEventListener('dragend', function () {
                this.classList.remove('is-dragging');
                tableBody.querySelectorAll('tr').forEach(r => r.classList.remove('drag-over-top', 'drag-over-bottom'));
            });

            row.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (!draggedRow || draggedRow === this) return;

                const rect = this.getBoundingClientRect();
                const offset = e.clientY - rect.top;
                tableBody.querySelectorAll('tr').forEach(r => r.classList.remove('drag-over-top', 'drag-over-bottom'));

                if (offset < rect.height / 2) {
                    this.classList.add('drag-over-top');
                } else {
                    this.classList.add('drag-over-bottom');
                }
            });

            row.addEventListener('dragleave', function () {
                this.classList.remove('drag-over-top', 'drag-over-bottom');
            });

            row.addEventListener('drop', function (e) {
                e.preventDefault();
                this.classList.remove('drag-over-top', 'drag-over-bottom');
                if (!draggedRow || draggedRow === this) return;

                const rect = this.getBoundingClientRect();
                const offset = e.clientY - rect.top;

                if (offset < rect.height / 2) {
                    tableBody.insertBefore(draggedRow, this);
                } else {
                    tableBody.insertBefore(draggedRow, this.nextSibling);
                }

                // Update visual order badge
                const rows = Array.from(tableBody.querySelectorAll('tr.category-row'));
                const ids = rows.map((r, index) => {
                    const badge = r.querySelector('.order-badge');
                    if (badge) badge.innerText = '#' + (index + 1);
                    return r.dataset.id;
                });

                // Send AJAX update
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch('{{ route("admin.categories.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Category sequence updated successfully!');
                    } else {
                        showToast('Failed to save category order.', true);
                    }
                })
                .catch(err => {
                    console.error('Error saving category sequence:', err);
                    showToast('Network error while saving category order.', true);
                });
            });
        });
    }
});
</script>
@endpush
