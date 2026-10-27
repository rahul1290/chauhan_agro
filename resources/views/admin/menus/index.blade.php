@extends('admin.layouts.admin')

@section('title', 'Navigation Menus')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Navigation Menu Builder</h2>
            <p class="text-muted small mb-0">Build, nest, and translate navigation menus for the website header and footer locations.</p>
        </div>
    </div>

    <!-- Menu Selector Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills bg-white p-2 rounded-4 shadow-sm border">
            @foreach($menus as $m)
                <li class="nav-item">
                    <a class="nav-link {{ $activeMenu && $activeMenu->id === $m->id ? 'active bg-success fw-bold' : 'text-dark' }}" href="{{ route('admin.menus.index', ['menu_id' => $m->id]) }}">
                        <i class="bi bi-menu-button-wide me-1"></i> {{ $m->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if($activeMenu)
        <div class="row g-4">
            <!-- Left 7 Cols: Current Menu Items Tree -->
            <div class="col-lg-7">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-list-nested text-warning me-2"></i>{{ $activeMenu->name }} Structure</h5>
                        <span class="badge bg-light text-dark border">Location: <code>{{ $activeMenu->location }}</code></span>
                    </div>
                    <div class="admin-card-body">
                        @if($activeMenu->allItems->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($activeMenu->allItems->whereNull('parent_id') as $item)
                                    <li class="list-group-item p-3 border rounded-3 mb-2 d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $item->title }}</div>
                                            <small class="text-muted"><code>{{ $item->url }}</code> &bull; Type: {{ ucfirst($item->type) }}</small>
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.menus.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this menu link?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </li>

                                    {{-- Sub-items / Dropdowns --}}
                                    @if($item->children->isNotEmpty())
                                        <div class="ps-4 mb-2">
                                            @foreach($item->children as $child)
                                                <div class="p-2 border rounded-3 mb-1 d-flex justify-content-between align-items-center bg-white">
                                                    <div>
                                                        <span class="text-secondary me-2">↳</span>
                                                        <strong class="text-dark">{{ $child->title }}</strong>
                                                        <small class="text-muted ms-2"><code>{{ $child->url }}</code></small>
                                                    </div>
                                                    <form action="{{ route('admin.menus.items.destroy', $child) }}" method="POST" onsubmit="return confirm('Remove sub-item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-circle"></i></button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-4 text-muted">No links added to this navigation menu yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Add New Item to Menu Form -->
            <div class="col-lg-5">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-plus-circle text-success me-2"></i>Add Link to Menu</h5>
                    </div>
                    <div class="admin-card-body">
                        <form action="{{ route('admin.menus.items.store', $activeMenu) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Parent Item (Optional for Dropdowns)</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">-- None (Top Level Menu Bar) --</option>
                                    @foreach($activeMenu->allItems->whereNull('parent_id') as $pItem)
                                        <option value="{{ $pItem->id }}">{{ $pItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Link Type</label>
                                <select name="type" id="menuItemType" class="form-select" onchange="toggleMenuTypeFields(this.value)">
                                    <option value="custom">Custom URL Link</option>
                                    <option value="page">Link to CMS Page</option>
                                    <option value="category">Link to Product Category</option>
                                </select>
                            </div>

                            <!-- Custom URL Input -->
                            <div class="mb-3" id="customUrlDiv">
                                <label class="form-label fw-semibold small">Target URL Link</label>
                                <input type="text" name="target_url" id="targetUrlInput" class="form-control" placeholder="e.g. /dealer-network or https://...">
                            </div>

                            <!-- CMS Page Picker -->
                            <div class="mb-3 d-none" id="pagePickerDiv">
                                <label class="form-label fw-semibold small">Select CMS Page</label>
                                <select name="target_id" id="pageSelect" class="form-select" onchange="onPageSelected(this)">
                                    <option value="">-- Choose Page --</option>
                                    @foreach($pages as $p)
                                        <option value="{{ $p->id }}" data-slug="/{{ $p->slug }}" data-title-en="{{ $p->translations->firstWhere('locale', 'en')?->title }}" data-title-hi="{{ $p->translations->firstWhere('locale', 'hi')?->title }}">{{ $p->title }} (/{{ $p->slug }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product Category Picker -->
                            <div class="mb-3 d-none" id="catPickerDiv">
                                <label class="form-label fw-semibold small">Select Machinery Category</label>
                                <select name="target_id" id="catSelect" class="form-select" onchange="onCategorySelected(this)">
                                    <option value="">-- Choose Category --</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}" data-slug="/products?category={{ $c->slug }}" data-title-en="{{ $c->translations->firstWhere('locale', 'en')?->name }}" data-title-hi="{{ $c->translations->firstWhere('locale', 'hi')?->name }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Multilingual Labels -->
                            <div class="p-3 bg-light rounded-3 border mb-4">
                                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-translate text-success me-1"></i> Menu Labels</h6>
                                @foreach($languages as $lang)
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold text-muted">{{ $lang->name }} ({{ $lang->flag }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" id="menuTitle_{{ $lang->code }}" class="form-control" required placeholder="Label in {{ $lang->name }}">
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-agro-primary w-100 py-2 fw-bold">
                                <i class="bi bi-plus-circle me-1"></i> Add Item to {{ $activeMenu->name }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function toggleMenuTypeFields(type) {
        document.getElementById('customUrlDiv').classList.toggle('d-none', type !== 'custom');
        document.getElementById('pagePickerDiv').classList.toggle('d-none', type !== 'page');
        document.getElementById('catPickerDiv').classList.toggle('d-none', type !== 'category');
    }

    function onPageSelected(select) {
        const option = select.options[select.selectedIndex];
        if (option && option.value) {
            document.getElementById('targetUrlInput').value = option.dataset.slug || '';
            const titleEn = document.getElementById('menuTitle_en');
            const titleHi = document.getElementById('menuTitle_hi');
            if (titleEn && option.dataset.titleEn) titleEn.value = option.dataset.titleEn;
            if (titleHi && option.dataset.titleHi) titleHi.value = option.dataset.titleHi;
        }
    }

    function onCategorySelected(select) {
        const option = select.options[select.selectedIndex];
        if (option && option.value) {
            document.getElementById('targetUrlInput').value = option.dataset.slug || '';
            const titleEn = document.getElementById('menuTitle_en');
            const titleHi = document.getElementById('menuTitle_hi');
            if (titleEn && option.dataset.titleEn) titleEn.value = option.dataset.titleEn;
            if (titleHi && option.dataset.titleHi) titleHi.value = option.dataset.titleHi;
        }
    }
</script>
@endpush
