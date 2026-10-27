<section class="py-5" style="background-color: var(--agro-light-bg);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="section-badge">{{ $currentLocale === 'hi' ? 'सामान्य प्रश्न' : 'FREQUENTLY ASKED QUESTIONS' }}</span>
            <h2 class="section-title">{{ $section->title ?? ($currentLocale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions') }}</h2>
            @if($section->subtitle)
                <p class="section-subtitle">{{ $section->subtitle }}</p>
            @endif
        </div>

        @php
            $faqs = $section->data;
            if (empty($faqs)) {
                $faqs = [
                    ['q' => 'What warranty is offered on AgroVision gearboxes?', 'a' => 'All multi-speed gearboxes carry a 24-month comprehensive replacement warranty against manufacturing defects.'],
                    ['q' => 'Are genuine boron steel spare blades readily available?', 'a' => 'Yes, through our 1,800+ authorized dealers and direct regional spare depots, genuine blades and wearing components are delivered within 48 hours.'],
                    ['q' => 'What tractor HP is required for the 7ft Gold Rotavator?', 'a' => 'The 7ft Gold Series Rotavator operates optimally with tractors ranging from 45 HP to 65 HP equipped with standard 540 RPM PTO.'],
                ];
            }
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion{{ $section->id }}">
                    @foreach($faqs as $idx => $f)
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header" id="heading{{ $section->id }}_{{ $idx }}">
                                <button class="accordion-button {{ $idx === 0 ? '' : 'collapsed' }} fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $section->id }}_{{ $idx }}" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $section->id }}_{{ $idx }}">
                                    {{ $f['q'] ?? '' }}
                                </button>
                            </h2>
                            <div id="collapse{{ $section->id }}_{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $section->id }}_{{ $idx }}" data-bs-parent="#faqAccordion{{ $section->id }}">
                                <div class="accordion-body text-muted" style="line-height: 1.7;">
                                    {{ $f['a'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
