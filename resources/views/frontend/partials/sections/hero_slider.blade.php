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

    $carouselId = 'agroHeroCarousel_' . (isset($section) && $section->id ? $section->id : 'main');
@endphp

@if($slides->isNotEmpty())
    <div id="{{ $carouselId }}" class="carousel slide agro-hero-carousel position-relative" data-bs-ride="carousel" data-bs-interval="5000">
        @if($slides->count() > 1)
            <div class="carousel-indicators" style="z-index: 25;">
                @foreach($slides as $index => $banner)
                    <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner">
            @foreach($slides as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="agro-hero-slide" style="background-image: url('{{ asset($banner->image_path) }}?v=2');">
                        <div class="agro-hero-overlay"></div>
                        <div class="container position-relative py-5 hero-content-container" style="z-index: 2;">
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
            <button class="carousel-control-prev agro-carousel-btn agro-carousel-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev" aria-label="Previous Slide">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-control-next agro-carousel-btn agro-carousel-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next" aria-label="Next Slide">
                <i class="bi bi-chevron-right"></i>
            </button>
        @endif
    </div>

    <style>
    /* Hero Carousel Control Buttons Fix & Styling */
    .agro-hero-carousel .agro-carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 52px;
        height: 52px;
        background: rgba(15, 23, 42, 0.7);
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        color: #ffffff;
        font-size: 1.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 30 !important;
        opacity: 0.85;
        cursor: pointer;
        padding: 0;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        transition: all 0.22s ease-in-out;
    }
    .agro-hero-carousel .agro-carousel-btn:hover,
    .agro-hero-carousel .agro-carousel-btn:focus {
        background: var(--agro-secondary, #ea580c);
        border-color: var(--agro-secondary, #ea580c);
        color: #ffffff;
        opacity: 1;
        transform: translateY(-50%) scale(1.12);
        outline: none;
        box-shadow: 0 6px 20px rgba(234, 88, 12, 0.45);
    }
    .agro-hero-carousel .agro-carousel-prev {
        left: 24px;
        right: auto;
    }
    .agro-hero-carousel .agro-carousel-next {
        right: 24px;
        left: auto;
    }
    @media (max-width: 576px) {
        .agro-hero-carousel .agro-carousel-btn {
            width: 40px;
            height: 40px;
            font-size: 1.05rem;
        }
        .agro-hero-carousel .agro-carousel-prev {
            left: 10px;
        }
        .agro-hero-carousel .agro-carousel-next {
            right: 10px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const carouselEl = document.getElementById('{{ $carouselId }}');
        if (!carouselEl) return;

        // Initialize or get Bootstrap carousel instance
        let bsCarousel = null;
        if (window.bootstrap && window.bootstrap.Carousel) {
            bsCarousel = window.bootstrap.Carousel.getOrCreateInstance(carouselEl, {
                interval: 5000,
                ride: 'carousel',
                wrap: true
            });
        }

        const prevBtn = carouselEl.querySelector('.agro-carousel-prev');
        const nextBtn = carouselEl.querySelector('.agro-carousel-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (bsCarousel) {
                    bsCarousel.prev();
                } else if (window.bootstrap && window.bootstrap.Carousel) {
                    window.bootstrap.Carousel.getOrCreateInstance(carouselEl).prev();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (bsCarousel) {
                    bsCarousel.next();
                } else if (window.bootstrap && window.bootstrap.Carousel) {
                    window.bootstrap.Carousel.getOrCreateInstance(carouselEl).next();
                }
            });
        }
    });
    </script>
@endif
