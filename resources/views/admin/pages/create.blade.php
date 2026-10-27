@extends('admin.layouts.admin')

@section('title', 'Create New Page')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Pages
        </a>
        <h2 class="fw-bold text-dark mb-1">Create New CMS Page</h2>
        <p class="text-muted small mb-0">Build custom pages with multilingual content, SEO metadata, and dynamic layout options.</p>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Left 8 Cols: Multilingual Content Tabs -->
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Page Content</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="pageLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#content-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                        @if($lang->is_default)
                                            <span class="badge bg-success bg-opacity-25 text-success ms-1" style="font-size: 0.65rem;">Default</span>
                                        @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Language Tabs Content -->
                        <div class="tab-content" id="pageLangTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Page Title ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old('translations.' . $lang->code . '.title') }}" class="form-control" placeholder="e.g. {{ $lang->code === 'hi' ? 'वारंटी विवरण' : 'Warranty Policy' }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Language URL Slug (optional)</label>
                                        <input type="text" name="translations[{{ $lang->code }}][slug]" value="{{ old('translations.' . $lang->code . '.slug') }}" class="form-control" placeholder="e.g. {{ $lang->code === 'hi' ? 'warranty-policy-hi' : 'warranty-policy' }}">
                                        <small class="text-muted">Localized slug used in /{{ $lang->code }}/slug</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Tagline</label>
                                        <input type="text" name="translations[{{ $lang->code }}][subtitle]" value="{{ old('translations.' . $lang->code . '.subtitle') }}" class="form-control" placeholder="Brief subheading displayed in page banner">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Summary / Excerpt</label>
                                        <textarea name="translations[{{ $lang->code }}][excerpt]" rows="2" class="form-control" placeholder="Short preview text for search and cards">{{ old('translations.' . $lang->code . '.excerpt') }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold small">Main Editorial Body Content (HTML supported)</label>
                                        <textarea name="translations[{{ $lang->code }}][content]" rows="10" class="form-control" placeholder="<p>Write your detailed page content here...</p>">{{ old('translations.' . $lang->code . '.content') }}</textarea>
                                    </div>

                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-search text-warning me-1"></i> SEO Metadata ({{ $lang->name }})</h6>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Meta Title</label>
                                            <input type="text" name="translations[{{ $lang->code }}][meta_title]" value="{{ old('translations.' . $lang->code . '.meta_title') }}" class="form-control" placeholder="Custom Google title">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small text-muted">Meta Description</label>
                                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2" class="form-control" placeholder="Concise description for search engines">{{ old('translations.' . $lang->code . '.meta_description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 4 Cols: Publishing Settings -->
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-gear-fill text-secondary me-2"></i>Page Attributes</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary System Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" required placeholder="e.g. warranty-policy">
                            <small class="text-muted">Unique identifier for database routing</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Page Template</label>
                            <select name="template" class="form-select">
                                <option value="default" {{ old('template') === 'default' ? 'selected' : '' }}>Standard Page Layout</option>
                                <option value="about" {{ old('template') === 'about' ? 'selected' : '' }}>About Us Corporate Template</option>
                                <option value="contact" {{ old('template') === 'contact' ? 'selected' : '' }}>Contact Form Layout</option>
                                <option value="custom" {{ old('template') === 'custom' ? 'selected' : '' }}>Custom Dynamic Section Canvas</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Featured Banner Image</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small class="text-muted">Max 4MB image (JPG, PNG, WebP)</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="isPublished">Publish Page Live Immediately</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="show_in_nav" id="showInNav" value="1" {{ old('show_in_nav') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="showInNav">Include in Navigation Bar</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Save & Create Page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
