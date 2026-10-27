@extends('admin.layouts.admin')

@section('title', 'Edit Page: ' . $page->title)

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Pages
            </a>
            <h2 class="fw-bold text-dark mb-1">Edit Page: {{ $page->title ?: $page->slug }}</h2>
            <p class="text-muted small mb-0">Update multilingual translations, SEO meta, template, or configure page sections.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.pages.sections.index', $page) }}" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-layers-fill me-1"></i> Manage Sections ({{ $page->sections()->count() }})
            </a>
            <a href="{{ loc_url('/' . ($page->slug === 'home' ? '' : $page->slug)) }}" target="_blank" class="btn btn-light btn-sm border">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Live
            </a>
        </div>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                @php $hasTrans = $translations->has($lang->code); @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#content-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                        @if($hasTrans)
                                            <span class="badge bg-success bg-opacity-10 text-success ms-1"><i class="bi bi-check2"></i></span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger ms-1" title="Missing translation"><i class="bi bi-exclamation"></i></span>
                                        @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Language Tabs Content -->
                        <div class="tab-content" id="pageLangTabsContent">
                            @foreach($languages as $index => $lang)
                                @php $trans = $translations->get($lang->code); @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Page Title ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old('translations.' . $lang->code . '.title', $trans?->title) }}" class="form-control" placeholder="Page Title in {{ $lang->name }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Language URL Slug</label>
                                        <input type="text" name="translations[{{ $lang->code }}][slug]" value="{{ old('translations.' . $lang->code . '.slug', $trans?->slug) }}" class="form-control" placeholder="e.g. {{ $page->slug }}-{{ $lang->code }}">
                                        <small class="text-muted">Direct localized URL: /{{ $lang->code }}/{{ $trans?->slug ?? $page->slug }}</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Tagline</label>
                                        <input type="text" name="translations[{{ $lang->code }}][subtitle]" value="{{ old('translations.' . $lang->code . '.subtitle', $trans?->subtitle) }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Summary / Excerpt</label>
                                        <textarea name="translations[{{ $lang->code }}][excerpt]" rows="2" class="form-control">{{ old('translations.' . $lang->code . '.excerpt', $trans?->excerpt) }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold small">Main Editorial Body Content (HTML supported)</label>
                                        <textarea name="translations[{{ $lang->code }}][content]" rows="10" class="form-control">{{ old('translations.' . $lang->code . '.content', $trans?->content) }}</textarea>
                                    </div>

                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-search text-warning me-1"></i> SEO Metadata ({{ $lang->name }})</h6>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Meta Title</label>
                                            <input type="text" name="translations[{{ $lang->code }}][meta_title]" value="{{ old('translations.' . $lang->code . '.meta_title', $trans?->meta_title) }}" class="form-control">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small text-muted">Meta Description</label>
                                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2" class="form-control">{{ old('translations.' . $lang->code . '.meta_description', $trans?->meta_description) }}</textarea>
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
                            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="form-control" required {{ in_array($page->slug, ['home', 'contact', 'about-us']) ? 'readonly' : '' }}>
                            <small class="text-muted">Unique identifier for database routing</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Page Template</label>
                            <select name="template" class="form-select">
                                <option value="default" {{ old('template', $page->template) === 'default' ? 'selected' : '' }}>Standard Page Layout</option>
                                <option value="about" {{ old('template', $page->template) === 'about' ? 'selected' : '' }}>About Us Corporate Template</option>
                                <option value="contact" {{ old('template', $page->template) === 'contact' ? 'selected' : '' }}>Contact Form Layout</option>
                                <option value="custom" {{ old('template', $page->template) === 'custom' ? 'selected' : '' }}>Custom Dynamic Section Canvas</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Featured Banner Image</label>
                            @if($page->featured_image)
                                <div class="mb-2 rounded border overflow-hidden" style="max-height: 120px;">
                                    <img src="{{ asset($page->featured_image) }}" alt="Preview" class="img-fluid w-100">
                                </div>
                            @endif
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small class="text-muted">Upload new image to replace</small>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="isPublished">Page is Published Live</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="show_in_nav" id="showInNav" value="1" {{ old('show_in_nav', $page->show_in_nav) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="showInNav">Include in Navigation Bar</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Update Page
                            </button>
                            <a href="{{ route('admin.pages.sections.index', $page) }}" class="btn btn-warning py-2 fw-bold">
                                <i class="bi bi-layers-fill me-1"></i> Manage Reusable Sections
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
