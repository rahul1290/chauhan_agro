@php
    $popupData = \App\Models\PromotionalPopup::getActivePopup();
    $bannerImage = $section->getSetting('image') ?? ($popupData ? $popupData->image_url : asset('assets/images/popups/exhibition-banner.jpg'));
    $bannerLink = $section->button_url ?? ($popupData ? $popupData->link_url : '/contact');
    $borderColor = $section->getSetting('border_color') ?? ($popupData ? $popupData->border_color : '#16a34a');
@endphp

<section class="py-5 bg-light agro-promo-section">
    <div class="container py-3">
        @if($section->title)
            <div class="text-center mb-4">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill text-uppercase mb-2">
                    {{ $currentLocale === 'hi' ? 'विशेष प्रदर्शनी एवं घोषणा' : 'Exhibition & Special Announcement' }}
                </span>
                <h2 class="fw-bold text-dark">{{ $section->title }}</h2>
                @if($section->subtitle)
                    <p class="text-muted mx-auto" style="max-width: 650px;">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="position-relative rounded-4 overflow-hidden shadow-lg" style="border: 4px solid {{ $borderColor }}; background: #ffffff;">
                    @if($bannerLink)
                        <a href="{{ $bannerLink }}" class="d-block text-decoration-none">
                            <img src="{{ $bannerImage }}" alt="{{ $section->title ?? 'Exhibition Banner' }}" class="img-fluid w-100 d-block">
                        </a>
                    @else
                        <img src="{{ $bannerImage }}" alt="{{ $section->title ?? 'Exhibition Banner' }}" class="img-fluid w-100 d-block">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
