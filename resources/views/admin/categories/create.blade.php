@extends('admin.layouts.admin')

@section('title', 'Add Category')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Categories
        </a>
        <h2 class="fw-bold text-dark mb-1">Create Product Category</h2>
        <p class="text-muted small mb-0">Set up a category or subcategory with multilingual names and descriptions.</p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Details</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="catLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="cattab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#catcontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="catLangTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="catcontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Category Name ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][name]" class="form-control" placeholder="e.g. {{ $lang->code === 'hi' ? 'रोटरी टिलर और रोटावेटर' : 'Rotary Tillers (Rotavators)' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Language URL Slug</label>
                                        <input type="text" name="translations[{{ $lang->code }}][slug]" class="form-control" placeholder="e.g. rotary-tillers-{{ $lang->code }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Description</label>
                                        <textarea name="translations[{{ $lang->code }}][description]" rows="3" class="form-control" placeholder="Summary of this implement group..."></textarea>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold small">SEO Meta Title</label>
                                        <input type="text" name="translations[{{ $lang->code }}][meta_title]" class="form-control">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-gear-fill text-secondary me-2"></i>Attributes</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary System Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control" required placeholder="e.g. rotary-tillers">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Parent Category (optional)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- None (Top-Level Category) --</option>
                                @foreach($parentCategories as $pCat)
                                    <option value="{{ $pCat->id }}">{{ $pCat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Bootstrap Icon Class</label>
                            <input type="text" name="icon" class="form-control" value="bi-gear-wide" placeholder="e.g. bi-circle-half, bi-tractor">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Cover / Banner Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="catFeatured" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="catFeatured">Feature on Homepage</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="catActive" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="catActive">Active & Enabled</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Save Category
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
