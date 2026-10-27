<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                @if($section->subtitle)
                    <span class="section-badge">{{ $section->subtitle }}</span>
                @endif
                <h2 class="section-title">{{ $section->title }}</h2>
                <div class="text-muted mb-4" style="line-height: 1.8;">
                    {!! $section->content !!}
                </div>
                @if($section->button_text)
                    <a href="{{ loc_url($section->button_url ?? '/contact') }}" class="btn btn-agro-primary">
                        {{ $section->button_text }} <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
            <div class="col-lg-6">
                @php
                    $imgUrl = $section->getSetting('image') ?? asset('assets/images/products/gold-rotary-tiller.jpg');
                @endphp
                <div class="rounded-4 overflow-hidden shadow-lg border">
                    <img src="{{ $imgUrl }}" alt="{{ $section->title }}" class="img-fluid w-100" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>
