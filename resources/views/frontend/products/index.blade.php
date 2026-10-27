@extends('frontend.layouts.app')

@section('meta_title', ($selectedCategory ? $selectedCategory->name . ' | ' : '') . ($currentLocale === 'hi' ? 'कृषि उपकरण सूची | चौहान एग्रो इंडस्ट्रीज' : 'Machinery Catalogue | Chauhan Agro Industries'))
@section('meta_description', $selectedCategory ? $selectedCategory->description : 'Browse heavy-duty agricultural machinery: Rotary tillers, disc harrows, laser levellers, and seed drills from Chauhan Agro Industries.')

@section('content')

    <!-- Header Banner -->
    <section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-bottom: 4px solid var(--agro-secondary);">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ loc_url('/') }}" class="text-light-muted">{{ $currentLocale === 'hi' ? 'होम' : 'Home' }}</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $currentLocale === 'hi' ? 'कृषि मशीनरी' : 'Machinery Catalogue' }}</li>
                    @if($selectedCategory)
                        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $selectedCategory->name }}</li>
                    @endif
                </ol>
            </nav>
            <h1 class="text-white fw-bold display-5 mb-2">
                {{ $selectedCategory ? $selectedCategory->name : ($currentLocale === 'hi' ? 'कृषि मशीनरी एवं उपकरण' : 'Agricultural Machinery & Implements') }}
            </h1>
            <p class="text-light-muted mb-0" style="max-width: 700px; font-size: 1.05rem;">
                {{ $selectedCategory ? $selectedCategory->description : ($currentLocale === 'hi' ? 'कठोर भूमि की जुताई और आधुनिक खेती के लिए उच्च गुणवत्ता वाले कृषि उपकरण।' : 'Discover world-class heavy duty farm machinery engineered with precision metallurgy and robotic laser alignment.') }}
            </p>
        </div>
    </section>

    <!-- Catalogue Main Content -->
    <section class="py-5" style="background-color: var(--agro-light-bg);">
        <div class="container">
            <div class="row g-4">
                <!-- Left Sidebar Filters -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white sticky-top" style="top: 100px; z-index: 10;">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-funnel-fill text-success me-2"></i>{{ $currentLocale === 'hi' ? 'फिल्टर' : 'Filter By' }}</span>
                            @if(request()->hasAny(['category', 'hp', 'search', 'sort']))
                                <a href="{{ loc_url('/products') }}" class="btn btn-sm btn-link text-danger p-0" style="font-size: 0.78rem;">{{ $currentLocale === 'hi' ? 'हटाएं' : 'Reset' }}</a>
                            @endif
                        </h5>

                        <form action="{{ loc_url('/products') }}" method="GET">
                            <!-- Search Field -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">{{ $currentLocale === 'hi' ? 'खोजें' : 'Keyword Search' }}</label>
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ $currentLocale === 'hi' ? 'मॉडल, कोड...' : 'Model, SKU...' }}">
                                    <button class="btn btn-agro-primary" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">{{ $currentLocale === 'hi' ? 'उपकरण श्रेणी' : 'Categories' }}</label>
                                <div class="list-group list-group-flush">
                                    <a href="{{ loc_url('/products' . (request('hp') ? '?hp=' . request('hp') : '')) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 border-0 {{ !request('category') ? 'fw-bold text-success' : 'text-dark' }}">
                                        <span>{{ $currentLocale === 'hi' ? 'सभी श्रेणियां' : 'All Categories' }}</span>
                                    </a>
                                    @foreach($categories as $cat)
                                        <a href="{{ loc_url('/products?category=' . $cat->slug . (request('hp') ? '&hp=' . request('hp') : '')) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 py-2 border-0 {{ request('category') === $cat->slug ? 'fw-bold text-success' : 'text-dark' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="badge bg-light text-secondary rounded-pill">{{ $cat->products_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tractor HP Requirement Filter -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">{{ $currentLocale === 'hi' ? 'ट्रैक्टर पावर (HP)' : 'Tractor Power (HP)' }}</label>
                                <div class="d-flex flex-column gap-2">
                                    @php $selectedHp = request('hp'); @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="hp" id="hpAll" value="" {{ empty($selectedHp) ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="hpAll">{{ $currentLocale === 'hi' ? 'सभी हॉर्सपावर' : 'Any Tractor HP' }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="hp" id="hp45" value="45" {{ $selectedHp == '45' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="hp45">35 - 50 HP (Compact & Utility)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="hp" id="hp60" value="60" {{ $selectedHp == '60' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="hp60">50 - 75 HP (Medium Heavy Duty)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="hp" id="hp85" value="85" {{ $selectedHp == '85' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="form-check-label small" for="hp85">75+ HP (Commercial Fleet)</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Products Grid -->
                <div class="col-lg-9">
                    <!-- Top sorting & count bar -->
                    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white d-flex flex-row justify-content-between align-items-center">
                        <div class="small text-muted">
                            {{ $currentLocale === 'hi' ? 'कुल' : 'Showing' }} <strong class="text-dark">{{ $products->total() }}</strong> {{ $currentLocale === 'hi' ? 'उपकरण उपलब्ध' : 'machinery models' }}
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted d-none d-sm-inline">{{ $currentLocale === 'hi' ? 'क्रम:' : 'Sort By:' }}</span>
                            <form action="{{ url()->current() }}" method="GET" class="d-inline">
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if(request('hp')) <input type="hidden" name="hp" value="{{ request('hp') }}"> @endif
                                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>{{ $currentLocale === 'hi' ? 'प्रमुख मॉडल' : 'Featured Models' }}</option>
                                    <option value="hp_asc" {{ request('sort') === 'hp_asc' ? 'selected' : '' }}>{{ $currentLocale === 'hi' ? 'HP: कम से अधिक' : 'HP: Low to High' }}</option>
                                    <option value="hp_desc" {{ request('sort') === 'hp_desc' ? 'selected' : '' }}>{{ $currentLocale === 'hi' ? 'HP: अधिक से कम' : 'HP: High to Low' }}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->isNotEmpty())
                        <div class="row g-4">
                            @foreach($products as $prod)
                                <div class="col-md-6 col-xl-4">
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
                                            <div class="product-category-name">{{ $prod->category->name ?? 'Implement' }}</div>
                                            <h3 class="product-title">
                                                <a href="{{ loc_url('/products/' . $prod->slug) }}">{{ $prod->name }}</a>
                                            </h3>
                                            <p class="product-excerpt">{{ Str::limit($prod->short_description, 100) }}</p>

                                            <div class="product-chips">
                                                @if($prod->working_width)
                                                    <span class="spec-chip"><i class="bi bi-arrows-expand text-success"></i> {{ $prod->working_width }}</span>
                                                @endif
                                                @if($prod->weight_approx)
                                                    <span class="spec-chip"><i class="bi bi-box-seam text-secondary"></i> {{ $prod->weight_approx }}</span>
                                                @endif
                                            </div>

                                            <div class="product-footer">
                                                <a href="{{ loc_url('/products/' . $prod->slug) }}" class="btn btn-sm btn-agro-outline flex-grow-1">
                                                    {{ $currentLocale === 'hi' ? 'तकनीकी विवरण' : 'View Specs' }}
                                                </a>
                                                <button class="btn btn-sm btn-agro-secondary" data-bs-toggle="modal" data-bs-target="#enquiryModal" title="{{ $currentLocale === 'hi' ? 'कोटेशन प्राप्त करें' : 'Get Quote' }}">
                                                    <i class="bi bi-chat-quote-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-5 d-flex justify-content-center">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="card border-0 shadow-sm p-5 text-center rounded-4 bg-white">
                            <div class="display-1 text-muted mb-3"><i class="bi bi-search"></i></div>
                            <h4 class="fw-bold text-dark">{{ $currentLocale === 'hi' ? 'कोई मशीन नहीं मिली' : 'No Machinery Found' }}</h4>
                            <p class="text-muted mb-4">{{ $currentLocale === 'hi' ? 'कृपया अन्य श्रेणी या फ़िल्टर का चयन करें।' : 'Try adjusting your search criteria or resetting the category filter.' }}</p>
                            <div>
                                <a href="{{ loc_url('/products') }}" class="btn btn-agro-primary px-4">{{ $currentLocale === 'hi' ? 'सभी मॉडल देखें' : 'View All Machinery' }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
