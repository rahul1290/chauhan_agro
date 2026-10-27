@extends('admin.layouts.admin')

@section('title', 'Add Banner Slide')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Banners
        </a>
        <h2 class="fw-bold text-dark mb-1">Add Hero Banner Slide</h2>
        <p class="text-muted small mb-0">Upload high-resolution photography and configure multilingual headlines.</p>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><i class="bi bi-translate text-success me-2"></i>Multilingual Headlines & CTAs</h5>
                    </div>
                    <div class="admin-card-body">
                        <!-- Language Tabs Header -->
                        <ul class="nav nav-tabs multilingual-tabs" id="bannerLangTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="bantab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#bancontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="bannerLangTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="bancontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Badge Text ({{ $lang->name }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][badge_text]" class="form-control" placeholder="e.g. {{ $lang->code === 'hi' ? 'अगली पीढ़ी की तकनीक' : 'Next-Gen Farming Technology' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Main Headline <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" class="form-control" placeholder="Bold banner headline">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Supporting Description</label>
                                        <textarea name="translations[{{ $lang->code }}][subtitle]" rows="2" class="form-control" placeholder="Short description..."></textarea>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Primary Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_text]" class="form-control" placeholder="e.g. Explore Machinery">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Secondary Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][secondary_button_text]" class="form-control" placeholder="e.g. Request Quote">
                                        </div>
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
                        <h5><i class="bi bi-image text-secondary me-2"></i>Banner Media & Links</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Desktop Background Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" required accept="image/*">
                            <small class="text-muted">Recommended: 1920x800px (Max 5MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Mobile Background (optional)</label>
                            <input type="file" name="mobile_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary Button URL</label>
                            <input type="text" name="button_url" class="form-control" placeholder="e.g. /products/gold-rotary-tiller">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Secondary Button URL</label>
                            <input type="text" name="secondary_button_url" class="form-control" placeholder="e.g. /contact">
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="bActive" value="1" checked>
                            <label class="form-check-label fw-semibold small" for="bActive">Active & Enabled</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Save Banner Slide
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
