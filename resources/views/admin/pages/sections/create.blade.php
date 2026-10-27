@extends('admin.layouts.admin')

@section('title', 'Add Section to ' . $page->title)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.pages.sections.index', $page) }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Sections
        </a>
        <h2 class="fw-bold text-dark mb-1">Add Dynamic Section Component</h2>
        <p class="text-muted small mb-0">Select a pre-built industrial component type and configure its multilingual content.</p>
    </div>

    <form action="{{ route('admin.pages.sections.store', $page) }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Section Content</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="secLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="sectab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#seccontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="secLangTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="seccontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Section Title ({{ $lang->name }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" class="form-control" placeholder="Main section headline">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Badge</label>
                                        <input type="text" name="translations[{{ $lang->code }}][subtitle]" class="form-control" placeholder="Sub-heading or category pill">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Editorial Text / Content (HTML allowed)</label>
                                        <textarea name="translations[{{ $lang->code }}][content]" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">CTA Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_text]" class="form-control" placeholder="e.g. Learn More">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">CTA Button URL</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_url]" class="form-control" placeholder="e.g. /products or /contact">
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold small">Structured JSON Data (optional)</label>
                                        <textarea name="translations[{{ $lang->code }}][data_json]" rows="4" class="form-control font-monospace" placeholder='[{"title":"Feature 1","description":"Desc"}]'></textarea>
                                        <small class="text-muted">Used for repeating cards, counters, or accordion questions.</small>
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
                        <h5><i class="bi bi-gear-fill text-secondary me-2"></i>Component Settings</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Component Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="secActive" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="secActive">Active & Visible</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-plus-circle me-1"></i> Add Section to Page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
