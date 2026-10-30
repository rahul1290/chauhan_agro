@extends('admin.layouts.admin')

@section('title', 'Global Website & Business Settings')

@section('content')
@php
    $isUnderConstruction = (bool) site_setting('under_construction_enabled', false);
    $clientSecret = $allSettings['under_construction_secret']->value ?? 'chauhan2026';
    $clientShareUrl = url('/') . '?client_access=' . $clientSecret;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Global Website Settings</h1>
        <p class="text-muted mb-0">Configure company branding, contact channels, maps, social media, SEO, and Under Construction mode.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Quick Under Construction Banner -->
<div class="card mb-4 border-0 shadow-sm {{ $isUnderConstruction ? 'border-start border-warning border-4' : 'border-start border-success border-4' }}" style="background: {{ $isUnderConstruction ? '#fffbeb' : '#f0fdf4' }};">
    <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="badge {{ $isUnderConstruction ? 'bg-warning text-dark' : 'bg-success' }} fs-6 px-3 py-2 text-uppercase fw-bold">
                <i class="bi {{ $isUnderConstruction ? 'bi-cone-striped' : 'bi-globe2' }} me-1"></i>
                {{ $isUnderConstruction ? 'Under Construction Mode: ACTIVE' : 'Public Website: LIVE FOR EVERYONE' }}
            </span>
            <span class="text-muted small">
                @if($isUnderConstruction)
                    Public visitors see the Under Construction page. You and clients with the secret link can access the full site.
                @else
                    All visitors can freely browse the full website and machinery catalog.
                @endif
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($isUnderConstruction)
                <button type="button" class="btn btn-outline-dark btn-sm fw-bold" onclick="copyClientLink('{{ $clientShareUrl }}')">
                    <i class="bi bi-link-45deg me-1"></i> Copy Client Share Link
                </button>
            @endif
            <form action="{{ route('admin.settings.toggleUnderConstruction') }}" method="POST" class="m-0">
                @csrf
                @if($isUnderConstruction)
                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-globe me-1"></i> Make Website Live
                    </button>
                @else
                    <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">
                        <i class="bi bi-cone-striped me-1"></i> Enable Under Construction
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="list-group shadow-sm border-0 sticky-top" style="top: 100px;">
                <a class="list-group-item list-group-item-action active py-3 fw-semibold" id="tab-general-link" data-bs-toggle="list" href="#tab-general">
                    <i class="bi bi-building me-2"></i> Company & Brand
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold text-warning" id="tab-construction-link" data-bs-toggle="list" href="#tab-construction">
                    <i class="bi bi-cone-striped me-2"></i> Under Construction
                    @if($isUnderConstruction)
                        <span class="badge bg-danger ms-1 small">ON</span>
                    @endif
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold" id="tab-contact-link" data-bs-toggle="list" href="#tab-contact">
                    <i class="bi bi-telephone me-2"></i> Contact & Toll-Free
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold" id="tab-map-link" data-bs-toggle="list" href="#tab-map">
                    <i class="bi bi-geo-alt me-2"></i> Factory Map & Address
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold" id="tab-social-link" data-bs-toggle="list" href="#tab-social">
                    <i class="bi bi-share me-2"></i> Social Media Links
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold" id="tab-seo-link" data-bs-toggle="list" href="#tab-seo">
                    <i class="bi bi-search me-2"></i> Default SEO & Meta
                </a>
                <a class="list-group-item list-group-item-action py-3 fw-semibold text-primary" id="tab-animations-link" data-bs-toggle="list" href="#tab-animations">
                    <i class="bi bi-magic me-2"></i> Animations & Transitions
                </a>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content">
                <!-- General Tab -->
                <div class="tab-pane fade show active" id="tab-general">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-building me-2"></i>Company & Brand Identity</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Company / Website Name</label>
                            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $allSettings['site_name']->value ?? 'Chauhan Agro Industries') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Brand Tagline</label>
                            <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $allSettings['site_tagline']->value ?? 'Pioneering Heavy Duty Agricultural Machinery & Implements Worldwide') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo Path / URL</label>
                            <div class="input-group">
                                <input type="text" name="logo_path" class="form-control" value="{{ old('logo_path', $allSettings['logo_path']->value ?? 'assets/images/branding/logo.svg') }}">
                                <a href="{{ route('admin.media.index') }}" target="_blank" class="btn btn-outline-secondary">Browse Media</a>
                            </div>
                            <div class="form-text">Relative path in public directory (e.g. <code>assets/images/branding/logo.svg</code>) or uploaded media URL.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Established Year</label>
                                <input type="text" name="established_year" class="form-control" value="{{ old('established_year', $allSettings['established_year']->value ?? '1989') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ISO & Quality Certifications</label>
                                <input type="text" name="iso_certification" class="form-control" value="{{ old('iso_certification', $allSettings['iso_certification']->value ?? 'ISO 9001:2015 & CE Certified') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Under Construction Tab -->
                <div class="tab-pane fade" id="tab-construction">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <input type="hidden" name="under_construction_section_present" value="1">
                        
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-cone-striped me-2 text-warning"></i>Under Construction & Client Preview Mode</h5>
                                <p class="text-muted small mb-0">Show an Under Construction page to the public while allowing your client and staff to view the full website.</p>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="under_construction_enabled" name="under_construction_enabled" value="1" {{ old('under_construction_enabled', $isUnderConstruction) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- Client Access Shareable Link Box -->
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold text-dark small"><i class="bi bi-share text-primary me-1"></i> Special Client Preview Link (Send to Client):</div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyClientLink('{{ $clientShareUrl }}')">
                                    <i class="bi bi-clipboard me-1"></i> Copy Link
                                </button>
                            </div>
                            <div class="input-group">
                                <input type="text" id="clientShareInput" class="form-control font-monospace bg-white" readonly value="{{ $clientShareUrl }}">
                                <a href="{{ $clientShareUrl }}" target="_blank" class="btn btn-outline-secondary">Test Link</a>
                            </div>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-info-circle text-info me-1"></i>
                                Send this link to your client via WhatsApp or Email. Opening it automatically sets a 30-day preview cookie and unlocks the complete website for them!
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Client Secret Passkey</label>
                                <input type="text" name="under_construction_secret" id="under_construction_secret" class="form-control font-monospace" value="{{ old('under_construction_secret', $clientSecret) }}" required>
                                <div class="form-text">Passkey for unlocking the site via direct URL (<code>?client_access=KEY</code>) or via the passkey input on the page.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Page Title / Heading</label>
                                <input type="text" name="under_construction_title" class="form-control" value="{{ old('under_construction_title', $allSettings['under_construction_title']->value ?? 'We Are Upgrading Our Digital Experience') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Under Construction Message</label>
                            <textarea name="under_construction_message" class="form-control" rows="3">{{ old('under_construction_message', $allSettings['under_construction_message']->value ?? 'We are currently preparing our new digital catalog, technical specifications, and online portal. Our factory, support team, and dealer network remain fully operational.') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Support Phone</label>
                                <input type="text" name="under_construction_phone" class="form-control" value="{{ old('under_construction_phone', $allSettings['under_construction_phone']->value ?? ($allSettings['phone_primary']->value ?? '1800-212-9000')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">WhatsApp Number</label>
                                <input type="text" name="under_construction_whatsapp" class="form-control" value="{{ old('under_construction_whatsapp', $allSettings['under_construction_whatsapp']->value ?? ($allSettings['whatsapp_number']->value ?? '+919254016570')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Support Email</label>
                                <input type="email" name="under_construction_email" class="form-control" value="{{ old('under_construction_email', $allSettings['under_construction_email']->value ?? ($allSettings['email_primary']->value ?? 'sales@chauhanagroindustries.com')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div class="tab-pane fade" id="tab-contact">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-telephone me-2"></i>Contact Numbers & Email Desks</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Toll-Free / Primary Phone</label>
                                <input type="text" name="phone_primary" class="form-control" value="{{ old('phone_primary', $allSettings['phone_primary']->value ?? '1800-212-9000') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Secondary / Sales Phone</label>
                                <input type="text" name="phone_secondary" class="form-control" value="{{ old('phone_secondary', $allSettings['phone_secondary']->value ?? '+91-9254016570') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">WhatsApp Number (with Country Code)</label>
                                <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $allSettings['whatsapp_number']->value ?? '+919254016570') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primary Sales Email</label>
                                <input type="email" name="email_primary" class="form-control" value="{{ old('email_primary', $allSettings['email_primary']->value ?? 'sales@chauhanagroindustries.com') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Export / International Desk Email</label>
                                <input type="email" name="email_export" class="form-control" value="{{ old('email_export', $allSettings['email_export']->value ?? 'export@chauhanagroindustries.com') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Working Hours</label>
                                <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $allSettings['working_hours']->value ?? 'Mon - Sat: 9:00 AM - 7:00 PM IST') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map & Address Tab -->
                <div class="tab-pane fade" id="tab-map">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-geo-alt me-2"></i>Manufacturing Plant Address & Google Map</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Factory / Plant Full Address</label>
                            <textarea name="factory_address" class="form-control" rows="3">{{ old('factory_address', $allSettings['factory_address']->value ?? 'Chauhan Agro Industries, Industrial Area, GT Road, Karnal, Haryana - 132001, India') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Google Maps Embed URL / Iframe src</label>
                            <input type="text" name="google_map_embed" class="form-control" value="{{ old('google_map_embed', $allSettings['google_map_embed']->value ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d111000!2d76.9897!3d29.6857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390e703998f45a75%3A0xe5f9be282f183ec9!2sKarnal%2C%20Haryana!5e0!3m2!1sen!2sin!4v1698000000000!5m2!1sen!2sin') }}">
                            <div class="form-text">Paste the <code>src="..."</code> attribute from Google Maps Share -> Embed Map.</div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Tab -->
                <div class="tab-pane fade" id="tab-social">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-share me-2"></i>Official Social Media Profiles</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-youtube text-danger me-1"></i> YouTube Channel</label>
                            <input type="url" name="social_youtube" class="form-control" value="{{ old('social_youtube', $allSettings['social_youtube']->value ?? 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-facebook text-primary me-1"></i> Facebook Page</label>
                            <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', $allSettings['social_facebook']->value ?? 'https://www.facebook.com/share/1GpUiRYvWb/') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-instagram text-danger me-1"></i> Instagram Profile</label>
                            <input type="url" name="social_instagram" class="form-control" value="{{ old('social_instagram', $allSettings['social_instagram']->value ?? 'https://www.instagram.com/chauhangroup_india?utm_source=qr&exln=dnZxdXdkYjc1Z2Vs') }}">
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div class="tab-pane fade" id="tab-seo">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-search me-2"></i>Global SEO & Search Indexing Defaults</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Default SEO Meta Title</label>
                            <input type="text" name="default_meta_title" class="form-control" value="{{ old('default_meta_title', $allSettings['default_meta_title']->value ?? 'Chauhan Agro Industries | Heavy-Duty Agricultural Machinery, Rotary Tillers & Implements') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Default SEO Meta Description</label>
                            <textarea name="default_meta_description" class="form-control" rows="3">{{ old('default_meta_description', $allSettings['default_meta_description']->value ?? 'Discover industry-leading agricultural machinery from Chauhan Agro Industries (chauhanagroindustries.com): Multi-Speed Rotary Tillers, Heavy-Duty Disc Harrows, Laser Land Levellers, Seed Drills, and MB Ploughs engineered for maximum farm productivity.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Visual Effects & Animations Tab -->
                <div class="tab-pane fade" id="tab-animations">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <input type="hidden" name="animation_settings_present" value="1">

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-magic me-2 text-primary"></i>Visual Effects, Transitions & Motion Control</h5>
                                <p class="text-muted small mb-0">Control scroll entrance transitions, card hover dynamics, speed, and cascading effects across the website.</p>
                            </div>
                            <div class="form-check form-switch fs-4 mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="animations_enabled" name="animations_enabled" value="1" {{ old('animations_enabled', site_setting('animations_enabled', '1')) === '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- Global Transition Effect -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Primary Scroll Transition Effect</label>
                                @php $currentEffect = old('animation_effect', site_setting('animation_effect', 'fade-up')); @endphp
                                <select name="animation_effect" id="animEffectSelector" class="form-select form-select-lg" onchange="triggerTestAnimation()">
                                    <option value="fade-up" {{ $currentEffect === 'fade-up' ? 'selected' : '' }}>Smooth Glide Up (Fade-Up)</option>
                                    <option value="scale-up" {{ $currentEffect === 'scale-up' ? 'selected' : '' }}>Fluid Zoom / Scale In</option>
                                    <option value="slide-left" {{ $currentEffect === 'slide-left' ? 'selected' : '' }}>Dynamic Slide from Left</option>
                                    <option value="slide-right" {{ $currentEffect === 'slide-right' ? 'selected' : '' }}>Dynamic Slide from Right</option>
                                    <option value="flip-up" {{ $currentEffect === 'flip-up' ? 'selected' : '' }}>Modern 3D Tilt / Flip Up</option>
                                    <option value="bounce-soft" {{ $currentEffect === 'bounce-soft' ? 'selected' : '' }}>Soft Elastic Bounce & Pop</option>
                                    <option value="float-glow" {{ $currentEffect === 'float-glow' ? 'selected' : '' }}>Ambient Floating Glow</option>
                                </select>
                                <div class="form-text">Choose the primary motion personality applied to sections and cards as visitors scroll.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Motion Speed / Duration</label>
                                @php $currentDuration = old('animation_duration', site_setting('animation_duration', 'normal')); @endphp
                                <select name="animation_duration" id="animDurationSelector" class="form-select form-select-lg">
                                    <option value="fast" {{ $currentDuration === 'fast' ? 'selected' : '' }}>Fast & Snappy (0.4s) — Instant feedback</option>
                                    <option value="normal" {{ $currentDuration === 'normal' ? 'selected' : '' }}>Smooth Standard (0.75s) — Recommended</option>
                                    <option value="relaxed" {{ $currentDuration === 'relaxed' ? 'selected' : '' }}>Cinematic & Gentle (1.1s) — Ultra smooth</option>
                                </select>
                                <div class="form-text">Controls how quickly elements transition into full visibility.</div>
                            </div>
                        </div>

                        <!-- Card Hover Dynamics & Stagger -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Card Hover & Micro-Interactions</label>
                                @php $currentCardHover = old('card_hover_effect', site_setting('card_hover_effect', 'lift-float')); @endphp
                                <select name="card_hover_effect" id="cardHoverSelector" class="form-select">
                                    <option value="lift-float" {{ $currentCardHover === 'lift-float' ? 'selected' : '' }}>Modern Elevation Lift & Soft Deep Shadow</option>
                                    <option value="glow-border" {{ $currentCardHover === 'glow-border' ? 'selected' : '' }}>Emerald Glow Accent & Top Line</option>
                                    <option value="subtle-scale" {{ $currentCardHover === 'subtle-scale' ? 'selected' : '' }}>Gentle Zoom Scale (1.025x)</option>
                                    <option value="none" {{ $currentCardHover === 'none' ? 'selected' : '' }}>Static / No Hover Transformation</option>
                                </select>
                                <div class="form-text">Visual response when hovering on feature cards, machinery models, or categories.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Child Element Staggering</label>
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="animation_stagger_enabled" name="animation_stagger_enabled" value="1" {{ old('animation_stagger_enabled', site_setting('animation_stagger_enabled', '1')) === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="animation_stagger_enabled">Enable Cascading Stagger</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Cards in a grid glide in sequentially one after another instead of all at once.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Panel Motion Setting -->
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">Admin Panel Transitions</div>
                                    <small class="text-muted">Enable smooth page fade-in and stat-card elevation inside this admin panel.</small>
                                </div>
                                <div class="form-check form-switch fs-5 mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="admin_animations_enabled" name="admin_animations_enabled" value="1" {{ old('admin_animations_enabled', site_setting('admin_animations_enabled', '1')) === '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <!-- Section-Wise Animation Manager Matrix -->
                        <div class="card border mb-4 shadow-xs">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        <i class="bi bi-sliders2-vertical text-success me-2"></i>Section-Wise Animation Manager
                                    </h6>
                                    <small class="text-muted">Assign customized transitions to individual sections on the website.</small>
                                </div>
                                @if(isset($homePage))
                                    <a href="{{ route('admin.pages.sections.index', $homePage) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                        <i class="bi bi-layers me-1"></i> Open Section Builder
                                    </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 70px;">Order</th>
                                            <th>Section Type</th>
                                            <th>Headline</th>
                                            <th style="min-width: 200px;">Transition Effect</th>
                                            <th style="min-width: 160px;">Speed / Duration</th>
                                            <th class="text-end" style="width: 80px;">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($homeSections as $sec)
                                            @php
                                                $secAnim = $sec->settings_json['animation_effect'] ?? '';
                                                $secSpeed = $sec->settings_json['animation_duration'] ?? '';
                                            @endphp
                                            <tr>
                                                <td class="fw-bold text-muted text-center">#{{ $sec->order }}</td>
                                                <td>
                                                    <span class="badge bg-dark bg-opacity-10 text-dark font-monospace fw-semibold px-2 py-1">
                                                        {{ $sec->type }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">
                                                        {{ $sec->title ?: ucfirst(str_replace('_', ' ', $sec->type)) }}
                                                    </div>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                                        {{ $sec->subtitle ?: 'Standard Layout' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <select name="section_animations[{{ $sec->id }}][effect]" class="form-select form-select-sm">
                                                        <option value="" {{ empty($secAnim) ? 'selected' : '' }}>Global Default ({{ ucfirst(site_setting('animation_effect', 'fade-up')) }})</option>
                                                        <option value="fade-up" {{ $secAnim === 'fade-up' ? 'selected' : '' }}>Smooth Glide Up (Fade-Up)</option>
                                                        <option value="scale-up" {{ $secAnim === 'scale-up' ? 'selected' : '' }}>Fluid Zoom / Scale In</option>
                                                        <option value="slide-left" {{ $secAnim === 'slide-left' ? 'selected' : '' }}>Slide In From Left</option>
                                                        <option value="slide-right" {{ $secAnim === 'slide-right' ? 'selected' : '' }}>Slide In From Right</option>
                                                        <option value="flip-up" {{ $secAnim === 'flip-up' ? 'selected' : '' }}>Modern 3D Tilt / Flip Up</option>
                                                        <option value="bounce-soft" {{ $secAnim === 'bounce-soft' ? 'selected' : '' }}>Soft Elastic Bounce</option>
                                                        <option value="float-glow" {{ $secAnim === 'float-glow' ? 'selected' : '' }}>Ambient Floating Glow</option>
                                                        <option value="none" {{ $secAnim === 'none' ? 'selected' : '' }}>None (Instant / Static)</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="section_animations[{{ $sec->id }}][duration]" class="form-select form-select-sm">
                                                        <option value="" {{ empty($secSpeed) ? 'selected' : '' }}>Global Default ({{ ucfirst(site_setting('animation_duration', 'normal')) }})</option>
                                                        <option value="fast" {{ $secSpeed === 'fast' ? 'selected' : '' }}>Fast (0.4s)</option>
                                                        <option value="normal" {{ $secSpeed === 'normal' ? 'selected' : '' }}>Normal (0.75s)</option>
                                                        <option value="relaxed" {{ $secSpeed === 'relaxed' ? 'selected' : '' }}>Relaxed (1.1s)</option>
                                                    </select>
                                                </td>
                                                <td class="text-end">
                                                    @if(isset($homePage))
                                                        <a href="{{ route('admin.pages.sections.edit', [$homePage, $sec]) }}" class="btn btn-sm btn-light border py-1 px-2" title="Edit Full Section" target="_blank">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-3 text-muted">No sections found on the Home page.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Live Real-Time Interactive Playground -->
                        <div class="p-4 rounded-4 border bg-white shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-play-circle-fill text-success me-2"></i>Live Effect Playground</h6>
                                    <small class="text-muted">Test the selected transition effect in real-time right now.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-3 rounded-pill" onclick="triggerTestAnimation()">
                                    <i class="bi bi-arrow-repeat me-1"></i> Play Animation
                                </button>
                            </div>

                            <div class="row g-3" id="previewContainer">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border bg-light text-center preview-card">
                                        <i class="bi bi-tractor fs-2 text-success mb-2 d-block"></i>
                                        <div class="fw-bold small">Rotary Tillers</div>
                                        <small class="text-muted">Sample Card 1</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border bg-light text-center preview-card">
                                        <i class="bi bi-cpu fs-2 text-warning mb-2 d-block"></i>
                                        <div class="fw-bold small">Laser Leveller</div>
                                        <small class="text-muted">Sample Card 2</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border bg-light text-center preview-card">
                                        <i class="bi bi-shield-check fs-2 text-primary mb-2 d-block"></i>
                                        <div class="fw-bold small">Heavy Implements</div>
                                        <small class="text-muted">Sample Card 3</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-agro-primary btn-lg px-5 shadow-sm">
                    <i class="bi bi-save2 me-2"></i> Save All Settings
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function copyClientLink(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            alert('Client preview link copied to clipboard:\n\n' + url + '\n\nYou can send this link to your client via WhatsApp or Email.');
        });
    } else {
        const input = document.getElementById('clientShareInput');
        if (input) {
            input.select();
            document.execCommand('copy');
            alert('Client preview link copied to clipboard!');
        }
    }
}

function triggerTestAnimation() {
    const effect = document.getElementById('animEffectSelector').value;
    const cards = document.querySelectorAll('.preview-card');
    cards.forEach((card, i) => {
        card.style.animation = 'none';
        card.offsetHeight; // trigger reflow
        card.style.animation = `animPreview_${effect} 0.6s cubic-bezier(0.16, 1, 0.3, 1) ${i * 0.1}s both`;
    });
}
</script>

<style>
@keyframes animPreview_fade-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes animPreview_scale-up {
    from { opacity: 0; transform: scale(0.85) translateY(20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
@keyframes animPreview_slide-left {
    from { opacity: 0; transform: translateX(-40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes animPreview_slide-right {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes animPreview_flip-up {
    from { opacity: 0; transform: perspective(800px) rotateX(30deg) translateY(25px); }
    to { opacity: 1; transform: perspective(800px) rotateX(0deg) translateY(0); }
}
@keyframes animPreview_bounce-soft {
    0% { opacity: 0; transform: translateY(35px) scale(0.9); }
    60% { opacity: 1; transform: translateY(-6px) scale(1.02); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes animPreview_float-glow {
    0% { opacity: 0; transform: translateY(20px); box-shadow: 0 0 20px rgba(4, 120, 87, 0.5); }
    100% { opacity: 1; transform: translateY(0); box-shadow: none; }
}
</style>
@endsection
