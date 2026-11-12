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

    <!-- Filters & Sorting Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-3">
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
                    <option value="">-- All Status --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="order_asc" {{ ($sort === 'order' && $direction === 'asc') ? 'selected' : '' }}>Order (1 to 9)</option>
                    <option value="order_desc" {{ ($sort === 'order' && $direction === 'desc') ? 'selected' : '' }}>Order (9 to 1)</option>
                    <option value="name_asc" {{ ($sort === 'name' && $direction === 'asc') ? 'selected' : '' }}>Model (A - Z)</option>
                    <option value="name_desc" {{ ($sort === 'name' && $direction === 'desc') ? 'selected' : '' }}>Model (Z - A)</option>
                    <option value="hp_asc" {{ ($sort === 'hp' && $direction === 'asc') || ($sort === 'tractor_hp_min' && $direction === 'asc') ? 'selected' : '' }}>HP: Low to High</option>
                    <option value="hp_desc" {{ ($sort === 'hp' && $direction === 'desc') || ($sort === 'tractor_hp_min' && $direction === 'desc') ? 'selected' : '' }}>HP: High to Low</option>
                    <option value="category_asc" {{ ($sort === 'category' && $direction === 'asc') ? 'selected' : '' }}>Category Name</option>
                    <option value="newest" {{ $sort === 'newest' || ($sort === 'created_at' && $direction === 'desc') ? 'selected' : '' }}>Newest Added</option>
                    <option value="oldest" {{ $sort === 'oldest' || ($sort === 'created_at' && $direction === 'asc') ? 'selected' : '' }}>Oldest Added</option>
                    <option value="featured_desc" {{ ($sort === 'featured' || $sort === 'is_featured') ? 'selected' : '' }}>Featured First</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-agro-primary btn-sm px-3">Filter</button>
                @if(request()->hasAny(['search', 'category_id', 'status', 'featured', 'sort', 'direction']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm border" title="Reset Filters & Sorting">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Card -->
    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><i class="bi bi-tractor text-success me-2"></i>Machinery List ({{ $products->total() }})</h5>
            </div>
            <div class="small text-muted d-flex align-items-center gap-2">
                @if($sort === 'order' && $direction === 'asc')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2">
                        <i class="bi bi-arrows-move me-1"></i> Drag rows or use arrows to change sequence
                    </span>
                @else
                    <a href="{{ route('admin.products.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'order', 'direction' => 'asc'])) }}" class="btn btn-sm btn-light border py-1 px-2" title="Switch to display order mode to reorder items">
                        <i class="bi bi-sort-numeric-down me-1"></i> Switch to Display Order Mode
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table admin-table" id="sortableProductTable">
                <thead>
                    <tr>
                        @php
                            $orderSortUrl = route('admin.products.index', array_merge(request()->query(), [
                                'sort' => 'order',
                                'direction' => ($sort === 'order' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $nameSortUrl = route('admin.products.index', array_merge(request()->query(), [
                                'sort' => 'name',
                                'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $catSortUrl = route('admin.products.index', array_merge(request()->query(), [
                                'sort' => 'category',
                                'direction' => ($sort === 'category' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $hpSortUrl = route('admin.products.index', array_merge(request()->query(), [
                                'sort' => 'hp',
                                'direction' => ($sort === 'hp' && $direction === 'asc') ? 'desc' : 'asc'
                            ]));
                            $statusSortUrl = route('admin.products.index', array_merge(request()->query(), [
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
                        <th style="width: 70px;">Image</th>
                        <th>
                            <a href="{{ $nameSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by model name">
                                <span>Model Name / SKU</span>
                                @if($sort === 'name')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ $catSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by category">
                                <span>Category</span>
                                @if($sort === 'category')
                                    <i class="bi bi-chevron-{{ $direction === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="bi bi-arrow-down-up text-muted opacity-50" style="font-size: 0.75rem;"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ $hpSortUrl }}" class="text-decoration-none text-dark d-flex align-items-center gap-1" title="Sort by tractor HP">
                                <span>Tractor HP</span>
                                @if($sort === 'hp' || $sort === 'tractor_hp_min')
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
                <tbody id="productTableBody">
                    @forelse($products as $p)
                        <tr data-id="{{ $p->id }}" class="product-row {{ ($sort === 'order' && $direction === 'asc') ? 'is-reorderable' : '' }}" draggable="{{ ($sort === 'order' && $direction === 'asc') ? 'true' : 'false' }}">
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="drag-handle text-muted me-1 {{ ($sort === 'order' && $direction === 'asc') ? 'cursor-grab' : 'opacity-25' }}" title="{{ ($sort === 'order' && $direction === 'asc') ? 'Drag to change sequence' : 'Drag reordering active in Display Order view' }}">
                                        <i class="bi bi-grip-vertical fs-5"></i>
                                    </span>
                                    <span class="badge bg-light text-dark border order-badge px-2 py-1 fw-bold">#{{ $p->order }}</span>
                                    <div class="d-flex flex-column gap-1 ms-1">
                                        <form action="{{ route('admin.products.reorder', array_merge([$p, 'up'], request()->only('category_id'))) }}" method="POST" class="d-inline reorder-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border py-0 px-1 btn-move" title="Move Up"><i class="bi bi-chevron-up" style="font-size: 0.7rem;"></i></button>
                                        </form>
                                        <form action="{{ route('admin.products.reorder', array_merge([$p, 'down'], request()->only('category_id'))) }}" method="POST" class="d-inline reorder-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border py-0 px-1 btn-move" title="Move Down"><i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
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
                            <td colspan="8" class="text-center py-5 text-muted">No machinery models found matching your filters.</td>
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

    <!-- Reorder Feedback Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="productReorderToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span id="productReorderToastMsg">Product sequence updated successfully!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('productTableBody');
    if (!tableBody) return;

    const isReorderable = {{ ($sort === 'order' && $direction === 'asc') ? 'true' : 'false' }};
    const toastEl = document.getElementById('productReorderToast');
    const toastMsg = document.getElementById('productReorderToastMsg');
    const bsToast = toastEl ? new bootstrap.Toast(toastEl, { delay: 3500 }) : null;

    function showToast(msg, isError = false) {
        if (!toastEl) return;
        toastEl.className = 'toast align-items-center text-white border-0 shadow-lg ' + (isError ? 'bg-danger' : 'bg-success');
        if (toastMsg) toastMsg.innerText = msg;
        if (bsToast) bsToast.show();
    }

    if (isReorderable) {
        let draggedRow = null;

        tableBody.querySelectorAll('tr.product-row').forEach(row => {
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
                const rows = Array.from(tableBody.querySelectorAll('tr.product-row'));
                const ids = rows.map((r, index) => {
                    const badge = r.querySelector('.order-badge');
                    if (badge) badge.innerText = '#' + (index + 1);
                    return r.dataset.id;
                });

                // Send AJAX update
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch('{{ route("admin.products.update-order") }}', {
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
                        showToast(data.message || 'Product sequence updated successfully!');
                    } else {
                        showToast('Failed to save product order.', true);
                    }
                })
                .catch(err => {
                    console.error('Error saving product sequence:', err);
                    showToast('Network error while saving product order.', true);
                });
            });
        });
    }
});
</script>
@endpush
