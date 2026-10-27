@extends('frontend.layouts.app')

@section('meta_title', ($currentLocale === 'hi' ? 'संपर्क करें | चौहान एग्रो इंडस्ट्रीज' : 'Contact Us | Chauhan Agro Industries'))
@section('meta_description', 'Contact Chauhan Agro Industries (chauhanagroindustries.com) factory headquarters, sales advisors, or dealer relations. Phone, email, Google Maps directions, and online enquiry form.')

@section('content')

    <!-- Header Banner -->
    <section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-bottom: 4px solid var(--agro-secondary);">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ loc_url('/') }}" class="text-light-muted">{{ $currentLocale === 'hi' ? 'होम' : 'Home' }}</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $currentLocale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}</li>
                </ol>
            </nav>
            <h1 class="text-white fw-bold display-5 mb-2">{{ $currentLocale === 'hi' ? 'हमसे संपर्क करें' : 'Contact Chauhan Agro Industries' }}</h1>
            <p class="text-light-muted mb-0" style="max-width: 700px; font-size: 1.15rem;">
                {{ $currentLocale === 'hi' ? 'मशीनरी पूछताछ, फैक्टरी कोटेशन या डीलरशिप आवेदन के लिए हमारी समर्पित टीम से संपर्क करें।' : 'Reach out to our engineering specialists, authorized dealership directorate, or regional technical support.' }}
            </p>
        </div>
    </section>

    <!-- Contact Info Cards & Map -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 rounded-4 border shadow-sm h-100 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white p-3 rounded-3 shadow-sm text-success fs-3"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $currentLocale === 'hi' ? 'मुख्यालय एवं संयंत्र' : 'Factory & Plant' }}</h5>
                                <small class="text-muted">Industrial Complex</small>
                            </div>
                        </div>
                        <p class="text-secondary small mb-0" style="line-height: 1.7;">
                            {{ site_setting('headquarters_address', 'Chauhan Agro Industries Complex, NH-8 Highway Corridor, Phase II, Precision Engineering Zone, India') }}
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="p-4 rounded-4 border shadow-sm h-100 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white p-3 rounded-3 shadow-sm text-warning fs-3"><i class="bi bi-telephone-inbound-fill"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $currentLocale === 'hi' ? 'फोन एवं टोल-फ्री' : 'Phone & Toll-Free' }}</h5>
                                <small class="text-muted">Mon - Sat: 9 AM - 6:30 PM</small>
                            </div>
                        </div>
                        <p class="text-secondary small mb-1">
                            <span class="text-muted">Toll-Free:</span> <strong class="text-dark">{{ site_setting('toll_free_number', '1800-212-9000') }}</strong>
                        </p>
                        <p class="text-secondary small mb-0">
                            <span class="text-muted">Sales Office:</span> <strong class="text-dark">{{ site_setting('primary_phone', '+91 98765 43210') }}</strong>
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="p-4 rounded-4 border shadow-sm h-100 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white p-3 rounded-3 shadow-sm text-primary fs-3"><i class="bi bi-envelope-at-fill"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $currentLocale === 'hi' ? 'ईमेल पूछताछ' : 'Email Desks' }}</h5>
                                <small class="text-muted">Quick 24h Response</small>
                            </div>
                        </div>
                        <p class="text-secondary small mb-1">
                            <span class="text-muted">Sales:</span> <a href="mailto:{{ site_setting('sales_email') }}">{{ site_setting('sales_email') }}</a>
                        </p>
                        <p class="text-secondary small mb-0">
                            <span class="text-muted">Support:</span> <a href="mailto:{{ site_setting('contact_email') }}">{{ site_setting('contact_email') }}</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                <!-- Contact & Quotation Form -->
                <div class="col-lg-7">
                    <div class="p-4 p-md-5 rounded-4 border shadow-sm bg-white">
                        <span class="section-badge">{{ $currentLocale === 'hi' ? 'ऑनलाइन संदेश' : 'DIRECT ENQUIRY' }}</span>
                        <h3 class="fw-bold text-dark mb-2">{{ $currentLocale === 'hi' ? 'पूछताछ या कोटेशन फॉर्म भरें' : 'Send An Official Inquiry' }}</h3>
                        <p class="text-muted small mb-4">{{ $currentLocale === 'hi' ? 'सभी आवश्यक फ़ील्ड भरें, हमारी तकनीकी टीम 24 घंटे के भीतर संपर्क करेगी।' : 'Fill out the details below. Our technical sales representative will respond within 24 business hours.' }}</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit', ['locale' => $currentLocale]) }}" method="POST">
                            @csrf
                            <!-- Honeypot -->
                            <div style="display: none;">
                                <input type="text" name="website_url" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'पूरा नाम' : 'Full Name' }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="e.g. Jaswinder Singh">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'ईमेल पता' : 'Email Address' }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="name@domain.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'मोबाइल नंबर' : 'Phone / Mobile' }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" required placeholder="+91 98765 00000">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'कंपनी / फार्म का नाम' : 'Farm / Business Entity' }}</label>
                                    <input type="text" name="company" value="{{ old('company') }}" class="form-control" placeholder="e.g. Singh Agri Enterprises">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'देश' : 'Country' }}</label>
                                    <input type="text" name="country" value="{{ old('country', 'India') }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'शहर / राज्य' : 'City / State' }}</label>
                                    <input type="text" name="city" value="{{ old('city') }}" class="form-control" placeholder="e.g. Ludhiana, Punjab">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'उपकरण चुनें (वैकल्पिक)' : 'Select Machinery Model (Optional)' }}</label>
                                    <select name="product_id" class="form-select">
                                        <option value="">{{ $currentLocale === 'hi' ? '-- सामान्य पूछताछ / डीलरशिप --' : '-- General Inquiry / Dealership Application --' }}</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} ({{ $p->hp_range ?? 'All HP' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'विषय' : 'Subject' }} <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" required placeholder="e.g. Quotation for Multi-Speed Rotavators">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">{{ $currentLocale === 'hi' ? 'विस्तृत संदेश / आवश्यकताएं' : 'Detailed Requirements / Tractor HP' }} <span class="text-danger">*</span></label>
                                    <textarea name="message" rows="4" class="form-control" required placeholder="{{ $currentLocale === 'hi' ? 'अपने ट्रैक्टर का विवरण, खेत की मिट्टी का प्रकार और आवश्यकता लिखें...' : 'Please specify tractor HP, target soil type, order quantity, and delivery destination...' }}">{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-agro-primary btn-lg w-100 py-3">
                                    <i class="bi bi-send-fill me-2"></i>
                                    {{ $currentLocale === 'hi' ? 'संदेश एवं पूछताछ भेजें' : 'Submit Official Inquiry' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Google Maps Embed & Plant Snapshot -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white p-3 fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-map-fill text-warning"></i>
                            <span>{{ $currentLocale === 'hi' ? 'संयंत्र स्थान एवं दिशा-निर्देश' : 'Plant Location & Coordinates' }}</span>
                        </div>
                        <div class="ratio ratio-16x9" style="min-height: 380px;">
                            <iframe src="{{ site_setting('google_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117506.97341393664!2d72.49397669466551!3d23.020497771783387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin') }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <div class="p-4 bg-light rounded-4 border">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-clock-fill text-success me-2"></i>{{ $currentLocale === 'hi' ? 'कार्य समय' : 'Operating Hours' }}</h6>
                        <p class="text-secondary small mb-3">{{ site_setting('working_hours', 'Monday - Saturday: 9:00 AM - 6:30 PM (IST)') }}</p>
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-globe2 text-primary me-2"></i>{{ $currentLocale === 'hi' ? 'निर्यात पूछताछ' : 'International Export Inquiries' }}</h6>
                        <p class="text-secondary small mb-0">For overseas dealership representation, OEM manufacturing, or export container inquiries, please email: <strong class="text-dark">export@chauhanagroindustries.com</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
