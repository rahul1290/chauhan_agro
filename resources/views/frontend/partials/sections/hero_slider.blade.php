@php
    $slides = $banners ?? collect();
    if ($slides->isEmpty()) {
        $pageSlug = isset($page) ? $page->slug : request()->segment(2, 'home');
        $slides = \App\Models\Banner::where('is_active', true)
            ->where(function ($q) use ($pageSlug) {
                $q->where('page_slug', $pageSlug)->orWhere('page_slug', 'home');
            })
            ->with('translations')
            ->orderBy('order', 'asc')
            ->get();
    }

    // Check if section itself has custom title or subtitle
    $secTitle = (isset($section) && !empty($section->title)) ? $section->title : null;
    $secSubtitle = (isset($section) && !empty($section->subtitle)) ? $section->subtitle : null;

    if ($secTitle || $slides->isEmpty()) {
        $customSlide = (object)[
            'image_path' => (isset($page) && $page->featured_image) ? $page->featured_image : 'assets/images/banners/hero-rotavator.jpg',
            'badge_text' => $secSubtitle ? ($currentLocale === 'hi' ? 'चौहान एग्रो इंडस्ट्रीज' : 'CHAUHAN AGRO INDUSTRIES') : null,
            'title' => $secTitle ?: ($page->title ?? 'About Chauhan Agro Industries'),
            'subtitle' => $secSubtitle ?: ($page->subtitle ?? ($currentLocale === 'hi' ? 'उच्च प्रदर्शन वाले कृषि उपकरण और ट्रैक्टर ट्रॉलियां' : 'Pioneering Heavy Duty Agricultural Machinery & Implements Since 1989')),
            'button_text' => (isset($section) && $section->button_text) ? $section->button_text : ($currentLocale === 'hi' ? 'ट्रॉलियां देखें' : 'Explore Machinery'),
            'button_url' => (isset($section) && $section->button_url) ? $section->button_url : '/products',
            'secondary_button_text' => ($currentLocale === 'hi' ? 'संपर्क करें' : 'Contact Us'),
            'secondary_button_url' => '/contact',
        ];
        if ($secTitle) {
            $slides = collect([$customSlide])->merge($slides);
        } elseif ($slides->isEmpty()) {
            $slides = collect([$customSlide]);
        }
    }
@endphp

@if($slides->isNotEmpty())
    <div id="agroHeroCarousel" class="carousel slide agro-hero-carousel" data-bs-ride="carousel">
        @if($slides->count() > 1)
            <div class="carousel-indicators">
                @foreach($slides as $index => $banner)
                    <button type="button" data-bs-target="#agroHeroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @foreach($slides as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="agro-hero-slide" style="background-image: url('{{ asset($banner->image_path) }}?v=2');">
                        <div class="agro-hero-overlay"></div>
                        <div class="container position-relative py-5" style="z-index: 2;">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    @if(!empty($banner->badge_text))
                                        <div class="hero-badge">
                                            <i class="bi bi-patch-check-fill text-warning"></i>
                                            <span>{{ $banner->badge_text }}</span>
                                        </div>
                                    @endif

                                    <h1 class="agro-hero-title">{{ $banner->title }}</h1>
                                    <p class="agro-hero-lead">{{ $banner->subtitle }}</p>

                                    <div class="d-flex flex-wrap gap-3">
                                        @if(!empty($banner->button_text))
                                            <a href="{{ loc_url($banner->button_url ?? '/products') }}" class="btn btn-agro-secondary btn-lg">
                                                {{ $banner->button_text }} <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        @endif

                                        @if(!empty($banner->secondary_button_text))
                                            <a href="{{ loc_url($banner->secondary_button_url ?? '/contact') }}" class="btn btn-outline-light btn-lg">
                                                {{ $banner->secondary_button_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($slides->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#agroHeroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#agroHeroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
@endif
