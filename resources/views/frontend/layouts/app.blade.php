<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('meta_title', site_setting('meta_title_default', 'Chauhan Agro Industries | Heavy-Duty Agricultural Machinery & Implements'))</title>
    <meta name="description" content="@yield('meta_description', site_setting('meta_description_default', 'High-performance agricultural machinery: Rotary Tillers, Disc Harrows, Laser Land Levellers, Seed Drills and MB Ploughs.'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Canonical and Hreflang SEO -->
    <link rel="canonical" href="{{ url()->current() }}">
    @if(isset($activeLanguages))
        @foreach($activeLanguages as $lang)
            <link rel="alternate" hreflang="{{ $lang->code }}" href="{{ loc_switch($lang->code) }}">
        @endforeach
    @endif

    <!-- Open Graph Meta -->
    <meta property="og:title" content="@yield('meta_title', site_setting('meta_title_default'))">
    <meta property="og:description" content="@yield('meta_description', site_setting('meta_description_default'))">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/branding/logo.svg'))">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/branding/logo.svg') }}">

    <!-- CSS Framework: Bootstrap 5 & Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-icons.min.css') }}">

    <!-- Custom Industrial Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>
<body>

    <!-- ==============================================
         Top Contact & Language Bar
         ============================================== -->
    <div class="agro-topbar py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4">
                <span class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>{{ site_setting('toll_free_number', '1800-212-9000') }}</span>
                </span>
                <span class="contact-item d-none d-md-inline-flex">
                    <i class="bi bi-envelope-fill"></i>
                    <a href="mailto:{{ site_setting('sales_email', 'sales@chauhanagroindustries.com') }}">{{ site_setting('sales_email', 'sales@chauhanagroindustries.com') }}</a>
                </span>
                <span class="contact-item d-none d-lg-inline-flex">
                    <i class="bi bi-shield-check"></i>
                    <span>ISO 9001:2015 & CE Certified</span>
                </span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Language Switcher Dropdown -->
                <div class="dropdown">
                    <button class="btn lang-selector-btn dropdown-toggle" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ $currentLanguage->flag ?? ($currentLocale === 'hi' ? '🇮🇳' : '🇬🇧') }}</span>
                        <span class="fw-bold">{{ $currentLanguage->native_name ?? ($currentLocale === 'hi' ? 'हिन्दी' : 'English') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="langDropdown">
                        @php
                            $languagesToDisplay = (isset($activeLanguages) && $activeLanguages->isNotEmpty()) ? $activeLanguages : null;
                        @endphp
                        @if($languagesToDisplay)
                            @foreach($languagesToDisplay as $lang)
                                <li>
                                    <a class="dropdown-item {{ $lang->code === $currentLocale ? 'active' : '' }}" href="{{ loc_switch($lang->code) }}">
                                        <span class="fs-6">{{ $lang->flag }}</span>
                                        <span class="flex-grow-1">{{ $lang->native_name }} ({{ $lang->name }})</span>
                                        @if($lang->code === $currentLocale)
                                            <i class="bi bi-check2"></i>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}" href="{{ loc_switch('en') }}">
                                    <span class="fs-6">🇬🇧</span>
                                    <span class="flex-grow-1">English</span>
                                    @if($currentLocale === 'en')
                                        <i class="bi bi-check2"></i>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $currentLocale === 'hi' ? 'active' : '' }}" href="{{ loc_switch('hi') }}">
                                    <span class="fs-6">🇮🇳</span>
                                    <span class="flex-grow-1">हिन्दी (Hindi)</span>
                                    @if($currentLocale === 'hi')
                                        <i class="bi bi-check2"></i>
                                    @endif
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <a href="{{ loc_url('/dealer-network') }}" class="btn btn-sm btn-outline-light d-none d-sm-inline-block rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                    <i class="bi bi-geo-alt-fill me-1 text-warning"></i>
                    {{ $currentLocale === 'hi' ? 'डीलर खोजें' : 'Locate Dealer' }}
                </a>
            </div>
        </div>
    </div>

    <!-- ==============================================
         Main Navigation Header
         ============================================== -->
    <header class="sticky-top agro-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <a class="navbar-brand" href="{{ loc_url('/') }}">
                    <img src="{{ asset('assets/images/branding/logo.svg') }}" alt="Chauhan Agro Industries" height="48">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#agroNavbarContent" aria-controls="agroNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="agroNavbarContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        @if(isset($headerMenu) && $headerMenu->items->isNotEmpty())
                            @foreach($headerMenu->items as $item)
                                @if($item->children->isNotEmpty())
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="menuItem{{ $item->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ $item->title }}
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="menuItem{{ $item->id }}">
                                            @foreach($item->children as $child)
                                                <li>
                                                    <a class="dropdown-item" href="{{ $child->url }}">
                                                        {{ $child->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->url() === $item->url ? 'active' : '' }}" href="{{ $item->url }}">
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ loc_url('/') }}">{{ $currentLocale === 'hi' ? 'होम' : 'Home' }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ loc_url('/about-us') }}">{{ $currentLocale === 'hi' ? 'हमारे बारे में' : 'About Us' }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ loc_url('/products') }}">{{ $currentLocale === 'hi' ? 'कृषि मशीनरी' : 'Machinery' }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ loc_url('/dealer-network') }}">{{ $currentLocale === 'hi' ? 'डीलर नेटवर्क' : 'Dealer Network' }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ loc_url('/contact') }}">{{ $currentLocale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}</a></li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-agro-secondary" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="bi bi-chat-quote-fill me-1"></i>
                            {{ $currentLocale === 'hi' ? 'कोटेशन प्राप्त करें' : 'Request Quote' }}
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Global Alert Notifications -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm border-0" role="alert" style="background-color: #ecfdf5; color: #065f46; border-left: 5px solid #059669 !important;">
                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm border-0" role="alert" style="background-color: #fef2f2; color: #991b1b; border-left: 5px solid #dc2626 !important;">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content Yield -->
    <main>
        @yield('content')
    </main>

    <!-- ==============================================
         Global Corporate Footer
         ============================================== -->
    <footer class="agro-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Column 1: Company Profile -->
                <div class="col-lg-4 col-md-6">
                    <img src="{{ asset('assets/images/branding/logo-white.svg') }}" alt="Chauhan Agro Industries" height="42" class="mb-4">
                    <p class="text-light-muted mb-4 pe-lg-3" style="font-size: 0.92rem; line-height: 1.7;">
                        {{ site_setting('footer_about_text', 'Chauhan Agro Industries is a premier global manufacturer of advanced agricultural implements engineered with robotic precision, laser alignment, and genuine European boron steel.') }}
                    </p>
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ site_setting('social_facebook', '#') }}" class="footer-social-link" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="{{ site_setting('social_youtube', '#') }}" class="footer-social-link" title="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="{{ site_setting('social_linkedin', '#') }}" class="footer-social-link" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="{{ site_setting('social_instagram', '#') }}" class="footer-social-link" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="{{ site_setting('social_twitter', '#') }}" class="footer-social-link" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h4>{{ $currentLocale === 'hi' ? 'त्वरित लिंक' : 'Quick Links' }}</h4>
                    <ul>
                        @if(isset($footerQuickMenu) && $footerQuickMenu->items->isNotEmpty())
                            @foreach($footerQuickMenu->items as $item)
                                <li><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ loc_url('/about-us') }}">{{ $currentLocale === 'hi' ? 'कंपनी परिचय' : 'About Us' }}</a></li>
                            <li><a href="{{ loc_url('/products') }}">{{ $currentLocale === 'hi' ? 'सभी उत्पाद' : 'All Machinery' }}</a></li>
                            <li><a href="{{ loc_url('/dealer-network') }}">{{ $currentLocale === 'hi' ? 'डीलर नेटवर्क' : 'Dealer Network' }}</a></li>
                            <li><a href="{{ loc_url('/warranty-policy') }}">{{ $currentLocale === 'hi' ? 'वारंटी नीति' : 'Warranty Policy' }}</a></li>
                            <li><a href="{{ loc_url('/contact') }}">{{ $currentLocale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Column 3: Machinery Lines -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h4>{{ $currentLocale === 'hi' ? 'प्रमुख मशीनरी' : 'Machinery Lines' }}</h4>
                    <ul>
                        @if(isset($footerProductsMenu) && $footerProductsMenu->items->isNotEmpty())
                            @foreach($footerProductsMenu->items as $item)
                                <li><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ loc_url('/products?category=rotary-tillers') }}">{{ $currentLocale === 'hi' ? 'रोटावेटर' : 'Rotary Tillers' }}</a></li>
                            <li><a href="{{ loc_url('/products?category=disc-harrows') }}">{{ $currentLocale === 'hi' ? 'डिस्क हैरो' : 'Disc Harrows' }}</a></li>
                            <li><a href="{{ loc_url('/products?category=laser-land-levellers') }}">{{ $currentLocale === 'hi' ? 'लेजर लैंड लेवलर' : 'Laser Levellers' }}</a></li>
                            <li><a href="{{ loc_url('/products?category=seeding-planting') }}">{{ $currentLocale === 'hi' ? 'सीड ड्रिल' : 'Zero-Till Seed Drills' }}</a></li>
                            <li><a href="{{ loc_url('/products?category=harvest-straw-management') }}">{{ $currentLocale === 'hi' ? 'स्ट्रॉ रीपर' : 'Tractor Straw Reapers' }}</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Column 4: Factory Contact -->
                <div class="col-lg-3 col-md-6">
                    <h4>{{ $currentLocale === 'hi' ? 'मुख्यालय एवं संयंत्र' : 'Factory & Plant' }}</h4>
                    <p style="font-size: 0.9rem; line-height: 1.6;" class="mb-3">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i>
                        {{ site_setting('headquarters_address', 'Chauhan Agro Industries Complex, NH-8 Highway Corridor, Phase II, Precision Engineering Zone, India') }}
                    </p>
                    <p style="font-size: 0.9rem;" class="mb-2">
                        <i class="bi bi-telephone-inbound-fill text-warning me-2"></i>
                        <span class="fw-bold text-white">{{ site_setting('primary_phone', '+91 98765 43210') }}</span>
                    </p>
                    <p style="font-size: 0.9rem;" class="mb-2">
                        <i class="bi bi-envelope-at-fill text-warning me-2"></i>
                        <a href="mailto:{{ site_setting('contact_email', 'contact@chauhanagroindustries.com') }}">{{ site_setting('contact_email', 'contact@chauhanagroindustries.com') }}</a>
                    </p>
                    <p style="font-size: 0.85rem;" class="text-secondary mt-3">
                        <i class="bi bi-clock-history me-1"></i> {{ site_setting('working_hours', 'Mon - Sat: 9:00 AM - 6:30 PM') }}
                    </p>
                </div>
            </div>

            <!-- Bottom Legal Bar -->
            <div class="agro-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    &copy; {{ date('Y') }} {{ site_setting('site_name', 'Chauhan Agro Industries') }}. {{ $currentLocale === 'hi' ? 'सर्वाधिकार सुरक्षित।' : 'All Rights Reserved.' }}
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ loc_url('/privacy-policy') }}" class="text-muted" style="font-size: 0.82rem;">{{ $currentLocale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}</a>
                    <span class="text-secondary">•</span>
                    <a href="{{ loc_url('/terms-conditions') }}" class="text-muted" style="font-size: 0.82rem;">{{ $currentLocale === 'hi' ? 'नियम एवं शर्तें' : 'Terms & Conditions' }}</a>
                    <span class="text-secondary">•</span>
                    <a href="{{ route('admin.login') }}" class="text-muted" style="font-size: 0.82rem;" title="Staff Console"><i class="bi bi-shield-lock me-1"></i>Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==============================================
         Global Quotation & Enquiry Modal
         ============================================== -->
    <div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-dark text-white p-4">
                    <div>
                        <h4 class="modal-title fw-bold text-white mb-1" id="enquiryModalLabel">
                            <i class="bi bi-chat-square-quote-fill text-warning me-2"></i>
                            {{ $currentLocale === 'hi' ? 'कोटेशन / पूछताछ फॉर्म' : 'Request Machinery Quotation' }}
                        </h4>
                        <small class="text-secondary">{{ $currentLocale === 'hi' ? 'फैक्टरी कीमतों और डीलर सहायता के लिए विवरण भरें' : 'Direct factory pricing & authorized dealer support' }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('contact.submit', ['locale' => $currentLocale]) }}" method="POST">
                        @csrf
                        <!-- Honeypot -->
                        <div style="display: none;">
                            <input type="text" name="website_url" autocomplete="off">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'आपका नाम' : 'Your Full Name' }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Gurpreet Singh">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'ईमेल पता' : 'Email Address' }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'मोबाइल नंबर' : 'Phone / Mobile No.' }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" required placeholder="+91 98765 00000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'शहर / जिला' : 'City / District' }}</label>
                                <input type="text" name="city" class="form-control" placeholder="e.g. Karnal, Haryana">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'रुचि का उपकरण' : 'Select Machinery Implement' }}</label>
                                <select name="product_id" class="form-select">
                                    <option value="">{{ $currentLocale === 'hi' ? '-- सामान्य पूछताछ / सभी मॉडल --' : '-- General Inquiry / All Models --' }}</option>
                                    @php
                                        $modalProducts = \App\Models\Product::where('is_active', true)->with('translations')->get();
                                    @endphp
                                    @foreach($modalProducts as $mProd)
                                        <option value="{{ $mProd->id }}">{{ $mProd->name }} ({{ $mProd->hp_range ?? 'All HP' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'पूछताछ का विषय' : 'Subject' }} <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" required placeholder="e.g. Price quotation for 7ft Rotavator">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'संदेश / विवरण' : 'Detailed Requirements / Tractor HP' }} <span class="text-danger">*</span></label>
                                <textarea name="message" rows="3" class="form-control" required placeholder="{{ $currentLocale === 'hi' ? 'अपने ट्रैक्टर का हॉर्सपावर और आवश्यक जानकारी लिखें...' : 'Mention your tractor HP, farm soil type, and location...' }}"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">{{ $currentLocale === 'hi' ? 'रद्द करें' : 'Cancel' }}</button>
                            <button type="submit" class="btn btn-agro-primary px-4">
                                <i class="bi bi-send-fill me-1"></i>
                                {{ $currentLocale === 'hi' ? 'संदेश भेजें' : 'Submit Quotation Request' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts: Bootstrap 5 JS Bundle -->
    <script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
