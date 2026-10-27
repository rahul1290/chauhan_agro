<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border p-4 shadow-sm text-center rounded-4">
                    <div class="mb-3 text-success fs-1"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5 class="fw-bold mb-2">{{ $currentLocale === 'hi' ? 'मुख्यालय पता' : 'Plant & Headquarters' }}</h5>
                    <p class="text-muted small">{{ site_setting('headquarters_address') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border p-4 shadow-sm text-center rounded-4">
                    <div class="mb-3 text-warning fs-1"><i class="bi bi-telephone-inbound-fill"></i></div>
                    <h5 class="fw-bold mb-2">{{ $currentLocale === 'hi' ? 'फोन एवं टोल-फ्री' : 'Phone & Toll-Free' }}</h5>
                    <p class="text-muted small mb-1">{{ site_setting('toll_free_number') }}</p>
                    <p class="text-muted small">{{ site_setting('primary_phone') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border p-4 shadow-sm text-center rounded-4">
                    <div class="mb-3 text-primary fs-1"><i class="bi bi-envelope-at-fill"></i></div>
                    <h5 class="fw-bold mb-2">{{ $currentLocale === 'hi' ? 'ईमेल संपर्क' : 'Email Enquiries' }}</h5>
                    <p class="text-muted small mb-1"><a href="mailto:{{ site_setting('sales_email') }}">{{ site_setting('sales_email') }}</a></p>
                    <p class="text-muted small"><a href="mailto:{{ site_setting('contact_email') }}">{{ site_setting('contact_email') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
