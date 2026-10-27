@extends('admin.layouts.admin')

@section('title', 'Edit Banner #' . $banner->id)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Banners
        </a>
        <h2 class="fw-bold text-dark mb-1">Edit Banner Slide #{{ $banner->id }}</h2>
        <p class="text-muted small mb-0">Update background photography, multilingual headlines, and action links.</p>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                @php $trans = $translations->get($lang->code); @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="bantab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#bancontent-{{ $lang->code }}" type="button" role="tab">
                                        <span>{{ $lang->flag }}</span>
                                        <span>{{ $lang->name }}</span>
                                        @if($trans) <i class="bi bi-check2 text-success"></i> @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="bannerLangTabsContent">
                            @foreach($languages as $index => $lang)
                                @php $trans = $translations->get($lang->code); @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="bancontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Badge Text ({{ $lang->name }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][badge_text]" value="{{ old('translations.' . $lang->code . '.badge_text', $trans?->badge_text) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Main Headline <span class="text-danger">*</span></label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old('translations.' . $lang->code . '.title', $trans?->title) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Supporting Description</label>
                                        <textarea name="translations[{{ $lang->code }}][subtitle]" rows="2" class="form-control">{{ old('translations.' . $lang->code . '.subtitle', $trans?->subtitle) }}</textarea>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Primary Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_text]" value="{{ old('translations.' . $lang->code . '.button_text', $trans?->button_text) }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Secondary Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][secondary_button_text]" value="{{ old('translations.' . $lang->code . '.secondary_button_text', $trans?->secondary_button_text) }}" class="form-control">
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
                            <label class="form-label fw-semibold small">Current Background Image</label>
                            <div class="rounded border overflow-hidden mb-2" style="max-height: 120px;">
                                <img src="{{ asset($banner->image_path) }}" alt="Preview" class="img-fluid w-100">
                            </div>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Upload new image to replace</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Primary Button URL</label>
                            <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Secondary Button URL</label>
                            <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $banner->secondary_button_url) }}" class="form-control">
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="bActive" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="bActive">Active & Enabled</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Update Banner Slide
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
