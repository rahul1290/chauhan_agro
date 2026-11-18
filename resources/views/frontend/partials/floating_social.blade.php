@php
    $floatingEnabled = site_setting('floating_social_enabled', '1') === '1';
    
    $showWhatsapp = site_setting('floating_whatsapp_enabled', '1') === '1';
    $showFacebook = site_setting('floating_facebook_enabled', '1') === '1';
    $showInstagram = site_setting('floating_instagram_enabled', '1') === '1';
    $showYoutube = site_setting('floating_youtube_enabled', '1') === '1';
    $showPhone = site_setting('floating_phone_enabled', '1') === '1';
    $showLinkedin = site_setting('floating_linkedin_enabled', '0') === '1';
    $showTwitter = site_setting('floating_twitter_enabled', '0') === '1';

    $whatsappNum = site_setting('whatsapp_number', '+91 98765 43210');
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNum);

    $facebookUrl = site_setting('social_facebook', 'https://www.facebook.com/share/1GpUiRYvWb/');
    $instagramUrl = site_setting('social_instagram', 'https://www.instagram.com/chauhangroup_india?utm_source=qr&exln=dnZxdXdkYjc1Z2Vs');
    $youtubeUrl = site_setting('social_youtube', 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc');
    
    $phoneNum = site_setting('toll_free_number', site_setting('primary_phone', '1800-212-9002'));
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNum);

    $linkedinUrl = site_setting('social_linkedin', '');
    $twitterUrl = site_setting('social_twitter', '');
@endphp

@if($floatingEnabled)
    <!-- Sticky Social Media Bar (Right Middle) with Genuine Brand Colors -->
    <div class="sticky-social-bar" id="stickySocialBar" aria-label="Social Media and Instant Contact Channels">
        {{-- WhatsApp --}}
        @if($showWhatsapp && !empty($cleanWhatsapp))
            <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode('Hello Chauhan Agro Industries, I would like to inquire about your agricultural machinery & products.') }}"
               class="sticky-social-item social-whatsapp"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ $currentLocale === 'hi' ? 'व्हाट्सएप पर चैट करें' : 'Chat on WhatsApp' }}">
                <span class="social-label-text">{{ $currentLocale === 'hi' ? 'व्हाट्सएप' : 'WhatsApp' }}</span>
                <span class="social-icon-wrapper"><i class="bi bi-whatsapp"></i></span>
            </a>
        @endif

        {{-- Facebook --}}
        @if($showFacebook && !empty($facebookUrl))
            <a href="{{ $facebookUrl }}"
               class="sticky-social-item social-facebook"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ $currentLocale === 'hi' ? 'फेसबुक पेज' : 'Facebook Page' }}">
                <span class="social-label-text">{{ $currentLocale === 'hi' ? 'फेसबुक' : 'Facebook' }}</span>
                <span class="social-icon-wrapper"><i class="bi bi-facebook"></i></span>
            </a>
        @endif

        {{-- Instagram --}}
        @if($showInstagram && !empty($instagramUrl))
            <a href="{{ $instagramUrl }}"
               class="sticky-social-item social-instagram"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ $currentLocale === 'hi' ? 'इंस्टाग्राम प्रोफाइल' : 'Instagram Profile' }}">
                <span class="social-label-text">{{ $currentLocale === 'hi' ? 'इंस्टाग्राम' : 'Instagram' }}</span>
                <span class="social-icon-wrapper"><i class="bi bi-instagram"></i></span>
            </a>
        @endif

        {{-- YouTube --}}
        @if($showYoutube && !empty($youtubeUrl))
            <a href="{{ $youtubeUrl }}"
               class="sticky-social-item social-youtube"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ $currentLocale === 'hi' ? 'यूट्यूब चैनल' : 'YouTube Channel' }}">
                <span class="social-label-text">{{ $currentLocale === 'hi' ? 'यूट्यूब' : 'YouTube' }}</span>
                <span class="social-icon-wrapper"><i class="bi bi-youtube"></i></span>
            </a>
        @endif

        {{-- Phone / Call / Toll-Free --}}
        @if($showPhone && !empty($cleanPhone))
            <a href="tel:{{ $cleanPhone }}"
               class="sticky-social-item social-phone"
               title="{{ $currentLocale === 'hi' ? 'कॉल करें: ' . $phoneNum : 'Call Us: ' . $phoneNum }}">
                <span class="social-label-text">{{ $currentLocale === 'hi' ? 'कॉल करें' : 'Call Toll-Free' }}</span>
                <span class="social-icon-wrapper"><i class="bi bi-telephone-fill"></i></span>
            </a>
        @endif

        {{-- LinkedIn --}}
        @if($showLinkedin && !empty($linkedinUrl))
            <a href="{{ $linkedinUrl }}"
               class="sticky-social-item social-linkedin"
               target="_blank"
               rel="noopener noreferrer"
               title="LinkedIn">
                <span class="social-label-text">LinkedIn</span>
                <span class="social-icon-wrapper"><i class="bi bi-linkedin"></i></span>
            </a>
        @endif

        {{-- Twitter / X --}}
        @if($showTwitter && !empty($twitterUrl))
            <a href="{{ $twitterUrl }}"
               class="sticky-social-item social-twitter"
               target="_blank"
               rel="noopener noreferrer"
               title="Twitter (X)">
                <span class="social-label-text">X (Twitter)</span>
                <span class="social-icon-wrapper"><i class="bi bi-twitter-x"></i></span>
            </a>
        @endif
    </div>
@endif
