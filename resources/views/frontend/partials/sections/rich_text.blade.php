<section class="py-5 bg-white">
    <div class="container py-3">
        @if($section->title)
            <div class="text-center mb-4">
                <h2 class="section-title">{{ $section->title }}</h2>
                @if($section->subtitle)
                    <p class="section-subtitle">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="cms-editorial-content" style="line-height: 1.85; font-size: 1.05rem; color: #334155;">
                    {!! $section->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
