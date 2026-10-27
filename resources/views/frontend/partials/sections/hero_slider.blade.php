@if(isset($banners) && $banners->isNotEmpty())
    <div id="agroHeroCarousel" class="carousel slide agro-hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
                <button type="button" data-bs-target="#agroHeroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="agro-hero-slide" style="background-image: url('{{ asset($banner->image_path) }}?v=2');">
                        <div class="agro-hero-overlay"></div>
                        <div class="container position-relative py-5" style="z-index: 2;">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    @if($banner->badge_text)
                                        <div class="hero-badge">
                                            <i class="bi bi-patch-check-fill text-warning"></i>
                                            <span>{{ $banner->badge_text }}</span>
                                        </div>
                                    @endif

                                    <h1 class="agro-hero-title">{{ $banner->title }}</h1>
                                    <p class="agro-hero-lead">{{ $banner->subtitle }}</p>

                                    <div class="d-flex flex-wrap gap-3">
                                        @if($banner->button_text)
                                            <a href="{{ loc_url($banner->button_url ?? '/products') }}" class="btn btn-agro-secondary btn-lg">
                                                {{ $banner->button_text }} <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        @endif

                                        @if($banner->secondary_button_text)
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

        <button class="carousel-control-prev" type="button" data-bs-target="#agroHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#agroHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
@endif
