<section class="py-5 bg-white" @if(!empty($section->settings_json['animation_effect'])) data-section-anim="{{ $section->settings_json['animation_effect'] }}" @endif @if(!empty($section->settings_json['animation_duration'])) data-section-duration="{{ $section->settings_json['animation_duration'] }}" @endif>
    <div class="container py-3">
        @if($section->title)
            <div class="text-center mb-4 agro-reveal">
                <h2 class="section-title">{{ $section->title }}</h2>
                @if($section->subtitle)
                    <p class="section-subtitle">{{ $section->subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10 agro-reveal">
                <div class="cms-editorial-content" style="line-height: 1.85; font-size: 1.05rem; color: #334155;">
                    {!! $section->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
