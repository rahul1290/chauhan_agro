<section class="py-5" style="background-color: var(--agro-light-bg);" @if(!empty($section->settings_json['animation_effect'])) data-section-anim="{{ $section->settings_json['animation_effect'] }}" @endif @if(!empty($section->settings_json['animation_duration'])) data-section-duration="{{ $section->settings_json['animation_duration'] }}" @endif>
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 agro-reveal">
            <div>
                <span class="section-badge">{{ $currentLocale === 'hi' ? 'विशेष मशीनरी' : 'ENGINEERED FOR HEAVY DUTY' }}</span>
                <h2 class="section-title mb-0">{{ $section->title ?? ($currentLocale === 'hi' ? 'प्रमुख कृषि मशीनरी' : 'Featured Machinery Showcase') }}</h2>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ loc_url('/products') }}" class="btn btn-agro-outline">
                    {{ $currentLocale === 'hi' ? 'सभी मॉडल देखें' : 'View All Equipment' }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        @php
            $displayProducts = \App\Models\Product::where('is_active', true)
                ->where('is_featured', true)
                ->with(['category.translations', 'translations', 'specifications.translations'])
                ->orderBy('order', 'asc')
                ->take(6)
                ->get();
        @endphp

        <div class="row g-4">
            @foreach($displayProducts as $prod)
                <div class="col-lg-4 col-md-6 agro-reveal stagger-{{ ($loop->index % 3) + 1 }}">
                    <div class="product-card">
                        <div class="product-thumb-wrapper">
                            <img src="{{ asset($prod->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg') }}?v=2" alt="{{ $prod->name }}" loading="lazy">

                            @if($prod->hp_range)
                                <div class="hp-badge">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    <span>{{ $prod->hp_range }}</span>
                                </div>
                            @endif

                            @if($prod->is_featured)
                                <div class="featured-pill">{{ $currentLocale === 'hi' ? 'प्रमुख' : 'Featured' }}</div>
                            @endif
                        </div>

                        <div class="product-body">
                            <div class="product-category-name">{{ $prod->category->name ?? 'Agricultural Implement' }}</div>
                            <h3 class="product-title">
                                <a href="{{ loc_url('/products/' . $prod->slug) }}">{{ $prod->name }}</a>
                            </h3>
                            <p class="product-excerpt">{{ Str::limit($prod->short_description, 110) }}</p>

                            <div class="product-chips">
                                @if($prod->working_width)
                                    <span class="spec-chip"><i class="bi bi-arrows-expand text-success"></i> {{ $prod->working_width }}</span>
                                @endif
                                @if($prod->weight_approx)
                                    <span class="spec-chip"><i class="bi bi-box-seam text-secondary"></i> {{ $prod->weight_approx }}</span>
                                @endif
                                @if($prod->blades_count)
                                    <span class="spec-chip"><i class="bi bi-gear-fill text-warning"></i> {{ $prod->blades_count }} Blades</span>
                                @endif
                            </div>

                            <div class="product-footer">
                                <a href="{{ loc_url('/products/' . $prod->slug) }}" class="btn btn-sm btn-agro-outline flex-grow-1">
                                    {{ $currentLocale === 'hi' ? 'विवरण देखें' : 'View Specs' }}
                                </a>
                                <button class="btn btn-sm btn-agro-secondary" data-bs-toggle="modal" data-bs-target="#enquiryModal" title="{{ $currentLocale === 'hi' ? 'कोटेशन मांगें' : 'Enquire' }}">
                                    <i class="bi bi-chat-quote-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
