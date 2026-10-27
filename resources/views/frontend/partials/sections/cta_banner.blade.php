<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="agro-cta-banner">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2>{{ $section->title ?? ($currentLocale === 'hi' ? 'क्या आप कृषि पैदावार बढ़ाने के लिए तैयार हैं?' : 'Ready To Transform Your Agricultural Productivity?') }}</h2>
                    <p>{{ $section->subtitle ?? ($currentLocale === 'hi' ? 'फैक्टरी कोटेशन और डीलर सहायता के लिए अभी संपर्क करें।' : 'Connect with our technical product specialists for customized implement recommendations and competitive factory-direct quotations.') }}</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <button class="btn btn-agro-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                        <i class="bi bi-chat-quote-fill me-1"></i>
                        {{ $section->button_text ?? ($currentLocale === 'hi' ? 'फैक्टरी कोटेशन मांगें' : 'Request Factory Quote') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
