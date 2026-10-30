<section class="py-5 bg-white" @if(!empty($section->settings_json['animation_effect'])) data-section-anim="{{ $section->settings_json['animation_effect'] }}" @endif @if(!empty($section->settings_json['animation_duration'])) data-section-duration="{{ $section->settings_json['animation_duration'] }}" @endif>
    <div class="container py-4">
        <div class="text-center mb-5 agro-reveal">
            <span class="section-badge">{{ $currentLocale === 'hi' ? 'कृषि उपकरण श्रेणियां' : 'PRODUCT CATEGORIES' }}</span>
            <h2 class="section-title">{{ $section->title ?? ($currentLocale === 'hi' ? 'उत्पाद श्रेणियां' : 'Explore Machinery Lines') }}</h2>
            @if($section->subtitle)
                <p class="section-subtitle">{{ $section->subtitle }}</p>
            @endif
        </div>

        @php
            $displayCategories = \App\Models\ProductCategory::where('is_active', true)
                ->where('is_featured', true)
                ->with('translations')
                ->orderBy('order', 'asc')
                ->take(6)
                ->get();
        @endphp

        <div class="row g-4">
            @foreach($displayCategories as $cat)
                <div class="col-lg-4 col-md-6 agro-reveal stagger-{{ ($loop->index % 3) + 1 }}">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="{{ asset($cat->image ?? 'assets/images/categories/rotary-tiller.jpg') }}?v=2" alt="{{ $cat->name }}" loading="lazy">
                            <div class="category-icon-badge">
                                <i class="bi {{ $cat->icon ?? 'bi-gear-wide' }}"></i>
                            </div>
                        </div>
                        <div class="category-card-body">
                            <h3>{{ $cat->name }}</h3>
                            <p>{{ Str::limit($cat->description, 105) }}</p>
                            <a href="{{ loc_url('/products?category=' . $cat->slug) }}" class="fw-bold text-success d-inline-flex align-items-center gap-1">
                                <span>{{ $currentLocale === 'hi' ? 'मॉडल देखें' : 'Explore Models' }}</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($section->button_text)
            <div class="text-center mt-5 agro-reveal">
                <a href="{{ loc_url($section->button_url ?? '/products') }}" class="btn btn-agro-outline px-4 py-2">
                    {{ $section->button_text }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>
</section>
