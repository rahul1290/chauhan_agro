@extends('admin.layouts.admin')

@section('title', 'Edit Section #' . $section->id)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.pages.sections.index', $page) }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Sections
        </a>
        <h2 class="fw-bold text-dark mb-1">Edit Section: {{ ucfirst($section->type) }}</h2>
        <p class="text-muted small mb-0">Update multilingual texts, buttons, and structured repeatable items.</p>
    </div>

    <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST">
        @csrf
        @method('PUT')

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
                                @php $trans = $translations->get($lang->code); @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="seccontent-{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Section Title ({{ $lang->name }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old('translations.' . $lang->code . '.title', $trans?->title) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Subtitle / Badge</label>
                                        <input type="text" name="translations[{{ $lang->code }}][subtitle]" value="{{ old('translations.' . $lang->code . '.subtitle', $trans?->subtitle) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Editorial Text / Content</label>
                                        <textarea name="translations[{{ $lang->code }}][content]" rows="4" class="form-control">{{ old('translations.' . $lang->code . '.content', $trans?->content) }}</textarea>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">CTA Button Text</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_text]" value="{{ old('translations.' . $lang->code . '.button_text', $trans?->button_text) }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">CTA Button URL</label>
                                            <input type="text" name="translations[{{ $lang->code }}][button_url]" value="{{ old('translations.' . $lang->code . '.button_url', $trans?->button_url) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold small">Structured JSON Data</label>
                                        <textarea name="translations[{{ $lang->code }}][data_json]" rows="6" class="form-control font-monospace">{{ is_array($trans?->data_json) ? json_encode($trans->data_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $trans?->data_json }}</textarea>
                                        <small class="text-muted">Used for feature cards, testimonials, and FAQ items.</small>
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
                        <h5><i class="bi bi-gear-fill text-secondary me-2"></i>Settings</h5>
                    </div>
                    <div class="admin-card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Component Type</label>
                            <input type="text" class="form-control bg-light" value="{{ $section->type }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Section Scroll Animation</label>
                            @php 
                                $secAnim = $section->settings_json['animation_effect'] ?? ''; 
                                $secDur = $section->settings_json['animation_duration'] ?? '';
                            @endphp
                            <select name="settings[animation_effect]" class="form-select mb-2">
                                <option value="" {{ empty($secAnim) ? 'selected' : '' }}>Inherit Global ({{ ucfirst(site_setting('animation_effect', 'fade-up')) }})</option>
                                <option value="fade-up" {{ $secAnim === 'fade-up' ? 'selected' : '' }}>Smooth Glide Up (Fade-Up)</option>
                                <option value="scale-up" {{ $secAnim === 'scale-up' ? 'selected' : '' }}>Fluid Zoom / Scale In</option>
                                <option value="slide-left" {{ $secAnim === 'slide-left' ? 'selected' : '' }}>Slide In From Left</option>
                                <option value="slide-right" {{ $secAnim === 'slide-right' ? 'selected' : '' }}>Slide In From Right</option>
                                <option value="flip-up" {{ $secAnim === 'flip-up' ? 'selected' : '' }}>Modern 3D Tilt / Flip Up</option>
                                <option value="bounce-soft" {{ $secAnim === 'bounce-soft' ? 'selected' : '' }}>Soft Elastic Bounce & Pop</option>
                                <option value="float-glow" {{ $secAnim === 'float-glow' ? 'selected' : '' }}>Ambient Floating Glow</option>
                                <option value="none" {{ $secAnim === 'none' ? 'selected' : '' }}>None (Instant / Static)</option>
                            </select>

                            <label class="form-label fw-semibold small">Animation Speed / Duration</label>
                            <select name="settings[animation_duration]" class="form-select">
                                <option value="" {{ empty($secDur) ? 'selected' : '' }}>Inherit Global ({{ ucfirst(site_setting('animation_duration', 'normal')) }})</option>
                                <option value="fast" {{ $secDur === 'fast' ? 'selected' : '' }}>Fast (0.4s)</option>
                                <option value="normal" {{ $secDur === 'normal' ? 'selected' : '' }}>Normal (0.75s)</option>
                                <option value="relaxed" {{ $secDur === 'relaxed' ? 'selected' : '' }}>Relaxed (1.1s)</option>
                            </select>
                            <small class="text-muted d-block mt-1">Override the global animation effect and duration for this specific section.</small>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="secActive" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="secActive">Active & Visible on Page</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-agro-primary py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Update Section
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
