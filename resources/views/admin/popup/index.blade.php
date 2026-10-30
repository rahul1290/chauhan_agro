@extends('admin.layouts.admin')

@section('title', 'Promotional Popup Modal')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Promotional Popup Banner</h2>
        <p class="text-muted small mb-0">Manage the promotional trade exhibition and announcement popup shown to visitors.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-dark btn-sm" onclick="previewAdminPopup()">
            <i class="bi bi-eye me-1"></i> Live Preview Popup
        </button>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-agro-outline btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Live Site
        </a>
    </div>
</div>

{{-- Quick Active/Deactive Banner --}}
<div class="card mb-4 border-0 shadow-sm {{ $popup->is_active ? 'border-start border-success border-4' : 'border-start border-secondary border-4' }}" style="background: {{ $popup->is_active ? '#f0fdf4' : '#f8fafc' }};">
    <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge {{ $popup->is_active ? 'bg-success' : 'bg-secondary' }} fs-6 px-3 py-2 text-uppercase text-wrap text-start" style="max-width: 100%;">
                <i class="bi {{ $popup->is_active ? 'bi-check-circle-fill' : 'bi-slash-circle' }} me-1"></i>
                {{ $popup->is_active ? 'Popup Active (Shows On Homepage On Refresh)' : 'Popup Deactivated (Hidden)' }}
            </span>
            <span class="text-muted small">
                {{ $popup->is_active ? 'Visitors will see this popup on every refresh of the Homepage.' : 'The popup is currently turned off and will not appear to any visitors on the site.' }}
            </span>
        </div>
        <form action="{{ route('admin.popup.toggleStatus') }}" method="POST" class="m-0">
            @csrf
            @if($popup->is_active)
                <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                    <i class="bi bi-power me-1"></i> Deactivate Popup
                </button>
            @else
                <button type="submit" class="btn btn-success btn-sm fw-bold">
                    <i class="bi bi-play-circle-fill me-1"></i> Activate Popup
                </button>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('admin.popup.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Settings Column --}}
        <div class="col-lg-7">
            <div class="admin-card mb-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-sliders2-vertical me-2 text-success"></i>Campaign & Display Settings</h5>
                    <div class="form-check form-switch fs-5 mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $popup->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fs-6 fw-bold ms-1" for="is_active">
                            <span id="activeStatusLabel" class="{{ $popup->is_active ? 'text-success' : 'text-muted' }}">
                                {{ $popup->is_active ? 'Active (Visible)' : 'Deactivated (Hidden)' }}
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Campaign Title --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Campaign / Exhibition Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $popup->title) }}" placeholder="e.g. EIMA International Agricultural Machinery Exhibition 2026" required>
                    <div class="form-text">Internal identifier and accessibility alt-text for the banner image.</div>
                </div>

                {{-- Image Upload & Current Image --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Promotional Banner Flyer / Poster <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" id="imageInput">
                    <div class="form-text">Upload JPG, PNG or WebP image. Recommended ratio 16:9 or 3:2 (e.g. 1200x675px or 1000x600px). Max 5MB.</div>

                    @if($popup->image_path)
                        <div class="mt-3 p-3 bg-light rounded-3 border">
                            <div class="small fw-bold text-muted mb-2">Current Active Banner:</div>
                            <div class="position-relative d-inline-block rounded-3 overflow-hidden shadow-sm" style="max-width: 100%; border: 3px solid {{ $popup->border_color }};">
                                <img src="{{ $popup->image_url }}" alt="{{ $popup->title }}" id="previewCurrentImg" style="max-height: 220px; width: auto; max-width: 100%; display: block; object-fit: contain;">
                            </div>
                            <div class="small text-muted mt-2 font-monospace">{{ $popup->image_path }}</div>
                        </div>
                    @endif
                </div>

                {{-- Action Link --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Click Action / Destination URL (Optional)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                        <input type="text" name="link_url" class="form-control" value="{{ old('link_url', $popup->link_url) }}" placeholder="e.g. /en/contact-us or https://example.com/exhibition-register">
                    </div>
                    <div class="form-text">If provided, clicking anywhere on the banner image redirects visitors to this URL.</div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_blank" id="target_blank" value="1" {{ old('target_blank', $popup->target_blank) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="target_blank">
                            Open link in a new browser tab (<code>target="_blank"</code>)
                        </label>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Border Outline Color --}}
                    <div class="col-sm-6">
                        <label class="form-label fw-bold">Accent Border Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color" id="borderColorPicker" value="{{ old('border_color', $popup->border_color) }}" title="Choose border accent color">
                            <input type="text" name="border_color" id="borderColorInput" class="form-control font-monospace" value="{{ old('border_color', $popup->border_color) }}" style="max-width: 130px;">
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-circle" style="width:24px;height:24px;background:#16a34a;" onclick="setColor('#16a34a')" title="Agro Green"></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-circle" style="width:24px;height:24px;background:#eab308;" onclick="setColor('#eab308')" title="Gold / Amber"></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-circle" style="width:24px;height:24px;background:#ea580c;" onclick="setColor('#ea580c')" title="Industrial Orange"></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-circle" style="width:24px;height:24px;background:#2563eb;" onclick="setColor('#2563eb')" title="Royal Blue"></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-circle" style="width:24px;height:24px;background:#ffffff;border:1px solid #ccc!important;" onclick="setColor('#ffffff')" title="Clean White"></button>
                        </div>
                    </div>

                    {{-- Display Delay --}}
                    <div class="col-sm-6">
                        <label class="form-label fw-bold">Display Delay (Seconds)</label>
                        <div class="input-group">
                            <input type="number" name="display_delay" class="form-control" min="0" max="30" value="{{ old('display_delay', $popup->display_delay) }}" required>
                            <span class="input-group-text">sec</span>
                        </div>
                        <div class="form-text">Delay before showing popup after page load (1 sec recommended).</div>
                    </div>

                    {{-- Display Frequency --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Display Trigger Frequency</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 bg-white {{ old('display_frequency', $popup->display_frequency) === 'always' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                    <input type="radio" name="display_frequency" value="always" {{ old('display_frequency', $popup->display_frequency) === 'always' ? 'checked' : '' }} class="form-check-input me-2">
                                    <strong class="d-block text-dark">Every Page Refresh</strong>
                                    <small class="text-muted">Shows on every page visit and browser refresh whenever popup is Active.</small>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 bg-white {{ old('display_frequency', $popup->display_frequency) === 'session' ? 'border-primary' : '' }}">
                                    <input type="radio" name="display_frequency" value="session" {{ old('display_frequency', $popup->display_frequency) === 'session' ? 'checked' : '' }} class="form-check-input me-2">
                                    <strong class="d-block text-dark">Once per Session</strong>
                                    <small class="text-muted">Shows on first visit. Dismisses until browser is closed and reopened.</small>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 bg-white {{ old('display_frequency', $popup->display_frequency) === 'daily' ? 'border-primary' : '' }}">
                                    <input type="radio" name="display_frequency" value="daily" {{ old('display_frequency', $popup->display_frequency) === 'daily' ? 'checked' : '' }} class="form-check-input me-2">
                                    <strong class="d-block text-dark">Once per 24 Hours</strong>
                                    <small class="text-muted">Shows once per 24 hours per visitor.</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Target Display Pages --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Display Target Pages</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 bg-white {{ ($targetPages ?? 'home') === 'home' ? 'border-success bg-success bg-opacity-10' : '' }}">
                                    <input type="radio" name="target_pages" value="home" {{ ($targetPages ?? 'home') === 'home' ? 'checked' : '' }} class="form-check-input me-2">
                                    <strong class="d-block text-dark"><i class="bi bi-house-door-fill text-success me-1"></i> Homepage Only (Recommended)</strong>
                                    <small class="text-muted">Popup triggers only on Home page refreshes. Other pages (about, products, contact) will never show the popup.</small>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 bg-white {{ ($targetPages ?? 'home') === 'all' ? 'border-primary' : '' }}">
                                    <input type="radio" name="target_pages" value="all" {{ ($targetPages ?? 'home') === 'all' ? 'checked' : '' }} class="form-check-input me-2">
                                    <strong class="d-block text-dark"><i class="bi bi-window-stack text-primary me-1"></i> All Pages Across Website</strong>
                                    <small class="text-muted">Popup triggers on every page across the entire website on refresh.</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-agro-primary px-4 py-2 fw-bold">
                        <i class="bi bi-save me-1"></i> Save Popup Changes
                    </button>
                </div>
            </div>
        </div>

        {{-- Help & Visual Mockup Column --}}
        <div class="col-lg-5">
            <div class="admin-card mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Visitor Experience Overview</h5>
                
                <p class="text-muted small">
                    When visitors land on your website, this high-converting modal automatically appears with a smooth fade-in and backdrop blur, exactly like modern agricultural machinery sites (e.g. Fieldking).
                </p>

                <ul class="list-group list-group-flush small mb-3">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-check2 text-success me-1"></i> Target Page:</span>
                        <span class="fw-bold text-success">
                            <i class="bi bi-house-door-fill me-1"></i> {{ ($targetPages ?? 'home') === 'home' ? 'Homepage Only' : 'All Pages' }}
                        </span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-check2 text-success me-1"></i> Current Mode:</span>
                        <span class="fw-bold {{ $popup->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $popup->is_active ? 'Active on Every Refresh' : 'Deactivated' }}
                        </span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-check2 text-success me-1"></i> Dismiss Trigger:</span>
                        <span class="fw-bold">Circular Close Button, Backdrop Click, or Escape key</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-check2 text-success me-1"></i> Responsive:</span>
                        <span class="fw-bold">Mobile, Tablet & Desktop</span>
                    </li>
                </ul>
            </div>

            <div class="admin-card text-center p-4">
                <h6 class="fw-bold text-dark mb-2">Interactive Preview</h6>
                <p class="text-muted small mb-3">Click below to test the exact popup dialog right now with your current settings.</p>
                <button type="button" class="btn btn-dark w-100 py-2 fw-semibold" onclick="previewAdminPopup()">
                    <i class="bi bi-window me-2"></i> Launch Preview Modal
                </button>
            </div>
        </div>
    </div>
</form>

{{-- Admin Interactive Preview Modal Component --}}
<div id="adminPopupBackdrop" class="admin-popup-overlay" style="display: none;">
    <div class="admin-popup-wrapper">
        <div class="admin-popup-container" id="adminPopupBox" style="border: 3.5px solid {{ $popup->border_color }};">
            <button type="button" class="admin-popup-close-btn" onclick="closeAdminPopup()" title="Close popup">
                <i class="bi bi-x-lg"></i>
            </button>
            @if($popup->link_url)
                <a href="{{ $popup->link_url }}" {{ $popup->target_blank ? 'target="_blank"' : '' }} class="d-block text-decoration-none">
                    <img src="{{ $popup->image_url }}" alt="{{ $popup->title }}" class="admin-popup-img" id="adminModalImg">
                </a>
            @else
                <img src="{{ $popup->image_url }}" alt="{{ $popup->title }}" class="admin-popup-img" id="adminModalImg">
            @endif
        </div>
    </div>
</div>

<style>
.admin-popup-overlay {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.78);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
    animation: popupFadeIn 0.25s ease-out;
}
.admin-popup-wrapper {
    position: relative;
    max-width: 680px;
    width: 100%;
    box-sizing: border-box;
}
.admin-popup-container {
    position: relative;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
    overflow: visible;
    transform: scale(0.96);
    animation: popupScaleUp 0.25s ease-out forwards;
}
.admin-popup-img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 10px;
}
.admin-popup-close-btn {
    position: absolute;
    top: -16px;
    right: -16px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ffffff;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 100;
    transition: transform 0.15s ease, background-color 0.15s ease;
    color: #111827;
    font-size: 16px;
    font-weight: bold;
}
@media (max-width: 576px) {
    .admin-popup-close-btn {
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
}
.admin-popup-close-btn:hover {
    transform: scale(1.12);
    background: #f3f4f6;
    color: #000000;
}
@keyframes popupFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes popupScaleUp {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
const colorPicker = document.getElementById('borderColorPicker');
const colorInput = document.getElementById('borderColorInput');
const activeSwitch = document.getElementById('is_active');
const activeStatusLabel = document.getElementById('activeStatusLabel');

if (colorPicker && colorInput) {
    colorPicker.addEventListener('input', function() {
        colorInput.value = this.value;
        const box = document.getElementById('adminPopupBox');
        if (box) box.style.borderColor = this.value;
    });
    colorInput.addEventListener('input', function() {
        colorPicker.value = this.value;
        const box = document.getElementById('adminPopupBox');
        if (box) box.style.borderColor = this.value;
    });
}

function setColor(hex) {
    if (colorPicker) colorPicker.value = hex;
    if (colorInput) colorInput.value = hex;
    const box = document.getElementById('adminPopupBox');
    if (box) box.style.borderColor = hex;
}

if (activeSwitch && activeStatusLabel) {
    activeSwitch.addEventListener('change', function() {
        if (this.checked) {
            activeStatusLabel.textContent = 'Active (Visible)';
            activeStatusLabel.className = 'text-success';
        } else {
            activeStatusLabel.textContent = 'Deactivated (Hidden)';
            activeStatusLabel.className = 'text-muted';
        }
    });
}

function previewAdminPopup() {
    const backdrop = document.getElementById('adminPopupBackdrop');
    if (backdrop) backdrop.style.display = 'flex';
}

function closeAdminPopup() {
    const backdrop = document.getElementById('adminPopupBackdrop');
    if (backdrop) backdrop.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAdminPopup();
    }
});

const adminBackdrop = document.getElementById('adminPopupBackdrop');
if (adminBackdrop) {
    adminBackdrop.addEventListener('click', function(e) {
        if (e.target === this || e.target.classList.contains('admin-popup-wrapper')) {
            closeAdminPopup();
        }
    });
}
</script>
@endsection
