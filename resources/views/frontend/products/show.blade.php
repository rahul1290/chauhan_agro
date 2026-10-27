@extends('frontend.layouts.app')

@section('meta_title', $product->meta_title ?? ($product->name . ' | AgroVision Machinery'))
@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 155))
@section('og_image', asset($product->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg'))

@section('content')

    <!-- Breadcrumb Banner -->
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.88rem;">
                    <li class="breadcrumb-item"><a href="{{ loc_url('/') }}">{{ $currentLocale === 'hi' ? 'होम' : 'Home' }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ loc_url('/products') }}">{{ $currentLocale === 'hi' ? 'कृषि मशीनरी' : 'Machinery' }}</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ loc_url('/products?category=' . $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Details Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">
                <!-- Left: Image Gallery -->
                <div class="col-lg-6">
                    <div class="product-gallery-main mb-3">
                        <img id="mainGalleryImg" src="{{ asset($product->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg') }}" alt="{{ $product->name }}">
                    </div>

                    @if($product->images->isNotEmpty())
                        <div class="gallery-thumbs">
                            <div class="gallery-thumb-item active" onclick="switchMainImage('{{ asset($product->featured_image) }}', this)">
                                <img src="{{ asset($product->featured_image) }}" alt="{{ $product->name }}">
                            </div>
                            @foreach($product->images as $img)
                                <div class="gallery-thumb-item" onclick="switchMainImage('{{ asset($img->image_path) }}', this)">
                                    <img src="{{ asset($img->image_path) }}" alt="{{ $img->caption }}">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Trust Highlights Cards -->
                    <div class="row g-3 mt-4">
                        <div class="col-sm-4 col-6">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <i class="bi bi-patch-check-fill text-success fs-3 mb-1"></i>
                                <div class="small fw-bold text-dark">ISO 9001:2015</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Quality Assured</small>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <i class="bi bi-shield-shaded text-warning fs-3 mb-1"></i>
                                <div class="small fw-bold text-dark">Boron Steel 52 HRC</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Extra Durability</small>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <i class="bi bi-wrench-adjustable text-primary fs-3 mb-1"></i>
                                <div class="small fw-bold text-dark">24 Months Warranty</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Gearbox Protected</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Information & Quick Quote -->
                <div class="col-lg-6">
                    @if($product->category)
                        <div class="text-uppercase fw-bold text-success small mb-2 tracking-wide">
                            <i class="bi bi-tag-fill me-1"></i> {{ $product->category->name }}
                        </div>
                    @endif

                    <h1 class="display-6 fw-bold text-dark mb-3">{{ $product->name }}</h1>

                    @if($product->sku)
                        <div class="mb-3 text-muted small">
                            <span class="fw-semibold text-secondary">SKU / Model Code:</span> <code class="text-dark bg-light px-2 py-1 rounded">{{ $product->sku }}</code>
                        </div>
                    @endif

                    <p class="lead text-secondary mb-4" style="font-size: 1.1rem; line-height: 1.7;">
                        {{ $product->short_description }}
                    </p>

                    <!-- Key Specification Chips -->
                    <div class="p-3 rounded-4 mb-4" style="background-color: var(--agro-light-bg); border: 1px solid var(--agro-border);">
                        <div class="row g-3">
                            @if($product->hp_range)
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-white p-2 rounded-3 shadow-sm text-warning"><i class="bi bi-lightning-charge-fill fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Tractor Power</small>
                                            <strong class="text-dark">{{ $product->hp_range }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($product->working_width)
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-white p-2 rounded-3 shadow-sm text-success"><i class="bi bi-arrows-expand fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Working Width</small>
                                            <strong class="text-dark">{{ $product->working_width }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($product->weight_approx)
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-white p-2 rounded-3 shadow-sm text-primary"><i class="bi bi-box-seam fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Weight Approx</small>
                                            <strong class="text-dark">{{ $product->weight_approx }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($product->blades_count)
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-white p-2 rounded-3 shadow-sm text-danger"><i class="bi bi-gear-fill fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Blade Count</small>
                                            <strong class="text-dark">{{ $product->blades_count }} Blades</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Call to Action Buttons -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <button class="btn btn-agro-secondary btn-lg flex-grow-1" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="bi bi-chat-quote-fill me-2"></i>
                            {{ $currentLocale === 'hi' ? 'फैक्टरी कोटेशन प्राप्त करें' : 'Request Factory Quote' }}
                        </button>

                        @if($product->brochure_pdf)
                            <a href="{{ asset($product->brochure_pdf) }}" target="_blank" class="btn btn-agro-outline btn-lg" download>
                                <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>
                                {{ $currentLocale === 'hi' ? 'ब्रोशर डाउनलोड करें' : 'Download Brochure' }}
                            </a>
                        @endif
                    </div>

                    <div class="border-top pt-3 text-muted small d-flex align-items-center gap-3">
                        <span><i class="bi bi-truck text-success me-1"></i> Nationwide Dispatch</span>
                        <span>•</span>
                        <span><i class="bi bi-credit-card text-success me-1"></i> Subsidy & Finance Eligible</span>
                    </div>
                </div>
            </div>

            <!-- Tabbed Detailed Content: Specs, Description, Features -->
            <div class="mt-5 pt-4">
                <ul class="nav nav-tabs nav-fill border-bottom-2" id="productDetailTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-3 fs-6" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button" role="tab" aria-controls="specs-pane" aria-selected="true">
                            <i class="bi bi-table me-2 text-warning"></i>
                            {{ $currentLocale === 'hi' ? 'तकनीकी विनिर्देश' : 'Technical Specifications' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 fs-6" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab" aria-controls="desc-pane" aria-selected="false">
                            <i class="bi bi-file-text me-2 text-success"></i>
                            {{ $currentLocale === 'hi' ? 'विस्तृत विवरण' : 'Overview & Description' }}
                        </button>
                    </li>
                    @if(!empty($product->features))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-3 fs-6" id="features-tab" data-bs-toggle="tab" data-bs-target="#features-pane" type="button" role="tab" aria-controls="features-pane" aria-selected="false">
                                <i class="bi bi-stars me-2 text-primary"></i>
                                {{ $currentLocale === 'hi' ? 'मुख्य विशेषताएं' : 'Key Features & Benefits' }}
                            </button>
                        </li>
                    @endif
                </ul>

                <div class="tab-content pt-4" id="productDetailTabContent">
                    <!-- Tab 1: Technical Specs Table -->
                    <div class="tab-pane fade show active" id="specs-pane" role="tabpanel" aria-labelledby="specs-tab" tabindex="0">
                        @if($product->specifications->isNotEmpty())
                            <div class="table-responsive">
                                <table class="specs-table">
                                    <thead>
                                        <tr>
                                            <th>{{ $currentLocale === 'hi' ? 'मापदंड / विशेषता' : 'Parameter / Specification' }}</th>
                                            <th>{{ $currentLocale === 'hi' ? 'मान / तकनीकी विवरण' : 'Value / Engineering Specification' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->specifications as $spec)
                                            <tr>
                                                <td class="spec-key">{{ $spec->key }}</td>
                                                <td class="spec-val fw-bold">{{ $spec->value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">{{ $currentLocale === 'hi' ? 'तकनीकी विनिर्देश जल्द ही अपडेट किए जाएंगे।' : 'Technical specifications will be updated shortly.' }}</p>
                        @endif
                    </div>

                    <!-- Tab 2: Overview Description -->
                    <div class="tab-pane fade" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab" tabindex="0">
                        <div class="cms-editorial-content p-4 bg-light rounded-4 border" style="line-height: 1.85;">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Tab 3: Features List -->
                    @if(!empty($product->features))
                        <div class="tab-pane fade" id="features-pane" role="tabpanel" aria-labelledby="features-tab" tabindex="0">
                            <div class="row g-3">
                                @foreach($product->features as $feat)
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3 border d-flex align-items-start gap-3 h-100">
                                            <div class="text-success fs-5"><i class="bi bi-check-circle-fill"></i></div>
                                            <div class="text-dark fw-medium">{{ $feat }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Products Section -->
            @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
                <div class="mt-5 pt-5 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold text-dark mb-0">{{ $currentLocale === 'hi' ? 'संबंधित कृषि मशीनरी' : 'Related Machinery & Implements' }}</h3>
                        <a href="{{ loc_url('/products') }}" class="btn btn-sm btn-agro-outline">{{ $currentLocale === 'hi' ? 'सभी देखें' : 'View All' }}</a>
                    </div>

                    <div class="row g-4">
                        @foreach($relatedProducts as $rel)
                            <div class="col-lg-4 col-md-6">
                                <div class="product-card">
                                    <div class="product-thumb-wrapper">
                                        <img src="{{ asset($rel->featured_image ?? 'assets/images/products/gold-rotary-tiller.jpg') }}" alt="{{ $rel->name }}" loading="lazy">
                                        @if($rel->hp_range)
                                            <div class="hp-badge"><i class="bi bi-lightning-charge-fill"></i> {{ $rel->hp_range }}</div>
                                        @endif
                                    </div>
                                    <div class="product-body">
                                        <div class="product-category-name">{{ $rel->category->name ?? 'Implement' }}</div>
                                        <h4 class="product-title"><a href="{{ loc_url('/products/' . $rel->slug) }}">{{ $rel->name }}</a></h4>
                                        <p class="product-excerpt">{{ Str::limit($rel->short_description, 90) }}</p>
                                        <a href="{{ loc_url('/products/' . $rel->slug) }}" class="btn btn-sm btn-agro-outline w-100 mt-auto">
                                            {{ $currentLocale === 'hi' ? 'विवरण देखें' : 'View Details' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection

@push('scripts')
<script>
    function switchMainImage(src, element) {
        document.getElementById('mainGalleryImg').src = src;
        document.querySelectorAll('.gallery-thumb-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }
</script>
@endpush
