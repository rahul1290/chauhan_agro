@php
    $floatingEnabled = site_setting('floating_social_enabled', '1') === '1';

    $defaultOrder = ['whatsapp', 'facebook', 'instagram', 'youtube', 'phone', 'linkedin', 'twitter'];
    $savedOrderRaw = site_setting('floating_social_order', '[]');
    $savedOrder = json_decode($savedOrderRaw, true);
    if (!is_array($savedOrder) || empty($savedOrder)) {
        $savedOrder = $defaultOrder;
    } else {
        // Append any missing channels to the end
        $savedOrder = array_unique(array_merge($savedOrder, $defaultOrder));
    }

    $whatsappNum = site_setting('whatsapp_number', '+91 98765 43210');
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappNum);

    $facebookUrl = site_setting('social_facebook', 'https://www.facebook.com/share/1GpUiRYvWb/');
    $instagramUrl = site_setting('social_instagram', 'https://www.instagram.com/chauhangroup_india?utm_source=qr&exln=dnZxdXdkYjc1Z2Vs');
    $youtubeUrl = site_setting('social_youtube', 'https://youtube.com/@chauhangroupindia?si=UnQCzy4DrxsMjZdc');
    
    $phoneNum = site_setting('toll_free_number', site_setting('primary_phone', '1800-212-9002'));
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phoneNum);

    $linkedinUrl = site_setting('social_linkedin', '');
    $twitterUrl = site_setting('social_twitter', '');

    $channelsData = [
        'whatsapp' => [
            'enabled' => site_setting('floating_whatsapp_enabled', '1') === '1',
            'url' => !empty($cleanWhatsapp) ? "https://wa.me/{$cleanWhatsapp}?text=" . urlencode('Hello Chauhan Agro Industries, I would like to inquire about your agricultural machinery & products.') : null,
            'class' => 'social-whatsapp',
            'icon' => 'bi-whatsapp',
            'title' => ($currentLocale === 'hi' ? 'व्हाट्सएप पर चैट करें' : 'Chat on WhatsApp'),
            'label' => ($currentLocale === 'hi' ? 'व्हाट्सएप' : 'WhatsApp'),
            'target' => '_blank',
        ],
        'facebook' => [
            'enabled' => site_setting('floating_facebook_enabled', '1') === '1',
            'url' => $facebookUrl,
            'class' => 'social-facebook',
            'icon' => 'bi-facebook',
            'title' => ($currentLocale === 'hi' ? 'फेसबुक पेज' : 'Facebook Page'),
            'label' => ($currentLocale === 'hi' ? 'फेसबुक' : 'Facebook'),
            'target' => '_blank',
        ],
        'instagram' => [
            'enabled' => site_setting('floating_instagram_enabled', '1') === '1',
            'url' => $instagramUrl,
            'class' => 'social-instagram',
            'icon' => 'bi-instagram',
            'title' => ($currentLocale === 'hi' ? 'इंस्टाग्राम प्रोफाइल' : 'Instagram Profile'),
            'label' => ($currentLocale === 'hi' ? 'इंस्टाग्राम' : 'Instagram'),
            'target' => '_blank',
        ],
        'youtube' => [
            'enabled' => site_setting('floating_youtube_enabled', '1') === '1',
            'url' => $youtubeUrl,
            'class' => 'social-youtube',
            'icon' => 'bi-youtube',
            'title' => ($currentLocale === 'hi' ? 'यूट्यूब चैनल' : 'YouTube Channel'),
            'label' => ($currentLocale === 'hi' ? 'यूट्यूब' : 'YouTube'),
            'target' => '_blank',
        ],
        'phone' => [
            'enabled' => site_setting('floating_phone_enabled', '1') === '1',
            'url' => !empty($cleanPhone) ? "tel:{$cleanPhone}" : null,
            'class' => 'social-phone',
            'icon' => 'bi-telephone-fill',
            'title' => ($currentLocale === 'hi' ? 'कॉल करें: ' . $phoneNum : 'Call Us: ' . $phoneNum),
            'label' => ($currentLocale === 'hi' ? 'कॉल करें' : 'Call Toll-Free'),
            'target' => null,
        ],
        'linkedin' => [
            'enabled' => site_setting('floating_linkedin_enabled', '0') === '1',
            'url' => $linkedinUrl,
            'class' => 'social-linkedin',
            'icon' => 'bi-linkedin',
            'title' => 'LinkedIn',
            'label' => 'LinkedIn',
            'target' => '_blank',
        ],
        'twitter' => [
            'enabled' => site_setting('floating_twitter_enabled', '0') === '1',
            'url' => $twitterUrl,
            'class' => 'social-twitter',
            'icon' => 'bi-twitter-x',
            'title' => 'Twitter (X)',
            'label' => 'X (Twitter)',
            'target' => '_blank',
        ],
    ];
@endphp

@if($floatingEnabled)
    <!-- Sticky Social Media Bar (Right Middle) with Genuine Brand Colors & Dynamic Sorting -->
    <div class="sticky-social-bar" id="stickySocialBar" aria-label="Social Media and Instant Contact Channels">
        @foreach($savedOrder as $chKey)
            @if(isset($channelsData[$chKey]))
                @php $ch = $channelsData[$chKey]; @endphp
                @if($ch['enabled'] && !empty($ch['url']))
                    <a href="{{ $ch['url'] }}"
                       class="sticky-social-item {{ $ch['class'] }}"
                       @if($ch['target']) target="{{ $ch['target'] }}" rel="noopener noreferrer" @endif
                       title="{{ $ch['title'] }}">
                        <span class="social-label-text">{{ $ch['label'] }}</span>
                        <span class="social-icon-wrapper"><i class="bi {{ $ch['icon'] }}"></i></span>
                    </a>
                @endif
            @endif
        @endforeach
    </div>
@endif
