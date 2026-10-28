@extends('admin.layouts.admin')

@section('title', 'Global Website & Business Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Global Website Settings</h1>
        <p class="text-muted mb-0">Configure company branding, contact channels, maps, social media, and default SEO parameters.</p>
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

                        <div class="mb-3">
                            <label class="form-label fw-bold">Footer Copyright Notice</label>
                            <input type="text" name="footer_copyright" class="form-control" value="{{ old('footer_copyright', $allSettings['footer_copyright']->value ?? '© ' . date('Y') . ' Chauhan Agro Industries Pvt. Ltd. All Rights Reserved.') }}">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch pt-2">
                                    <input class="form-check-input" type="checkbox" name="enable_testimonials" value="1" id="enableTestimonialsCheck" {{ ($allSettings['enable_testimonials']->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="enableTestimonialsCheck">Enable Customer Testimonials Section</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch pt-2">
                                    <input class="form-check-input" type="checkbox" name="enable_blog" value="1" id="enableBlogCheck" {{ ($allSettings['enable_blog']->value ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="enableBlogCheck">Enable News & Updates Section</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div class="tab-pane fade" id="tab-contact">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-telephone me-2"></i>Contact & Communication Channels</h5>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sales & Enquiry Email</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $allSettings['contact_email']->value ?? 'sales@chauhanagroindustries.com') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Toll-Free / Customer Support Phone</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $allSettings['contact_phone']->value ?? '1800-212-9000') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">WhatsApp Business Number / Link</label>
                            <input type="text" name="contact_whatsapp" class="form-control" value="{{ old('contact_whatsapp', $allSettings['contact_whatsapp']->value ?? '+919876543210') }}">
                            <div class="form-text">Include country code for direct click-to-chat WhatsApp integration.</div>
                        </div>
                    </div>
                </div>

                <!-- Location & Map Tab -->
                <div class="tab-pane fade" id="tab-map">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-geo-alt me-2"></i>Factory & Corporate Address</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Manufacturing Facility & Head Office Address</label>
                            <textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address', $allSettings['contact_address']->value ?? 'Chauhan Agro Industries Complex, NH-8 Highway Corridor, Phase II, Precision Engineering Zone, Gujarat, India - 382010') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Google Maps Embed URL</label>
                            <textarea name="map_embed_url" class="form-control" rows="3">{{ old('map_embed_url', $allSettings['map_embed_url']->value ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117506.97341393664!2d72.49397669466551!3d23.020497771783387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin') }}</textarea>
                            <div class="form-text">Paste the <code>src</code> URL from Google Maps Embed iframe.</div>
                        </div>
                    </div>
                </div>

                <!-- Social Tab -->
                <div class="tab-pane fade" id="tab-social">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold text-agro-forest mb-3"><i class="bi bi-share me-2"></i>Corporate Social Media Links</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-facebook text-primary me-2"></i>Facebook Page</label>
                            <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', $allSettings['social_facebook']->value ?? 'https://www.facebook.com/share/1GpUiRYvWb/') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-youtube text-danger me-2"></i>YouTube Channel</label>
                            <input type="url" name="social_youtube" class="form-control" value="{{ old('social_youtube', $allSettings['social_youtube']->value ?? 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-instagram text-danger me-2"></i>Instagram Profile</label>
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
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-agro-primary btn-lg px-5 shadow-sm">
                    <i class="bi bi-save2 me-2"></i> Save All Settings
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
